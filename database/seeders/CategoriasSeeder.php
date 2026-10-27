<?php

namespace Database\Seeders;

use App\Models\Categorias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// asi se creo "php artisan make:seeder CategoriasSeeder"
class CategoriasSeeder extends Seeder
{
    use WithoutModelEvents; // uso del evento para que no marque en desuso 
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //generamos 10 categorias en base al formato de registros del factory
        Categorias::factory()->count(10)->create();
    }
}
