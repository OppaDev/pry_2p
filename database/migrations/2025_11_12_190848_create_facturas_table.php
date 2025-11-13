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
            
            // Relaciones
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict')->comment('Usuario que genera la factura');
            
            // Numeración factura
            $table->string('numero_secuencial', 15)->unique()->comment('001-001-000000001');
            $table->string('clave_acceso', 49)->unique()->comment('Clave de acceso 48 dígitos SRI');
            
            // Datos establecimiento (SRI)
            $table->string('establecimiento', 3)->default('001');
            $table->string('punto_emision', 3)->default('001');
            
            // Fechas
            $table->date('fecha_emision');
            $table->timestamp('fecha_autorizacion')->nullable();
            
            // Estados
            $table->enum('estado', ['PENDIENTE', 'AUTORIZADO', 'ANULADO', 'ERROR'])->default('PENDIENTE');
            $table->enum('modo', ['PRUEBA', 'PRODUCCION'])->default('PRUEBA');
            
            // Datos cliente en factura (snapshot)
            $table->string('cliente_identificacion', 20);
            $table->string('cliente_razon_social');
            $table->string('cliente_direccion')->nullable();
            $table->string('cliente_telefono', 15)->nullable();
            $table->string('cliente_email')->nullable();
            
            // Totales
            $table->decimal('subtotal_sin_impuestos', 10, 2);
            $table->decimal('subtotal_0', 10, 2)->default(0)->comment('Base imponible tarifa 0%');
            $table->decimal('subtotal_12', 10, 2)->default(0)->comment('Base imponible tarifa 12%');
            $table->decimal('iva', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Archivos generados
            $table->text('xml_path')->nullable()->comment('Ruta del XML generado');
            $table->text('pdf_path')->nullable()->comment('Ruta del PDF RIDE');
            
            // Respuesta SRI
            $table->text('autorizacion_xml')->nullable()->comment('XML de autorización del SRI');
            $table->text('mensajes_sri')->nullable()->comment('Mensajes/errores del SRI en JSON');
            
            // Notas
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('numero_secuencial');
            $table->index('clave_acceso');
            $table->index('fecha_emision');
            $table->index('estado');
            $table->index(['establecimiento', 'punto_emision']);
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
