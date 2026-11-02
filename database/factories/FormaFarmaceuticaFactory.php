<?php

namespace Database\Factories;

use App\Models\FormaFarmaceutica;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormaFarmaceutica>
 */
class FormaFarmaceuticaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => mb_strtoupper(fake()->unique()->randomElement([
                'Tableta',
                'Cápsula',
                'Jarabe',
                'Pomada',
                'Crema',
                'Solución',
                'Suspensión',
                'Inhalador',
                'Gotas',
                'Spray',
                'Ungüento',
                'Polvo',
                'Tableta masticable',
                'Tableta sublingual',
                'Comprimido',
                'Inyección',
                'Parches',
                'Supositorio',
                'Gel',
                'Emulsión',
                'Espuma',
                'Nebulizador',
                'Pastilla',
                'Barrera',
                'Granulado',
                'Nasal',
                'Óvulos',
                'Loción',
                'Aerosol',
            ])),
        ];
    }
}
