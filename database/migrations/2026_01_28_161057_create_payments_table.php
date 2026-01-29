<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();

            // pending / success / failed / timeout
            $table->string('status')->default('pending');

            $table->unsignedInteger('amount')->default(0);

            // Для демонстрации: не храним карту, только last4
            $table->string('card_last4')->nullable();

            // Для демонстрации "шифрование": имя держателя шифруем (в реальности вообще не храним)
            $table->text('card_holder_encrypted')->nullable();

            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
