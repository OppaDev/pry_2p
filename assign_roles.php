<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Obtener todos los usuarios ordenados por ID
$users = User::orderBy('id')->get();

echo "Total de usuarios: " . $users->count() . "\n\n";

// Asignar roles según la solicitud:
// - 5 primeros: admin
// - 5 siguientes: estudiante
// - 7 últimos: docente

foreach ($users as $index => $user) {
    // Remover todos los roles actuales
    $user->syncRoles([]);

    if ($index < 5) {
        // Primeros 5: admin
        $user->assignRole('admin');
        echo "Usuario " . ($index + 1) . ": {$user->name} ({$user->email}) -> ROL: ADMIN\n";
    } elseif ($index < 10) {
        // Siguientes 5: estudiante
        $user->assignRole('estudiante');
        echo "Usuario " . ($index + 1) . ": {$user->name} ({$user->email}) -> ROL: ESTUDIANTE\n";
    } else {
        // Últimos 7: docente
        $user->assignRole('docente');
        echo "Usuario " . ($index + 1) . ": {$user->name} ({$user->email}) -> ROL: DOCENTE\n";
    }
}

echo "\n¡Asignación de roles completada!\n";
echo "\nResumen:\n";
echo "- Administradores: " . User::role('admin')->count() . "\n";
echo "- Estudiantes: " . User::role('estudiante')->count() . "\n";
echo "- Docentes: " . User::role('docente')->count() . "\n";
