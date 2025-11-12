<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Cervezas',
                'descripcion' => 'Bebidas fermentadas de malta de cebada',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Vinos',
                'descripcion' => 'Vinos tintos, blancos, rosados y espumosos',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Whisky',
                'descripcion' => 'Whisky escocés, irlandés, americano y otros',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Ron',
                'descripcion' => 'Ron blanco, dorado, añejo y especiado',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Vodka',
                'descripcion' => 'Vodka premium y estándar',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Tequila',
                'descripcion' => 'Tequila blanco, reposado y añejo',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Aguardientes',
                'descripcion' => 'Aguardientes nacionales y artesanales',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Licores',
                'descripcion' => 'Licores dulces y cremas',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Gin',
                'descripcion' => 'Ginebra clásica y premium',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Brandy y Cognac',
                'descripcion' => 'Brandies y cognacs premium',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Bebidas sin alcohol',
                'descripcion' => 'Refrescos, aguas y mixers',
                'estado' => 'activo',
            ],
        ];
        
        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
        
        $this->command->info('✅ Categorías creadas exitosamente: ' . count($categorias) . ' categorías');
    }
}
