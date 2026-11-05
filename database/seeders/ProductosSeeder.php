<?php

namespace Database\Seeders;

use App\Models\productos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Como tu modelo se llama 'productos', llamas al factory así:
        productos::factory()->count(100)->create();
    }
}
