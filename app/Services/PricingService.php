<?php

namespace App\Services;

use App\Models\Hall;
use Carbon\Carbon;

class PricingService
{
    /**
     * Рассчитать множитель динамического ценообразования
     * по тому, сколько часов осталось до начала.
     */
    public function multiplierFor(Carbon $now, Carbon $startAt): float
    {
        $hoursLeft = max(0, $now->diffInHours($startAt, false)); // если startAt в прошлом, будет отрицательно
        if ($hoursLeft < 0) {
            $hoursLeft = 0;
        }

        $tiers = config('booking.pricing_tiers', []);

        // Идём сверху вниз: 72, 24, 6, 0...
        foreach ($tiers as $tier) {
            if ($hoursLeft >= (int)$tier['min_hours']) {
                return (float)$tier['multiplier'];
            }
        }

        return 1.00;
    }

    /**
     * Посчитать цену зала (в рублях) по базовой цене, длительности и множителю.
     */
    public function hallPrice(Hall $hall, int $hours, float $multiplier): int
    {
        $base = (int)$hall->base_price_per_hour;
        $raw = $base * $hours * $multiplier;

        // Округлим до рубля
        return (int) round($raw);
    }

    /**
     * Цена реквизита: price_per_hour * hours * qty
     * (без множителя — проще и логичнее)
     */
    public function equipmentPrice(int $pricePerHour, int $hours, int $qty): int
    {
        return $pricePerHour * $hours * $qty;
    }
}
