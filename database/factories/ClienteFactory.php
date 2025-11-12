<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'tipo_identificacion' => $this->faker->randomElement(['cedula', 'pasaporte', 'ruc']),
            'identificacion' => $this->faker->unique()->numerify('##########'),
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->numerify('09########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'estado' => 'activo',
        ];
    }

    /**
     * Cliente menor de edad
     */
    public function menorDeEdad(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_nacimiento' => now()->subYears(rand(10, 17)),
        ]);
    }

    /**
     * Cliente inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }
}
