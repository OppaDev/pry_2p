# 🔐 Configuración de Permisos por Rol

**Fecha:** 12 de Noviembre de 2025  
**Sistema:** Laravel 11 con Spatie Permission

---

## 👥 Definición de Roles y Permisos

### 1. **ADMINISTRADOR** 🔑
**Acceso:** TODO EL SISTEMA

- ✅ Usuarios (CRUD completo)
- ✅ Clientes (CRUD completo)
- ✅ Productos (CRUD completo + ajuste stock)
- ✅ Inventario (todos los movimientos)
- ✅ Ventas (CRUD completo)
- ✅ Facturas (CRUD completo)
- ✅ Reportes (TODOS)
- ✅ Auditorías (acceso completo)

---

### 2. **VENDEDOR** 🛒
**Acceso:** Solo operaciones de VENTAS y CLIENTES

#### ✅ Puede hacer:
- **Clientes:**
  - Ver, crear, editar, eliminar clientes
  - Verificar edad
  - Restaurar clientes eliminados

- **Ventas:**
  - Ver, crear, editar, anular ventas

- **Facturas:**
  - Ver, crear, anular facturas
  - Descargar XML y RIDE
  - Enviar por email

- **Productos:**
  - ❗ **SOLO VER** (no puede crear/editar/eliminar)
  - Ver stock actual

- **Reportes:**
  - Reportes de ventas
  - Reportes de clientes
  - Ventas por vendedor
  - Exportar reportes

#### ❌ NO puede hacer:
- ❌ Crear/editar/eliminar productos
- ❌ Ajustar stock de productos
- ❌ Ver reportes de inventario
- ❌ Ver reportes de productos más vendidos
- ❌ Ver reportes de bajo stock
- ❌ Ver auditorías del sistema
- ❌ Gestionar usuarios

---

### 3. **JEFE DE BODEGA** 📦
**Acceso:** Solo operaciones de INVENTARIO y PRODUCTOS

#### ✅ Puede hacer:
- **Productos:**
  - Ver, crear, editar, eliminar productos
  - Restaurar productos eliminados
  - Ver stock
  - **Ajustar stock** (entradas, salidas, ajustes)

- **Inventario:**
  - Registrar entradas de mercadería
  - Registrar salidas de mercadería
  - Realizar ajustes de inventario
  - Ver movimientos de inventario

- **Reportes:**
  - Reportes de inventario
  - Reportes de productos más vendidos
  - Reportes de movimientos de inventario
  - Reportes de productos con bajo stock
  - Exportar reportes

#### ❌ NO puede hacer:
- ❌ Gestionar clientes
- ❌ Crear/gestionar ventas
- ❌ Emitir facturas
- ❌ Ver reportes de ventas
- ❌ Ver auditorías del sistema
- ❌ Gestionar usuarios

---

## 📋 Tabla Comparativa de Permisos

| Módulo | Administrador | Vendedor | Jefe de Bodega |
|--------|---------------|----------|----------------|
| **Usuarios** | ✅ CRUD | ❌ | ❌ |
| **Clientes** | ✅ CRUD | ✅ CRUD | ❌ |
| **Productos - Ver** | ✅ | ✅ Solo ver | ✅ |
| **Productos - CRUD** | ✅ | ❌ | ✅ |
| **Productos - Ajustar Stock** | ✅ | ❌ | ✅ |
| **Inventario** | ✅ | ❌ | ✅ |
| **Ventas** | ✅ CRUD | ✅ CRUD | ❌ |
| **Facturas** | ✅ CRUD | ✅ CRUD | ❌ |
| **Reportes - Ventas** | ✅ | ✅ | ❌ |
| **Reportes - Inventario** | ✅ | ❌ | ✅ |
| **Reportes - Auditorías** | ✅ | ❌ | ❌ |

---

## 🔧 Archivos Modificados

### 1. `database/seeders/RolesAndPermissionsSeeder.php`
**Cambios:**
- ✅ Permisos del **Vendedor** actualizados:
  - Agregado: clientes.eliminar, clientes.restaurar
  - Agregado: ventas.editar, ventas.anular
  - Agregado: facturas.anular
  - Agregado: reportes.exportar
  - **Removido:** productos.crear, productos.editar, productos.eliminar

- ✅ Permisos del **Jefe de Bodega** actualizados:
  - Mantenido: productos.* (todos)
  - Mantenido: inventario.* (todos)
  - Agregado: reportes.exportar
  - **Removido:** acceso a ventas y clientes

### 2. `app/Policies/ReportePolicy.php` (NUEVO)
**Creado para gestionar permisos de reportes:**
```php
verReportesVentas()        // Administrador + Vendedor
verReportesInventario()    // Administrador + Jefe de Bodega
verReportesAuditoria()     // Solo Administrador
exportarReportes()         // Todos (según su alcance)
```

### 3. `app/Http/Controllers/ReporteController.php`
**Middleware agregado en constructor:**
```php
// Reportes de Ventas: Administrador y Vendedor
$this->middleware('can:verReportesVentas')->only([
    'ventas', 'ventasPorVendedor', 'clientes'
]);

// Reportes de Inventario: Administrador y Jefe de Bodega
$this->middleware('can:verReportesInventario')->only([
    'inventario', 'productosMasVendidos', 'movimientosInventario', 'bajoStock'
]);

// Reportes de Auditoría: Solo Administrador
$this->middleware('can:verReportesAuditoria')->only([
    'auditoria'
]);
```

### 4. `app/Providers/AppServiceProvider.php`
**Gates registrados:**
```php
Gate::define('verReportesVentas', ...);
Gate::define('verReportesInventario', ...);
Gate::define('verReportesAuditoria', ...);
Gate::define('exportarReportes', ...);
```

### 5. `resources/views/productos/index.blade.php`
**Botón "NUEVO PRODUCTO" protegido:**
```blade
@can('productos.crear')
    <a href="{{ route('productos.create') }}">
        Nuevo Producto
    </a>
@endcan
```

---

## 🧪 Cómo Validar los Permisos

### Prueba 1: Vendedor NO puede crear productos
1. Iniciar sesión como **vendedor**
2. Ir a **Inventario → Productos**
3. ✅ Debe ver la lista de productos
4. ✅ El botón **"NUEVO PRODUCTO"** NO debe aparecer
5. ✅ Si intenta acceder a `/productos/create` directamente → Error 403

### Prueba 2: Vendedor SÍ puede crear ventas
1. Como **vendedor**
2. Ir a **Ventas → Nueva Venta**
3. ✅ Debe permitir crear una venta
4. ✅ Debe permitir generar factura

### Prueba 3: Vendedor NO puede ver reportes de inventario
1. Como **vendedor**
2. Ir a **Reportes**
3. ✅ Debe ver solo: Ventas, Clientes, Ventas por Vendedor
4. ❌ NO debe ver: Inventario, Productos más vendidos, Bajo Stock

### Prueba 4: Jefe de Bodega SÍ puede crear productos
1. Iniciar sesión como **jefe_bodega**
2. Ir a **Inventario → Productos**
3. ✅ Debe ver el botón **"NUEVO PRODUCTO"**
4. ✅ Puede crear, editar, eliminar productos
5. ✅ Puede ajustar stock

### Prueba 5: Jefe de Bodega NO puede crear ventas
1. Como **jefe_bodega**
2. Intentar ir a **Ventas**
3. ❌ No debe aparecer en el menú o debe dar Error 403

### Prueba 6: Administrador puede hacer TODO
1. Iniciar sesión como **administrador**
2. ✅ Debe ver todos los módulos
3. ✅ Debe poder acceder a todos los reportes
4. ✅ Debe poder gestionar usuarios

---

## 🔄 Aplicar Cambios

### Paso 1: Regenerar permisos
```bash
php artisan migrate:fresh --seed
```

**⚠️ ADVERTENCIA:** Esto eliminará TODOS los datos existentes.

### Paso 2: Solo actualizar permisos (sin borrar datos)
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### Paso 3: Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan permission:cache-reset
```

---

## 👤 Usuarios de Prueba

Después del seeding, deberías tener:

| Usuario | Email | Password | Rol |
|---------|-------|----------|-----|
| Admin | admin@infernoclub.com | password | Administrador |
| Vendedor | vendedor@infernoclub.com | password | Vendedor |
| Jefe Bodega | jefe.bodega@infernoclub.com | password | Jefe de Bodega |

---

## 📊 Estructura de Permisos

### Nomenclatura:
- `modulo.accion`
- Ejemplos: `productos.crear`, `ventas.ver`, `reportes.inventario`

### Verificación en código:
```php
// Blade
@can('productos.crear')
    <!-- Solo usuarios con permiso -->
@endcan

// Controlador
$this->authorize('create', Producto::class);

// Middleware
$this->middleware('can:productos.crear');

// Verificación directa
if (auth()->user()->can('productos.crear')) {
    // ...
}
```

---

## 🎯 Resumen de Correcciones

| Problema | Solución | Estado |
|----------|----------|--------|
| Vendedor podía crear productos | Removido permiso `productos.crear` | ✅ |
| Botón "Nuevo Producto" visible para todos | Agregado `@can('productos.crear')` | ✅ |
| Reportes sin restricción por rol | Creado `ReportePolicy` + middleware | ✅ |
| Jefe de Bodega veía módulo Ventas | Permisos específicos por rol | ✅ |

---

## 🚀 Estado Final

✅ **Administrador:** Acceso completo a todo el sistema
✅ **Vendedor:** Solo ventas, clientes, facturas y sus reportes
✅ **Jefe de Bodega:** Solo productos, inventario y sus reportes
✅ **Permisos protegidos** en vistas y controladores
✅ **Políticas registradas** correctamente

---

**LISTO PARA APLICAR** 🎉

Ejecuta el seeder y prueba con cada rol para validar los permisos.
