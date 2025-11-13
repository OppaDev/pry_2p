<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venta>
 */
class VentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 500);
        $impuestos = $subtotal * 0.15;
        $total = $subtotal + $impuestos;

        return [
            'numero_secuencial' => 'VENTA-' . fake()->unique()->numberBetween(1000, 9999),
            'fecha' => fake()->dateTimeBetween('-1 month', 'now'),
            'cliente_id' => \App\Models\Cliente::factory(),
            'vendedor_id' => \App\Models\User::factory(),
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'total' => $total,
            'metodo_pago' => fake()->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'estado' => 'completada',
            'observaciones' => fake()->optional()->sentence(),
        ];
    }
}
