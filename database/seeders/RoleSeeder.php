<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { // Limpia la caché de roles y permisos, es una buena práctica.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crea los roles requeridos por la evaluación
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'docente']);
        Role::create(['name' => 'estudiante']);

        // 5 usuarios de ejemplo para cada rol
        $roles = ['admin', 'docente', 'estudiante'];

        foreach ($roles as $role) {
            for ($i = 1; $i <= 5; $i++) {
                $user = \App\Models\User::create([
                    'name' => ucfirst($role) . " $i",
                    'email' => "{$role}{$i}@example.com",
                    'password' => bcrypt('password'),
                ]);
                $user->assignRole($role);
            }
        }
    }
}
