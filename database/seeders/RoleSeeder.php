<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
//asi se creo "php artisan make:seeder RoleSeeder"
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //creacion de roles por defecto
        Role::create(['name' => 'SUPER ADMIN', 'guard_name' => 'web']);
        Role::create(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        Role::create(['name' => 'CONTADOR', 'guard_name' => 'web']);
        Role::create(['name' => 'VENDEDOR', 'guard_name' => 'web']);
        Role::create(['name' => 'EMPLEADO', 'guard_name' => 'web']);
    }
}
