<?php

namespace Database\Seeders;

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
    public function run(): void
    {
        // User::factory(10)->create();
        // se crea este usuario por DEFECTO ejecutanto un "php artisan migrate:fresh --seed"
        $usuarioAdmin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678')
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'admin', 'password' => bcrypt('12345678')]
        );

        $superAdminRole = Role::query()->firstOrCreate([
            'name' => 'SUPER ADMIN',
            'guard_name' => 'web',
        ]);
        // vincular el super admin con el usuario por defecto que tiene control total del sistema
        $admin->syncRoles([$superAdminRole->name]);
    }
}
