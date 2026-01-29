<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hall_id')->constrained()->cascadeOnDelete();

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            /*
            |--------------------------------------------------------------------------
            | Статусы (упрощённо)
            |--------------------------------------------------------------------------
            | awaiting_payment - создано, ждём оплату (держим слот reservation_minutes)
            | paid            - оплачено, бронь активна
            | canceled         - отменено до оплаты или админом
            | refunded         - деньги возвращены
            | failed           - оплата не прошла
            | expired          - резерв слота истёк (не оплатили вовремя)
            */
            $table->string('status')->default('awaiting_payment');

            // До какого времени держим резерв слота
            $table->dateTime('reserved_until')->nullable();

            // Когда оплатили
            $table->dateTime('paid_at')->nullable();

            // Снимки цены, чтобы потом не "переобуваться" если цены изменятся
            $table->unsignedInteger('price_hall')->default(0);
            $table->unsignedInteger('price_equipment')->default(0);
            $table->unsignedInteger('price_total')->default(0);

            // Множитель динамического ценообразования
            $table->decimal('pricing_multiplier', 5, 2)->default(1.00);

            // Возврат
            $table->unsignedInteger('refund_amount')->default(0);
            $table->dateTime('refunded_at')->nullable();

            $table->string('cancel_reason')->nullable();

            $table->timestamps();

            $table->index(['hall_id', 'start_at', 'end_at']);
            $table->index(['status', 'reserved_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
