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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->unique()->constrained('ventas')->restrictOnDelete();
            $table->string('numero_secuencial', 50); // Mismo que venta
            $table->string('numero_autorizacion', 100)->nullable();
            $table->string('clave_acceso_sri', 100)->nullable()->unique();
            $table->dateTime('fecha_emision');
            $table->dateTime('fecha_autorizacion')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('impuestos', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('estado_autorizacion', ['pendiente', 'autorizada', 'rechazada', 'anulada'])->default('pendiente');
            $table->text('xml_factura')->nullable();
            $table->json('respuesta_sri')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('numero_secuencial');
            $table->index('clave_acceso_sri');
            $table->index('estado_autorizacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
