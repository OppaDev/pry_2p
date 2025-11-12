<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_secuencial', 50)->unique(); // FACT-2025-11-00001
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('vendedor_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('fecha');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('impuestos', 10, 2); // IVA 15%
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['completada', 'anulada'])->default('completada');
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('edad_verificada')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para consultas rápidas
            $table->index('numero_secuencial');
            $table->index('fecha');
            $table->index('vendedor_id');
            $table->index('cliente_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
