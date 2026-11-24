<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('ticket_type_id')->constrained();
            $table->uuid('code')->unique(); // conteúdo do QR Code
            $table->string('holder_name', 120)->nullable();
            $table->enum('status', ['valid', 'used', 'cancelled'])->default('valid');
            $table->dateTime('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};