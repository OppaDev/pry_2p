<?php

require_once 'vendor/autoload.php';

// Inicializar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Verificar usuarios
$users = \App\Models\User::with('roles')->get();

echo "Usuarios creados:\n";
foreach ($users as $user) {
    $role = $user->getRoleNames()->first() ?? 'Sin rol';
    echo "- {$user->name} ({$user->email}) - Rol: {$role}\n";
}

echo "\nCredenciales de acceso:\n";
echo "Email: admin1@example.com\n";
echo "Password: password\n";
