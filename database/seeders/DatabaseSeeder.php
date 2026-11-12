<?php

namespace Database\Seeders;

use App\Models\FormaFarmaceutica;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // para aplicar cambios en los seeders ejecutar asi "php artisan migrate:fresh --seed"
    public function run(): void
    {
        // crear una sucursal por defecto
        Sucursal::firstOrCreate(
            [
                'nombre' => 'CASA MATRIZ',
            ],
            [
                'direccion' => 'AV principal 123 centro',
                'telefono' => '12345678',
                'estado' => '1',
            ]
        );
        
        $this->call([
            // llamar los seeders que vamos a ejecutar
            RoleSeeder::class,
            AjusteSeeder::class,
            SucursalSeeder::class,
            CategoriasSeeder::class,
            EmpleadosSeeder::class,
            ProveedorSeeder::class,
            LoteSeeder::class,
            ClienteSeeder::class,
        ]);

        // generacion de los registros de "LaboratorioFactory" y de "FormaFarmaceutica"
        Laboratorio::factory()->count(20)->create();
        FormaFarmaceutica::factory()->count(20)->create();
        Presentacion::factory()->count(20)->create();

        // llamar al seeder despues de los modelos de arriba similar al orden que deben ir las migraciones donde de ultimo las llaves foraneas son las que se llenas de los otros modelos que generan 
        // los registros
        $this->call([
            // llamar los seeders que vamos a ejecutar
            ProductosSeeder::class,
        ]);
        // User::factory(10)->create();
        // se crea este usuario por DEFECTO ejecutanto un "php artisan migrate:fresh --seed" que es el SUPER ADMINISTRADOR DE TODO EL SISTEMA
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'admin', 'password' => bcrypt('12345678')]
        );

        // crear rol
        $superAdminRole = Role::query()->firstOrCreate([
            'name' => 'SUPER ADMIN',
            'guard_name' => 'web',
        ]);
        // vincular el super admin con el usuario por defecto que tiene control total del sistema
        $admin->syncRoles([$superAdminRole->name]);

        
    }
}
