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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->enum('tipo', ['ingreso', 'salida', 'ajuste']);
            $table->dateTime('fecha');
            $table->integer('cantidad'); // Positivo para ingreso, negativo para salida
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->text('descripcion')->nullable();
            $table->foreignId('responsable_id')->constrained('users')->restrictOnDelete();
            $table->string('referencia_tipo')->nullable(); // 'venta', 'ajuste', 'ingreso'
            $table->unsignedBigInteger('referencia_id')->nullable(); // ID de la venta o ajuste
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('tipo');
            $table->index('fecha');
            $table->index('responsable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
