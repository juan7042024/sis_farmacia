<?php

namespace Database\Factories;

use App\Models\Empleados;
use App\Models\Model;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
// asi se creo "php artisan make:factory EmpleadoFactory"

class EmpleadosFactory extends Factory
{

    // asociar el modelo con el factory
    protected $model = Empleados::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // dar formato a los registros del empleado
        $nombre = fake()->firstName();
        $apellidos = fake()->lastName();

        return [
            'usuario_id' => User::factory(),
            'sucursal_id' => function(){return Sucursal::inRandomOrder()->value('id') ?? Sucursal::factory()->create()->id; },
            'nombres' => $nombre,
            'apellidos' => $apellidos,
            'tipo_doc' => fake()->randomElement(['CI', 'DNI', 'RUC', 'PASAPORTE', 'OTRO']),
            'numero_doc' => fake()->unique()->numerify('########'),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->address(),
            'profesion' => fake()->jobTitle(),
            'fecha_nacimiento' => fake()->date('Y-m-d', '-18 years'),
            'genero' => fake()->randomElement(['M', 'F']),
            'avatar' => null,
            'estado' => fake()->boolean(80) ? 'activo' : 'inactivo',
        ];
    }
}
