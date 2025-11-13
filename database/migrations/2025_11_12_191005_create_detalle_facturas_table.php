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
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            
            // Datos del producto (snapshot al momento de la factura)
            $table->string('codigo_principal', 25);
            $table->string('codigo_auxiliar', 25)->nullable();
            $table->string('descripcion');
            
            // Cantidades y precios
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_total_sin_impuesto', 10, 2);
            
            // Impuestos
            $table->decimal('base_imponible', 10, 2);
            $table->string('codigo_porcentaje_iva', 4)->comment('0, 2 (12%), 3 (14%), etc');
            $table->decimal('tarifa_iva', 5, 2)->comment('0.00, 12.00, 14.00, etc');
            $table->decimal('valor_iva', 10, 2);
            
            $table->timestamps();
            
            // Índices
            $table->index('factura_id');
            $table->index('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_facturas');
    }
};
