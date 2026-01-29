<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class RefundPolicyService
{
    public function canRefund(Booking $booking, ?Carbon $now = null): bool
    {
        $now = $now ?: now();

        // Возвращать можно только оплаченные и ещё не возвращённые
        if ($booking->status !== 'paid' || !$booking->paid_at) {
            return false;
        }

        $hoursAfterPay = (int) config('booking.refund_hours_after_payment', 4);
        $minHoursBeforeStart = (int) config('booking.refund_min_hours_before_start', 2);

        // 1) не прошло N часов после оплаты
        $deadlineByPay = $booking->paid_at->copy()->addHours($hoursAfterPay);

        // 2) не позже чем за N часов до начала
        $deadlineByStart = $booking->start_at->copy()->subHours($minHoursBeforeStart);

        return $now->lte($deadlineByPay) && $now->lte($deadlineByStart);
    }

    public function policySnapshot(): string
    {
        $hoursAfterPay = (int) config('booking.refund_hours_after_payment', 4);
        $minHoursBeforeStart = (int) config('booking.refund_min_hours_before_start', 2);

        return "Refund allowed if: now <= paid_at + {$hoursAfterPay}h AND now <= start_at - {$minHoursBeforeStart}h";
    }
}
