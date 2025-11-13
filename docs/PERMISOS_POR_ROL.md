# Sistema de Permisos por Rol

## Resumen de Permisos

### 👨‍💼 Administrador (37 permisos)
**Acceso total al sistema**

- ✅ Todos los permisos de Usuarios
- ✅ Todos los permisos de Clientes
- ✅ Todos los permisos de Productos
- ✅ Todos los permisos de Inventario
- ✅ Todos los permisos de Ventas
- ✅ Todos los permisos de Facturas
- ✅ Todos los permisos de Reportes

---

### 💼 Vendedor (13 permisos)
**Enfoque en ventas y atención al cliente**

#### Clientes (4 permisos)
- ✅ `clientes.ver` - Ver listado y detalles
- ✅ `clientes.crear` - Registrar nuevos clientes
- ✅ `clientes.editar` - Modificar información
- ✅ `clientes.verificar_edad` - Validar edad para licor

#### Ventas (2 permisos)
- ✅ `ventas.ver` - Ver listado de ventas
- ✅ `ventas.crear` - Realizar ventas en POS

#### Facturas (4 permisos)
- ✅ `facturas.ver` - Ver facturas
- ✅ `facturas.crear` - Generar facturas
- ✅ `facturas.descargar` - Descargar XML/RIDE
- ✅ `facturas.enviar_email` - Enviar factura por email

#### Productos (2 permisos)
- ✅ `productos.ver` - Ver catálogo
- ✅ `productos.ver_stock` - Consultar disponibilidad

#### Reportes (1 permiso)
- ✅ `reportes.ventas` - Ver reportes de ventas

#### ❌ NO TIENE ACCESO A:
- ❌ Usuarios (gestión)
- ❌ Inventario (ajustes)
- ❌ Productos (crear/editar/eliminar)
- ❌ Auditorías detalladas
- ❌ Anular ventas
- ❌ Reportes de inventario

---

### 📦 Jefe de Bodega (13 permisos)
**Enfoque en inventario y productos**

#### Productos (7 permisos)
- ✅ `productos.ver` - Ver listado
- ✅ `productos.crear` - Agregar productos
- ✅ `productos.editar` - Modificar información
- ✅ `productos.eliminar` - Eliminar productos
- ✅ `productos.restaurar` - Restaurar eliminados
- ✅ `productos.ver_stock` - Ver stock
- ✅ `productos.ajustar_stock` - Ajustar cantidades

#### Inventario (5 permisos)
- ✅ `inventario.ver` - Ver movimientos
- ✅ `inventario.entrada` - Registrar entradas
- ✅ `inventario.salida` - Registrar salidas
- ✅ `inventario.ajuste` - Ajustes de inventario
- ✅ `inventario.reportes` - Ver reportes

#### Reportes (1 permiso)
- ✅ `reportes.inventario` - Reportes de stock

#### ❌ NO TIENE ACCESO A:
- ❌ Usuarios (gestión)
- ❌ Clientes (gestión)
- ❌ Ventas (crear/anular)
- ❌ Facturas (gestión)
- ❌ Reportes de ventas
- ❌ Auditorías de usuarios

---

## Lista Completa de Permisos

### 👥 Usuarios (6 permisos)
- `usuarios.ver`
- `usuarios.crear`
- `usuarios.editar`
- `usuarios.eliminar`
- `usuarios.restaurar`
- `usuarios.asignar_roles`

### 👤 Clientes (6 permisos)
- `clientes.ver`
- `clientes.crear`
- `clientes.editar`
- `clientes.eliminar`
- `clientes.restaurar`
- `clientes.verificar_edad`

### 📦 Productos (7 permisos)
- `productos.ver`
- `productos.crear`
- `productos.editar`
- `productos.eliminar`
- `productos.restaurar`
- `productos.ver_stock`
- `productos.ajustar_stock`

### 📊 Inventario (5 permisos)
- `inventario.ver`
- `inventario.entrada`
- `inventario.salida`
- `inventario.ajuste`
- `inventario.reportes`

### 💰 Ventas (4 permisos)
- `ventas.ver`
- `ventas.crear`
- `ventas.anular`
- `ventas.editar`

### 🧾 Facturas (5 permisos)
- `facturas.ver`
- `facturas.crear`
- `facturas.anular`
- `facturas.descargar`
- `facturas.enviar_email`

### 📈 Reportes (4 permisos)
- `reportes.ventas`
- `reportes.inventario`
- `reportes.auditoria`
- `reportes.exportar`

---

## Implementación

### Verificación en Vistas (Blade)
```blade
@can('ventas.crear')
    <!-- Mostrar botón Nueva Venta -->
@endcan
```

### Verificación en Controladores
```php
$this->authorize('ventas.crear');
```

### Protección de Rutas
```php
Route::resource('ventas', VentaController::class)
    ->middleware('permission:ventas.ver');
```

---

## Flujo de Trabajo por Rol

### 🛒 Vendedor - Flujo Típico
1. Ver dashboard con ventas del día
2. Crear nueva venta en POS
3. Buscar/agregar cliente
4. Seleccionar productos (solo consulta stock)
5. Procesar venta
6. Generar factura electrónica
7. Descargar RIDE/XML
8. Enviar factura por email

### 📦 Jefe de Bodega - Flujo Típico
1. Ver dashboard con alertas de stock
2. Revisar productos bajo stock
3. Registrar entrada de mercancía
4. Ajustar stock de productos
5. Crear/editar productos
6. Ver movimientos de inventario
7. Generar reportes de inventario

### 👨‍💼 Administrador - Acceso Total
- Gestión completa de usuarios y roles
- Supervisión de todas las operaciones
- Acceso a todos los reportes
- Auditorías del sistema
- Configuración general

---

## Notas Importantes

1. **Seguridad en Rutas**: Todas las rutas están protegidas con middleware `permission:`
2. **Menú Dinámico**: El sidebar solo muestra opciones según permisos del usuario
3. **Validación Doble**: Se valida en rutas Y en controladores
4. **Cache de Permisos**: Los permisos se cachean automáticamente (Spatie Permission)
5. **Actualización**: Para actualizar permisos ejecutar: `php artisan db:seed --class=RolesAndPermissionsSeeder`

---

## Testing de Permisos

### Usuarios de Prueba
```
Admin:
- Email: admin@infernoclub.com
- Password: password123

Vendedor:
- Email: vendedor@infernoclub.com
- Password: password123

Jefe de Bodega:
- Email: bodega@infernoclub.com
- Password: password123
```

### Verificación Manual
1. Iniciar sesión con cada rol
2. Verificar que el menú lateral muestre solo opciones permitidas
3. Intentar acceder a rutas no permitidas (debe redirigir 403)
4. Verificar que los botones de acción aparezcan según permisos
