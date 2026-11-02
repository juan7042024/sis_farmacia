<?php

namespace Database\Factories;

use App\Models\Presentacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Presentacion>
 */
// asi se creo "php artisan make:factory PresentacionFactory"
class PresentacionFactory extends Factory
{

// asociar el factory al modelo
    protected $model = Presentacion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => mb_strtoupper(fake()->unique()->randomElement([
                'BOLSA',
                'BOTELLA',
                'LATA',
                'BOTE',
                'TARRO',
                'ENVASE',
                'CUBETA',
                'TAMBOR',
                'BARRIL',
                'SACO',
                'COSTAL',
                'ROLLO',
                'CHAROLA',
                'BANDEJA',
                'ESTUCHE',
                'CONTENEDOR',
                'AEROSOL',
                'CAPSULA',
                'TABLETA',
                'COMPRIMIDO',
                'OVULO',
                'CREMA',
                'GEL',
                'UNGÜENTO',
                'POMADA',
                'LOCION',
                'JARABE',
                'ELIXIR',
                'SOLUCION',
                'SUSPENSION',
                'EMULSION',
                'GOTAS',
                'POLVO',
                'GRANULADO',
                'INHALADOR',
                'NEBULIZADOR',
                'IMPLANTE',
                'APLICADOR',
                'DISPENSADOR',
                'PAR',
                'PIEZA',
                'UNIDAD',
                'DOCENA',
                'MEDIA DOCENA',
                'JUEGO',
                'KIT',
                'SET',
                'DISPLAY',
                'BLISTER CON 10',
                'BLISTER CON 20',
                'CAJA CON 10',
                'CAJA CON 20',
                'CAJA CON 30',
                'CAJA CON 50',
                'CAJA CON 100',
                'PAQUETE CON 2',
                'PAQUETE CON 5',
                'PAQUETE CON 10',
                'PAQUETE CON 12',
                'PAQUETE CON 24',
                'FRACCION',
                'PORCION',
                'DOSIS',
                'TIRA',
                'HOJA',
                'LÁMINA',
                'BARRA',
                'BLOQUE',
                'PLACA',
                'ROLLO DE CINTA'
            ]))
        ];
    }
}
