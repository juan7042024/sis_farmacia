<?php

namespace Database\Factories;

use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sucursal>
 */
// asi creamos "php artisan make:factory SucursalFactory"
class SucursalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
    
    */
    // asociar al modelo
    protected $model = Sucursal::class;

    public function definition(): array
    {
        // crear dar estructura de como se van a crear los registros
        return [
            'nombre' => fake()->unique()->company() . ' ' . fake()->randomElement(['Sucursal', 'Fillial', 'Oficina']),
            'direccion' => fake()->address(),
            'telefono' => fake()->phoneNumber(),
            'estado' => fake()->boolean(80),
        ];
    }
}
