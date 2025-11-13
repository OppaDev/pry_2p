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

        // Crear categorías de licores si no existen
        $categorias = [
            ['nombre' => 'Whisky', 'descripcion' => 'Whisky y whiskey de diferentes orígenes'],
            ['nombre' => 'Ron', 'descripcion' => 'Rones blancos, dorados y añejos'],
            ['nombre' => 'Vodka', 'descripcion' => 'Vodkas premium y saborizados'],
            ['nombre' => 'Tequila', 'descripcion' => 'Tequilas blancos, reposados y añejos'],
            ['nombre' => 'Vinos', 'descripcion' => 'Vinos tintos, blancos y rosados'],
            ['nombre' => 'Cervezas', 'descripcion' => 'Cervezas nacionales e importadas'],
            ['nombre' => 'Licores', 'descripcion' => 'Licores y cremas'],
            ['nombre' => 'Ginebra', 'descripcion' => 'Ginebras premium y artesanales'],
        ];
        
        foreach ($categorias as $cat) {
            \App\Models\Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        // Crear productos de licorería si no existen
        if (Producto::count() < 10) {
            $productos = [
                ['nombre' => 'Johnnie Walker Black Label', 'codigo' => 'WALL-RAUT-0001', 'marca' => 'Johnnie Walker', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 19, 'stock_minimo' => 5, 'precio' => 45.00, 'categoria_id' => 1, 'descripcion' => 'Whisky escocés premium'],
                ['nombre' => 'Bacardi Carta Blanca', 'codigo' => 'WEW-WEWE-002', 'marca' => 'Bacardi', 'presentacion' => 'Botella', 'capacidad' => '1L', 'volumen_ml' => 1000, 'stock_actual' => 2, 'stock_minimo' => 3, 'precio' => 18.50, 'categoria_id' => 2, 'descripcion' => 'Ron blanco clásico'],
                ['nombre' => 'Absolut Vodka', 'codigo' => 'TEC001', 'marca' => 'Absolut', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 30, 'stock_minimo' => 10, 'precio' => 22.00, 'categoria_id' => 3, 'descripcion' => 'Vodka sueco premium'],
                ['nombre' => 'Jose Cuervo Especial', 'codigo' => 'MON001', 'marca' => 'Jose Cuervo', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 20, 'stock_minimo' => 8, 'precio' => 28.00, 'categoria_id' => 4, 'descripcion' => 'Tequila reposado mexicano'],
                ['nombre' => 'Casillero del Diablo', 'codigo' => 'IMP001', 'marca' => 'Concha y Toro', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 8, 'stock_minimo' => 5, 'precio' => 12.50, 'categoria_id' => 5, 'descripcion' => 'Vino tinto reserva chileno'],
                ['nombre' => 'Corona Extra', 'codigo' => 'HDD001', 'marca' => 'Corona', 'presentacion' => 'Six Pack', 'capacidad' => '355ml x 6', 'volumen_ml' => 2130, 'stock_actual' => 25, 'stock_minimo' => 10, 'precio' => 8.99, 'categoria_id' => 6, 'descripcion' => 'Cerveza mexicana clara'],
                ['nombre' => 'Baileys Irish Cream', 'codigo' => 'USB001', 'marca' => 'Baileys', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 15, 'stock_minimo' => 5, 'precio' => 24.99, 'categoria_id' => 7, 'descripcion' => 'Crema de whisky irlandés'],
                ['nombre' => 'Bombay Sapphire', 'codigo' => 'WEB001', 'marca' => 'Bombay', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 12, 'stock_minimo' => 6, 'precio' => 32.00, 'categoria_id' => 8, 'descripcion' => 'Ginebra London Dry premium'],
                ['nombre' => 'Jack Daniels Old No.7', 'codigo' => 'ALT001', 'marca' => 'Jack Daniels', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 35, 'stock_minimo' => 10, 'precio' => 38.50, 'categoria_id' => 1, 'descripcion' => 'Tennessee Whiskey'],
                ['nombre' => 'Havana Club 7 Años', 'codigo' => 'CAB001', 'marca' => 'Havana Club', 'presentacion' => 'Botella', 'capacidad' => '750ml', 'volumen_ml' => 750, 'stock_actual' => 18, 'stock_minimo' => 8, 'precio' => 26.50, 'categoria_id' => 2, 'descripcion' => 'Ron cubano añejo'],
            ];

            foreach ($productos as $producto) {
                Producto::create($producto);
            }
        }
    }
}
