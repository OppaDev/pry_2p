<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');
        $driver = DB::connection($connection)->getDriverName();

        // Para PostgreSQL, necesitamos usar SQL raw para especificar USING
        if ($driver === 'pgsql') {
            DB::connection($connection)->statement("ALTER TABLE {$table} ALTER COLUMN tags TYPE json USING tags::json");
        } elseif ($driver === 'sqlite') {
            // SQLite ya maneja tags como text, no necesita conversión
            // Los datos JSON se pueden almacenar en columnas text en SQLite
        } else {
            // Para MySQL y otros
            Schema::connection($connection)->table($table, function (Blueprint $table) {
                $table->json('tags')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Revertir a string
            $table->string('tags')->nullable()->change();
        });
    }
};
