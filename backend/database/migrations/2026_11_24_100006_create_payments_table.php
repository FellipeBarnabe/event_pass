<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('gateway', 30);               // mercadopago, asaas...
            $table->string('gateway_id', 100)->unique(); // evita webhook duplicado
            $table->enum('method', ['pix', 'card']);
            $table->enum('status', ['pending', 'approved', 'refused', 'refunded'])->default('pending');
            $table->unsignedInteger('amount_cents');
            $table->json('raw_payload')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};