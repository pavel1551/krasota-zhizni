<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'hall_id',
        'start_at',
        'end_at',
        'status',
        'reserved_until',
        'paid_at',
        'price_hall',
        'price_equipment',
        'price_total',
        'pricing_multiplier',
        'refund_amount',
        'refunded_at',
        'cancel_reason',
    ];

    protected $casts = [
        'start_at'       => 'datetime',
        'end_at'         => 'datetime',
        'reserved_until' => 'datetime',
        'paid_at'        => 'datetime',
        'refunded_at'    => 'datetime',
    ];

    // -------- Relations --------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function items(): HasMany
    {
        // booking_equipment — отдельная таблица
        return $this->hasMany(BookingEquipment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    // -------- Scopes / helpers --------

    /**
     * "Живые" брони, которые блокируют слот:
     * - paid
     * - awaiting_payment, но резерв ещё не истёк
     */
    public function scopeBlocking(Builder $q): Builder
    {
        return $q->where(function ($qq) {
            $qq->where('status', 'paid')
               ->orWhere(function ($qqq) {
                   $qqq->where('status', 'awaiting_payment')
                       ->whereNotNull('reserved_until')
                       ->where('reserved_until', '>', now());
               });
        });
    }

    /**
     * Просроченные резервы (не оплачен вовремя)
     */
    public function scopeExpiredHolds(Builder $q): Builder
    {
        return $q->where('status', 'awaiting_payment')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<=', now());
    }

    public static function expireOldReservations(): int
    {
        // Чтобы слоты не зависали: помечаем просроченные резервы как expired
        return static::expiredHolds()->update(['status' => 'expired']);
    }

    public function durationHours(): int
    {
        // Округляем вверх до целого часа для простоты (можно сделать по минутам)
        $minutes = $this->start_at->diffInMinutes($this->end_at);
        return (int) ceil($minutes / 60);
    }
}
