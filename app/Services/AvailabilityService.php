<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Equipment;
use App\Models\Hall;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Проверяем, свободен ли зал на интервал.
     */
    public function hallIsFree(Hall $hall, Carbon $startAt, Carbon $endAt): bool
    {
        // Сначала “чистим” просроченные резервы, чтобы не мешали
        Booking::expireOldReservations();

        $conflict = Booking::query()
            ->blocking()
            ->where('hall_id', $hall->id)
            ->where(function ($q) use ($startAt, $endAt) {
                // Пересечение интервалов:
                // start < other_end AND end > other_start
                $q->where('start_at', '<', $endAt)
                  ->where('end_at', '>', $startAt);
            })
            ->exists();

        return !$conflict;
    }

    /**
     * Считаем, сколько единиц equipment занято на интервале.
     */
    public function equipmentBusyQty(Equipment $equipment, Carbon $startAt, Carbon $endAt): int
    {
        Booking::expireOldReservations();

        // Находим брони, которые пересекаются по времени и блокируют слот
        $bookings = Booking::query()
            ->blocking()
            ->whereHas('hall', function ($q) use ($equipment) {
                // Реквизит привязан к студии, а залы тоже к студии.
                // Но проще: ограничим только по equipment_id в pivot ниже.
            })
            ->where(function ($q) use ($startAt, $endAt) {
                $q->where('start_at', '<', $endAt)
                  ->where('end_at', '>', $startAt);
            })
            ->whereHas('items', function ($q) use ($equipment) {
                $q->where('equipment_id', $equipment->id);
            })
            ->with(['items' => function ($q) use ($equipment) {
                $q->where('equipment_id', $equipment->id);
            }])
            ->get();

        $sum = 0;
        foreach ($bookings as $booking) {
            foreach ($booking->items as $item) {
                $sum += (int)$item->qty;
            }
        }

        return $sum;
    }

    /**
     * Проверяем доступность набора реквизита вида:
     * [equipment_id => qty]
     */
    public function equipmentIsEnough(Collection $equipmentList, array $requestedQtyById, Carbon $startAt, Carbon $endAt): array
    {
        // Возвращаем массив ошибок: [equipment_id => "сообщение"]
        $errors = [];

        foreach ($equipmentList as $equipment) {
            $id = (int)$equipment->id;
            $need = (int)($requestedQtyById[$id] ?? 0);

            if ($need <= 0) {
                continue;
            }

            $busy = $this->equipmentBusyQty($equipment, $startAt, $endAt);
            $total = (int)$equipment->total_qty;

            if ($busy + $need > $total) {
                $errors[$id] = "Недостаточно '{$equipment->name}' на выбранное время. Доступно: " . max(0, $total - $busy);
            }
        }

        return $errors;
    }
}
