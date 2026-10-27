<?php

namespace Database\Seeders;

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
            EmpleadosSeeder::class
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
