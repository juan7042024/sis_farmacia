<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proveedor>
 */
// asi se creo ""php artisan make:model Proveedor -mcrf" donde creamos la migracion, controlador, recursos de los metodos del controlador y factory"
class ProveedorFactory extends Factory
{

    protected $model = Proveedor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'nombre' => fake()->name(),
            'telefono' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'direccion' => fake()->address(),
            'notas' => fake()->optional()->sentence(),
        ];
    }
}
