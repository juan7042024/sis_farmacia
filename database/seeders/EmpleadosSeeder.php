<?php

namespace Database\Seeders;

use App\Models\Empleados;
use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

// asi se creo php "artisan make:seeder EmpleadoSeeder"
class EmpleadosSeeder extends Seeder
{
    // para el seeder
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // creamos el rol Empleado por defecto
        $empleadoRole = Role::query()->firstOrCreate([
            'name' => 'EMPLEADO',
            'guard_name' => 'web',
        ]);
        // sacar todos los id de la sucursal
        $sucursalIds = Sucursal::query()->pluck('id')->all();

        // si no hay id de sucursal, se crean con el id
        if (empty($sucursalIds)) {
            Sucursal::factory()->count(5)->create();
            $sucursalIds = Sucursal::query()->pluck('id')->all();
        }
        // crear los empleados de prueba
        Empleados::factory()
            ->count(10)
            ->create()
            ->each(function (Empleados $empleado) use ($empleadoRole) {
                if ($empleado->usuario) {
                    $empleado->usuario->syncRoles([$empleadoRole->name]);
                }
            });
    }
}
