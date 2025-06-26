<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Support\Facades\Hash;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de prueba si no existen
        if (User::count() < 5) {
            User::create([
                'name' => 'Admin Usuario',
                'email' => 'admin@ejemplo.com',
                'password' => Hash::make('password123'),
            ]);

            User::create([
                'name' => 'María González',
                'email' => 'maria@ejemplo.com',
                'password' => Hash::make('password123'),
            ]);

            User::create([
                'name' => 'Juan Pérez',
                'email' => 'juan@ejemplo.com',
                'password' => Hash::make('password123'),
            ]);

            User::create([
                'name' => 'Ana López',
                'email' => 'ana@ejemplo.com',
                'password' => Hash::make('password123'),
            ]);

            User::create([
                'name' => 'Carlos Martínez',
                'email' => 'carlos@ejemplo.com',
                'password' => Hash::make('password123'),
            ]);
        }

        // Crear productos de prueba si no existen
        if (Producto::count() < 10) {
            $productos = [
                ['nombre' => 'Laptop Dell Inspiron', 'codigo' => 'LAP001', 'cantidad' => 15, 'precio' => 899.99],
                ['nombre' => 'Mouse Inalámbrico', 'codigo' => 'MOU001', 'cantidad' => 50, 'precio' => 25.99],
                ['nombre' => 'Teclado Mecánico', 'codigo' => 'TEC001', 'cantidad' => 30, 'precio' => 79.99],
                ['nombre' => 'Monitor 24 pulgadas', 'codigo' => 'MON001', 'cantidad' => 20, 'precio' => 199.99],
                ['nombre' => 'Impresora HP LaserJet', 'codigo' => 'IMP001', 'cantidad' => 8, 'precio' => 249.99],
                ['nombre' => 'Disco Duro Externo 1TB', 'codigo' => 'HDD001', 'cantidad' => 25, 'precio' => 69.99],
                ['nombre' => 'Memoria USB 32GB', 'codigo' => 'USB001', 'cantidad' => 100, 'precio' => 12.99],
                ['nombre' => 'Webcam HD', 'codigo' => 'WEB001', 'cantidad' => 40, 'precio' => 49.99],
                ['nombre' => 'Altavoces Bluetooth', 'codigo' => 'ALT001', 'cantidad' => 35, 'precio' => 39.99],
                ['nombre' => 'Cable HDMI 2m', 'codigo' => 'CAB001', 'cantidad' => 75, 'precio' => 15.99],
            ];

            foreach ($productos as $producto) {
                Producto::create($producto);
            }
        }
    }
}
