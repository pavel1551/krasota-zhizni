<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayBookingRequest;
use App\Http\Requests\RefundBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\BookingEquipment;
use App\Models\Equipment;
use App\Models\Hall;
use App\Models\Payment;
use App\Models\Refund;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use App\Services\RefundPolicyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct(
        private PricingService $pricing,
        private AvailabilityService $availability,
        private RefundPolicyService $refundPolicy
    ) {}

    public function history()
    {
        $bookings = Booking::query()
            ->where('user_id', auth()->id())
            ->with(['hall.studio', 'items.equipment', 'payments'])
            ->orderByDesc('id')
            ->get();

        return view('bookings.history', compact('bookings'));
    }

    public function create(Hall $hall)
    {
        $hall->load('studio');

        $equipment = Equipment::query()
            ->where('studio_id', $hall->studio_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('bookings.create', compact('hall', 'equipment'));
    }

    public function store(StoreBookingRequest $request)
    {
        $user = auth()->user();

        $hall = Hall::query()->findOrFail((int)$request->validated()['hall_id']);

        $startAt = Carbon::parse($request->validated()['start_at']);
        $endAt   = Carbon::parse($request->validated()['end_at']);

        // 1) Проверка зала
        if (!$this->availability->hallIsFree($hall, $startAt, $endAt)) {
            return back()->withInput()->withErrors([
                'start_at' => 'Зал уже занят на выбранное время. Выбери другой интервал.',
            ]);
        }

        // 2) Реквизит
        $equipmentInput = $request->validated()['equipment'] ?? [];
        $equipmentIds = array_map('intval', array_keys($equipmentInput));

        $equipmentList = Equipment::query()
            ->whereIn('id', $equipmentIds)
            ->where('studio_id', $hall->studio_id)
            ->where('is_active', true)
            ->get();

        $errors = $this->availability->equipmentIsEnough($equipmentList, $equipmentInput, $startAt, $endAt);
        if (!empty($errors)) {
            // Превращаем ошибки в формат Laravel
            return back()->withInput()->withErrors($errors);
        }

        // 3) Расчёт цены
        $hours = (int) ceil($startAt->diffInMinutes($endAt) / 60);

        $multiplier = $this->pricing->multiplierFor(now(), $startAt);
        $priceHall  = $this->pricing->hallPrice($hall, $hours, $multiplier);

        $priceEquip = 0;
        foreach ($equipmentList as $eq) {
            $need = (int)($equipmentInput[$eq->id] ?? 0);
            if ($need > 0) {
                $priceEquip += $this->pricing->equipmentPrice((int)$eq->price_per_hour, $hours, $need);
            }
        }

        $total = $priceHall + $priceEquip;

        $reserveMinutes = (int) config('booking.reservation_minutes', 15);
        $reservedUntil = now()->addMinutes($reserveMinutes);

        // 4) Сохраняем в транзакции (на случай гонок)
        $booking = DB::transaction(function () use ($user, $hall, $startAt, $endAt, $reservedUntil, $priceHall, $priceEquip, $total, $multiplier, $equipmentList, $equipmentInput) {

            $booking = Booking::create([
                'user_id' => $user->id,
                'hall_id' => $hall->id,
                'start_at' => $startAt,
                'end_at'   => $endAt,
                'status'   => 'awaiting_payment',
                'reserved_until' => $reservedUntil,

                'pricing_multiplier' => $multiplier,
                'price_hall' => $priceHall,
                'price_equipment' => $priceEquip,
                'price_total' => $total,
            ]);

            // Сохраняем реквизит как снимок: qty + price_per_hour
            foreach ($equipmentList as $eq) {
                $need = (int)($equipmentInput[$eq->id] ?? 0);
                if ($need > 0) {
                    BookingEquipment::create([
                        'booking_id' => $booking->id,
                        'equipment_id' => $eq->id,
                        'qty' => $need,
                        'price_per_hour' => (int)$eq->price_per_hour,
                    ]);
                }
            }

            // Создаём запись оплаты "pending" (заглушка)
            Payment::create([
                'booking_id' => $booking->id,
                'status' => 'pending',
                'amount' => $booking->price_total,
            ]);

            return $booking;
        });

        return redirect()->route('bookings.pay.form', $booking);
    }

    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load(['hall.studio', 'items.equipment', 'payments', 'refunds']);

        return view('bookings.show', compact('booking'));
    }

    public function payForm(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load(['hall.studio', 'items.equipment', 'payments']);

        // Если резерв истёк — сразу пометим как expired
        Booking::expireOldReservations();
        $booking->refresh();

        return view('bookings.pay', compact('booking'));
    }

    public function pay(PayBookingRequest $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        Booking::expireOldReservations();
        $booking->refresh();

        if ($booking->status !== 'awaiting_payment') {
            return redirect()->route('bookings.show', $booking)
                ->with('status', 'Оплата невозможна: бронирование уже не в статусе ожидания оплаты.');
        }

        if ($booking->reserved_until && $booking->reserved_until->lte(now())) {
            $booking->update(['status' => 'expired']);
            return redirect()->route('bookings.show', $booking)
                ->with('status', 'Резерв времени истёк. Бронирование просрочено.');
        }

        $outcome = $request->validated()['outcome'];

        $cardHolder = $request->validated()['card_holder'] ?? null;
        $cardLast4  = $request->validated()['card_last4'] ?? null;

        // Обновляем payment (последняя запись)
        $payment = $booking->payments()->latest('id')->first();

        if ($payment) {
            $payment->update([
                'card_last4' => $cardLast4,
                'card_holder_encrypted' => $cardHolder ? Crypt::encryptString($cardHolder) : null,
            ]);
        }

        if ($outcome === 'success') {
            DB::transaction(function () use ($booking, $payment) {
                $booking->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                if ($payment) {
                    $payment->update(['status' => 'success']);
                }
            });

            return redirect()->route('bookings.show', $booking)
                ->with('status', 'Оплата успешна (ЗАГЛУШКА). Бронирование подтверждено.');
        }

        if ($outcome === 'timeout') {
            // Таймаут — считаем, что платёж не завершился
            DB::transaction(function () use ($booking, $payment) {
                $booking->update([
                    'status' => 'failed',
                    'cancel_reason' => 'Payment timeout (stub)',
                ]);
                if ($payment) {
                    $payment->update(['status' => 'timeout']);
                }
            });

            return redirect()->route('bookings.show', $booking)
                ->with('status', 'Таймаут оплаты (ЗАГЛУШКА). Бронирование не подтверждено.');
        }

        // failed
        DB::transaction(function () use ($booking, $payment) {
            $booking->update([
                'status' => 'failed',
                'cancel_reason' => 'Payment failed (stub)',
            ]);
            if ($payment) {
                $payment->update(['status' => 'failed']);
            }
        });

        return redirect()->route('bookings.show', $booking)
            ->with('status', 'Оплата отклонена (ЗАГЛУШКА). Бронирование не подтверждено.');
    }

    public function cancel(Booking $booking)
    {
        $this->authorizeBooking($booking);

        // Отменять можно только если не оплачено
        if (!in_array($booking->status, ['awaiting_payment', 'failed'], true)) {
            return back()->with('status', 'Отмена невозможна: бронирование уже оплачено или завершено.');
        }

        $booking->update([
            'status' => 'canceled',
            'cancel_reason' => 'Canceled by user',
        ]);

        return redirect()->route('bookings.show', $booking)->with('status', 'Бронирование отменено.');
    }

    public function refund(RefundBookingRequest $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->refresh();

        if (!$this->refundPolicy->canRefund($booking)) {
            return back()->with('status', 'Возврат невозможен по правилам (≤4ч после оплаты и ≥2ч до начала).');
        }

        $amount = (int)$booking->price_total;

        DB::transaction(function () use ($booking, $amount) {
            $booking->update([
                'status' => 'refunded',
                'refund_amount' => $amount,
                'refunded_at' => now(),
            ]);

            Refund::create([
                'booking_id' => $booking->id,
                'status' => 'success',
                'amount' => $amount,
                'policy_snapshot' => $this->refundPolicy->policySnapshot(),
            ]);
        });

        return redirect()->route('bookings.show', $booking)
            ->with('status', 'Возврат выполнен (демонстрация).');
    }

    private function authorizeBooking(Booking $booking): void
    {
        // Простая авторизация: пользователь видит только свои брони, админ видит всё
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->is_admin) {
            return;
        }

        if ((int)$booking->user_id !== (int)$user->id) {
            abort(403);
        }
    }
}
