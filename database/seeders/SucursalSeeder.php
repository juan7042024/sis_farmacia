<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// asi se creo "php artisan make:seeder SucursalSeeder"
class SucursalSeeder extends Seeder
{
    // uso del eventos para el seeder
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //llamar al factory para crear 5 registros en base a esa estructura del registro
        Sucursal::factory()->count(5)->create();
        //
    }
}
