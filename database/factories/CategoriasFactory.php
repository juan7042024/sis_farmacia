<?php

namespace Database\Factories;

use App\Models\Categorias;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categorias>
 */
// asi se creo "php artisan make:factory CategoriasFactory"
class CategoriasFactory extends Factory
{

    // asociamos el modelo con la categoria
    protected $model = Categorias::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // darle formato a los valores que nos debe generar
        return [
            'nombre' => fake()->unique()->randomElement([
                'Medicamentos',
                'Cuidado Personal',
                'Higiene',
                'Vitaminas',
                'Bebes',
                'Salud Sexual',
                'Hogar',
                'Belleza',
                'Alimentos',
                'Bebidas',
                'Suplementos',
                'Dermocosmetica',
                'Aparatos Medicos',
                'Nutricion',
                'Veterinaria'

            ]),
        ];
    }
}
