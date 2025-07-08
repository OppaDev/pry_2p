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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('asignatura_id')->constrained();
            $table->decimal('nota_1', 3, 2);
            $table->decimal('nota_2', 3, 2);
            $table->decimal('nota_3', 3, 2);
            $table->decimal('promedio', 3, 2);
            $table->string('estado_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
