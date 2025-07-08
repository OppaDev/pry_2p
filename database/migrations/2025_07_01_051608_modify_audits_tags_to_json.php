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
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');

        // Usamos el Schema Builder de Laravel, que es compatible con múltiples bases de datos.
        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Laravel generará el SQL correcto para MySQL ("MODIFY COLUMN ... json")
            // o para PostgreSQL ("ALTER COLUMN ... TYPE json USING ...").
            $table->json('tags')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Tu método down ya estaba correcto.
            $table->string('tags')->nullable()->change();
        });
    }
};
