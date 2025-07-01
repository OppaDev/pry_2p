# Guía de Prueba - Sistema de Motivos en Auditoría

## Pasos para probar el sistema

### 1. Acceder a la página de debug (temporal)
Visita: `http://tu-dominio/debug-audit`

Esta página te mostrará los últimos 5 registros de auditoría con todos sus datos en formato JSON para verificar cómo se están almacenando.

### 2. Probar eliminación con motivo

1. Ve a la lista de usuarios: `/users`
2. Haz clic en "Eliminar" en cualquier usuario (excepto el tuyo)
3. Se abrirá un modal con un campo de texto para el motivo
4. Ingresa un motivo como: "Usuario inactivo por más de 6 meses"
5. Haz clic en "Eliminar"
6. Ve al historial de auditoría del usuario eliminado
7. Deberías ver el motivo en la sección de auditoría

### 3. Probar restauración con motivo

1. Ve a la papelera de usuarios: `/usuarios-eliminados`
2. Haz clic en "Restaurar" en un usuario eliminado
3. Se abrirá un modal con un campo de texto para el motivo
4. Ingresa un motivo como: "Reactivación solicitada por el departamento"
5. Haz clic en "Restaurar"
6. Ve al historial de auditoría del usuario restaurado
7. Deberías ver ambos motivos: eliminación y restauración

### 4. Probar con productos

El mismo proceso aplica para productos:
- Lista de productos: `/productos`
- Papelera de productos: `/productos-eliminados`

### 5. Verificar datos en debug

Después de cada acción, revisa `/debug-audit` para ver:
- Que el campo `tags` contiene entradas como `["motivo:tu motivo aquí"]`
- Que los eventos 'deleted' y 'restored' tienen los motivos correspondientes

## Qué deberías ver

### En el historial de auditoría:
- Una sección titulada "Motivo de eliminación:" o "Motivo de restauración:"
- El texto del motivo entrecomillado
- Un icono de comentario al lado

### En la página de debug:
```json
{
  "id": 123,
  "event": "deleted",
  "tags": [
    "motivo:Usuario inactivo por más de 6 meses"
  ],
  ...
}
```

## Solución de problemas

### Si no ves motivos en el historial:
1. Verifica que el AuditServiceProvider esté registrado en `bootstrap/providers.php`
2. Limpia las cachés: `php artisan config:clear`
3. Revisa `/debug-audit` para ver los datos raw
4. Verifica que los modales requieren motivo antes de enviar

### Si los modales no requieren motivo:
1. Verifica que JavaScript está funcionando
2. Inspecciona la consola del navegador para errores
3. Asegúrate de que los IDs de los elementos coinciden

### Si hay errores 500:
1. Revisa los logs de Laravel: `storage/logs/laravel.log`
2. Verifica que todas las validaciones estén funcionando
3. Asegúrate de que el campo 'motivo' se está enviando en las requests

## Archivos importantes modificados

- `app/Providers/AuditServiceProvider.php` - Maneja el almacenamiento de motivos
- `resources/views/components/delete-modal.blade.php` - Modal con campo de motivo
- `resources/views/components/restore-modal.blade.php` - Modal con campo de motivo
- `resources/views/users/audit-history.blade.php` - Muestra motivos en historial
- `resources/views/productos/audit-history.blade.php` - Muestra motivos en historial
- `app/Models/User.php` y `app/Models/Producto.php` - Configuración de auditoría
