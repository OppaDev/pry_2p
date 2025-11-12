<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden
        $this->call([
            RolesAndPermissionsSeeder::class,
            CategoriasSeeder::class,
            AdminUserSeeder::class,
        ]);
        
        $this->command->info('');
        $this->command->info('🎉 Base de datos inicializada correctamente!');
        $this->command->info('');
    }
}
