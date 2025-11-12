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
        Schema::table('productos', function (Blueprint $table) {
            // Renombrar cantidad a stock_actual
            $table->renameColumn('cantidad', 'stock_actual');
            
            // Agregar nuevos campos
            $table->string('marca')->nullable()->after('nombre');
            $table->string('presentacion')->nullable()->after('marca'); // botella, lata, caja
            $table->string('capacidad')->nullable()->after('presentacion'); // "750ml", "1L"
            $table->integer('volumen_ml')->nullable()->after('capacidad'); // Para cálculos
            $table->integer('stock_minimo')->default(5)->after('stock_actual');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('stock_minimo');
            $table->text('descripcion')->nullable()->after('estado');
            $table->unsignedBigInteger('categoria_id')->nullable()->after('descripcion');
            
            // Foreign key
            $table->foreign('categoria_id')->references('id')->on('categorias')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn([
                'marca', 'presentacion', 'capacidad', 'volumen_ml',
                'stock_minimo', 'estado', 'descripcion', 'categoria_id'
            ]);
            $table->renameColumn('stock_actual', 'cantidad');
        });
    }
};
