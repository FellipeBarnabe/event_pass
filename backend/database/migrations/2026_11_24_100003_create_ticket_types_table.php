<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80); // Inteira, Meia, VIP, 1º lote...
            $table->unsignedInteger('price_cents');
            $table->unsignedInteger('quantity_total');
            $table->unsignedInteger('quantity_reserved')->default(0);
            $table->unsignedInteger('quantity_sold')->default(0);
            $table->unsignedTinyInteger('max_per_order')->default(6);
            $table->dateTime('sales_start_at')->nullable();
            $table->dateTime('sales_end_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // O Laravel não tem método para CHECK, então vai em SQL puro.
        // Garante no banco que nunca se reserva/vende mais do que existe.
        DB::statement(
            'ALTER TABLE ticket_types ADD CONSTRAINT chk_stock
             CHECK (quantity_reserved + quantity_sold <= quantity_total)'
        );

    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};