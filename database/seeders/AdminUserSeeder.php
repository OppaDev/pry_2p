<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        $admin = User::create([
            'name' => 'Alexander López',
            'email' => 'admin@infernoclub.com',
            'cedula' => '1234567890',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('administrador');
        
        // Usuario Vendedor
        $vendedor = User::create([
            'name' => 'María Pérez',
            'email' => 'vendedor@infernoclub.com',
            'cedula' => '0987654321',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $vendedor->assignRole('vendedor');
        
        // Usuario Jefe de Bodega
        $jefeBodega = User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'bodega@infernoclub.com',
            'cedula' => '1122334455',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $jefeBodega->assignRole('jefe_bodega');
        
        $this->command->info('✅ Usuarios de prueba creados exitosamente:');
        $this->command->info('   📧 Admin: admin@infernoclub.com / password123');
        $this->command->info('   📧 Vendedor: vendedor@infernoclub.com / password123');
        $this->command->info('   📧 Jefe Bodega: bodega@infernoclub.com / password123');
    }
}
