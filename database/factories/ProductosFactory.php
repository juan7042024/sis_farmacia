<?php

namespace Database\Factories;

use App\Models\Categorias;
use App\Models\FormaFarmaceutica;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\productos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<productos>
 */
class ProductosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // asociamos el modelo con el factory
    protected $model = productos::class;

    public function definition(): array
    {
        return [
            // Categoría es obligatoria (required en tu migración)
            'categoria_id' => $this->getRandomId(Categorias::class),
            
            // Llaves foráneas opcionales (nullable en tu migración)
            'laboratorio_id' => $this->faker->boolean(85) ? $this->getRandomId(Laboratorio::class) : null,
            'forma_farmaceutica_id' => $this->faker->boolean(85) ? $this->getRandomId(FormaFarmaceutica::class) : null,
            'presentacion_id' => $this->faker->boolean(85) ? $this->getRandomId(Presentacion::class) : null,
            
            // Códigos
            'codigo_producto' => strtoupper($this->faker->unique()->bothify('PRD-#####')),
            'codigo_barra' => $this->faker->boolean(80) ? $this->faker->unique()->numerify('#############') : null,
            
            // Nombres
            'nombre_comercial' => strtoupper($this->faker->words($this->faker->numberBetween(1, 3), true)),
            'nombre_generico' => strtoupper($this->faker->words($this->faker->numberBetween(1, 3), true)),
            
            // Concentración (opcional 80% de probabilidad)
            'concentracion' => $this->faker->optional(0.8)->randomElement([
                '100 mg',
                '250 mg',
                '500 mg',
                '5 mg/ml',
                '10 mg/ml',
                '1 g',
                '2 g',
                '200 mcg',
                '1 %',
                '0.9 %',
            ]),
            
            // Unidad de medida (agregado según tu formulario create)
            'unidad_medida' => $this->faker->randomElement([
                'kg', 'g', 'mg', 'mcg', 'l', 'ml', 'mmol', 'mEq', 'UI', '%'
            ]),
            
            // Acción terapéutica (opcional 75% de probabilidad)
            'accion_terapeutica' => $this->faker->optional(0.75)->randomElement([
                'ANALGESICO',
                'ANTINFLAMATORIO',
                'ANTIBIOTICO',
                'ANTIGRIPAL',
                'ANTIALERGICO',
                'ANTIPIRETICO',
                'ANTIHISTAMINICO',
                'ANTISEPTICO',
                'EXPECTORANTE',
                'ANTIACIDO',
            ]),
            
            // Booleano (false por defecto en migración, pero aleatorio aquí)
            'usa_receta' => $this->faker->boolean(30), // 30% de probabilidad de ser true
            
            // Imagen nula por defecto
            'imagen' => null,
        ];
    }

    /**
     * Método auxiliar para obtener un ID aleatorio de una tabla relacionada.
     * (Si ya tienes este método en un BaseFactory o Trait, puedes omitirlo aquí).
     */
    private function getRandomId(string $modelClass)
    {
        // Si la tabla está vacía, crea un registro por defecto o retorna 1 para evitar errores
        $record = $modelClass::inRandomOrder()->first();
        
        if (!$record) {
            // Esto es un "salvavidas" en caso de que ejecutes el seeder sin haber sembrado las otras tablas
            return $modelClass::factory()->create()->id; 
        }
        
        return $record->id;
    }
}
