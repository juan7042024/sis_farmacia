<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// asi se creo "php artisan make:seeder AjusteSeeder"
class AjusteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //creacion de ajuste por defecto al sistema
        Ajuste::firstOrCreate(
            ['email'=> 'contacto@farmacia.com'],
            [
                'nombre' => 'famacia demo',
                'descripcion' => 'Configuracion inicial del sistema',
                'direccion' => 'Av principal 123 - centro',
                'telefono' => '987654321',
                'divisa' => 'MXN',
                'logo' => null,
                'web' => 'http://sis_farmacia.test',
            ]
        );
    }
}
