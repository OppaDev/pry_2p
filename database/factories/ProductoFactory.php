<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'codigo' => strtoupper($this->faker->unique()->bothify('PROD-###??')),
            'nombre' => $this->faker->words(3, true),
            'marca' => $this->faker->company(),
            'presentacion' => $this->faker->randomElement(['botella', 'lata', 'caja', 'barril']),
            'capacidad' => $this->faker->randomElement(['750ml', '1L', '355ml', '500ml']),
            'volumen_ml' => $this->faker->randomElement([355, 500, 750, 1000]),
            'precio' => $this->faker->randomFloat(2, 10, 200),
            'stock_actual' => $this->faker->numberBetween(50, 200),
            'stock_minimo' => $this->faker->numberBetween(10, 30),
            'estado' => 'activo',
            'descripcion' => $this->faker->optional()->sentence(),
            'categoria_id' => Categoria::factory(),
        ];
    }

    /**
     * Producto sin stock
     */
    public function sinStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_actual' => 0,
        ]);
    }

    /**
     * Producto con bajo stock
     */
    public function bajoStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_actual' => rand(1, 5),
            'stock_minimo' => 10,
        ]);
    }

    /**
     * Producto inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }
}
