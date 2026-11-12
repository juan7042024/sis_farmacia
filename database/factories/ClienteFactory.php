<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    // asociar el factory con el modelo
     protected $model = Cliente::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera un número único de 8 dígitos para el CI/NIT
            'ci_nit' => $this->faker->unique()->numerify('########'),
            'nombres_apellidos' => $this->faker->name(),
            // Genera un email único y seguro
            'email' => $this->faker->unique()->safeEmail(),
            // Genera un número de teléfono (a veces con formato, a veces nulo)
            'telefono' => $this->faker->optional(0.8)->numerify('7#######'),
        ];
    }
}
