<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('halls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();

            // Название зала
            $table->string('name');

            $table->text('description')->nullable();

            // Базовая цена за час (в копейках/центах лучше, но для диплома можно "в рублях")
            // Я делаю integer (рубли) ради простоты и наглядности.
            $table->unsignedInteger('base_price_per_hour');

            $table->unsignedInteger('capacity')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('halls');
    }
};
