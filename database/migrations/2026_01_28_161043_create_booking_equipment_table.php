<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_equipment', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();

            // Сколько единиц взяли
            $table->unsignedInteger('qty')->default(1);

            // Цена за час на момент бронирования (снимок)
            $table->unsignedInteger('price_per_hour')->default(0);

            $table->timestamps();

            $table->unique(['booking_id', 'equipment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_equipment');
    }
};
