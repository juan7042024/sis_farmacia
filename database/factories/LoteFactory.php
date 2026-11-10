<?php

namespace Database\Factories;

use App\Models\Lote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lote>
 */
class LoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // asociar al modelo lotes
    protected $model = Lote::class;
    
    public function definition(): array
    {
        
        return [
            //generar los lotes
            'nombre' => 'LOTE-' . fake()->unique()->bothify('####??'),
        ];
    }
}
