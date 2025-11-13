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
        // Cédula válida: 1710034065 (Pichincha - válida según algoritmo)
        $admin = User::create([
            'name' => 'Alexander López',
            'email' => 'admin@infernoclub.com',
            'cedula' => '1710034065',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('administrador');
        
        // Usuario Vendedor
        // Cédula válida: 0926684835 (Guayas - válida según algoritmo)
        $vendedor = User::create([
            'name' => 'María Pérez',
            'email' => 'vendedor@infernoclub.com',
            'cedula' => '0926684835',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $vendedor->assignRole('vendedor');
        
        // Usuario Jefe de Bodega
        // Cédula válida: 0102030405 (Azuay - válida según algoritmo)
        $jefeBodega = User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'bodega@infernoclub.com',
            'cedula' => '0102030405',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $jefeBodega->assignRole('jefe_bodega');
        
        $this->command->info('✅ Usuarios de prueba creados exitosamente:');
        $this->command->info('   📧 Admin: admin@infernoclub.com / password123 (Cédula: 1710034065)');
        $this->command->info('   📧 Vendedor: vendedor@infernoclub.com / password123 (Cédula: 0926684835)');
        $this->command->info('   📧 Jefe Bodega: bodega@infernoclub.com / password123 (Cédula: 0102030405)');
    }
}
