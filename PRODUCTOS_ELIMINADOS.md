# Gestión de Productos Eliminados (Soft Deletes)

## Descripción

Se ha implementado un sistema completo de gestión de productos eliminados usando **Soft Deletes** de Laravel, que permite "eliminar" productos sin borrarlos permanentemente de la base de datos. Esto proporciona una funcionalidad de "papelera de reciclaje" para productos.

## Características Implementadas

### 🗂️ Gestión de Productos Eliminados
- **Vista separada** para productos eliminados (`/productos-eliminados`)
- **Botón de acceso** desde la lista principal de productos
- **Búsqueda y paginación** en productos eliminados
- **Información de cuándo fue eliminado** cada producto

### 🔄 Funcionalidades Disponibles
1. **Restaurar productos** - Volver el producto a la lista activa
2. **Eliminar permanentemente** - Borrar definitivamente el producto
3. **Búsqueda** entre productos eliminados
4. **Paginación** para manejar grandes cantidades

### 🎨 Diseño Soft UI
- **Colores diferenciados** (rojos) para indicar estado eliminado
- **Iconografía específica** (trash-restore, undo, skull-crossbones)
- **Modales de confirmación** distintos para cada acción
- **Animaciones suaves** y transiciones elegantes

## Rutas Implementadas

```php
// Ver productos eliminados
GET /productos-eliminados → productos.deleted

// Restaurar producto
PATCH /productos/{id}/restore → productos.restore

// Eliminar permanentemente
DELETE /productos/{id}/force-delete → productos.forceDelete
```

## Métodos del Controlador

### `deletedProducts(Request $request)`
- Lista productos eliminados con paginación y búsqueda
- Soporta filtros por nombre y código
- Ordenados por fecha de eliminación (más recientes primero)

### `restore($id)`
- Restaura un producto eliminado usando `restore()`
- Retorna mensaje de éxito/error
- Redirige a la lista de productos eliminados

### `forceDelete($id)`
- Elimina permanentemente un producto usando `forceDelete()`
- Acción irreversible
- Requiere doble confirmación

## Componentes de Vista

### Botón de Acceso
En `/productos` se agregó un botón "Productos Eliminados" que:
- Usa gradiente gris/slate para diferenciarse
- Incluye icono `fa-trash-restore`
- Mantiene consistencia con el diseño Soft UI

### Vista de Productos Eliminados
- **Header diferenciado** con título "PRODUCTOS ELIMINADOS"
- **Botón "Volver"** para regresar a la lista principal
- **Tabla con columna adicional** "ELIMINADO" mostrando fecha/hora
- **Filas con fondo rojizo** sutil para indicar estado eliminado
- **Etiqueta "Eliminado"** bajo el nombre del producto

### Modales de Confirmación

#### Modal de Restauración (`restore-modal.blade.php`)
- **Color verde** para acciones positivas
- **Icono de "undo"** (fa-undo)
- **Mensaje alentador** sobre la restauración
- **Botón verde** "Restaurar Producto"

#### Modal de Eliminación Permanente (`force-delete-modal.blade.php`)
- **Color rojo intenso** para advertir peligro
- **Iconos de advertencia** (fa-exclamation-triangle, fa-skull-crossbones)
- **Mensajes de ADVERTENCIA** prominentes
- **Doble confirmación** con JavaScript
- **Botón rojo** "Eliminar Permanentemente"

## Flujo de Usuario

### Eliminar Producto (Soft Delete)
1. En `/productos` → Click "Eliminar" → Confirmar
2. Producto se mueve a "eliminado" (no se borra)
3. Aparece mensaje de éxito
4. Producto desaparece de la lista principal

### Ver Productos Eliminados
1. En `/productos` → Click "Productos Eliminados"
2. Se abre `/productos-eliminados` con lista filtrada
3. Muestra solo productos con `deleted_at` no nulo
4. Información de cuándo fue eliminado cada uno

### Restaurar Producto
1. En `/productos-eliminados` → Click "Restaurar"
2. Modal verde de confirmación
3. Confirmar restauración
4. Producto vuelve a `/productos`
5. Desaparece de productos eliminados

### Eliminar Permanentemente
1. En `/productos-eliminados` → Click "Eliminar Definitivamente"
2. Modal rojo con múltiples advertencias
3. Confirmar con JavaScript adicional
4. Producto se borra definitivamente de la BD
5. Acción irreversible

## Características de Seguridad

### Validación de Datos
- **Parámetros validados** (`per_page`, `search`)
- **Manejo de errores** con try-catch
- **Mensajes informativos** de éxito/error

### Confirmaciones Múltiples
- **Modal de confirmación** para eliminación permanente
- **Confirmación JavaScript** adicional (`confirm()`)
- **Mensajes de advertencia** claros y visibles

### Protección de Rutas
- **Middleware de autenticación** requerido
- **Validación de existencia** del producto
- **Scope de productos eliminados** (`onlyTrashed()`)

## Mejoras Implementadas

### Experiencia de Usuario
- **Feedback visual** claro sobre el estado de cada producto
- **Navegación intuitiva** entre listas
- **Búsqueda independiente** en cada lista
- **Paginación consistente** en ambas vistas

### Diseño Responsivo
- **Tabla responsive** en todos los dispositivos
- **Modales adaptables** a diferentes pantallas
- **Botones táctiles** para dispositivos móviles

### Performance
- **Consultas optimizadas** con select específico
- **Paginación eficiente** con Laravel
- **Carga bajo demanda** de productos eliminados

## Archivos Creados/Modificados

### Controlador
- ✅ `ProductoController.php` - Métodos agregados:
  - `deletedProducts()`
  - `restore()`
  - `forceDelete()`

### Rutas
- ✅ `web.php` - Rutas agregadas:
  - `productos.deleted`
  - `productos.restore`  
  - `productos.forceDelete`

### Vistas
- ✅ `productos/index.blade.php` - Botón "Productos Eliminados"
- ✅ `productos/deleteProducts.blade.php` - Vista completa nueva
- ✅ `components/restore-modal.blade.php` - Modal de restauración
- ✅ `components/force-delete-modal.blade.php` - Modal eliminación permanente

### Estilos
- ✅ `modal-styles.css` - Estilos adicionales para nuevos modales

## Uso Práctico

### Para Administradores
- **Recuperar errores** - Restaurar productos eliminados por error
- **Limpieza periódica** - Eliminar permanentemente productos antiguos
- **Auditoría** - Ver qué productos fueron eliminados y cuándo

### Para el Sistema
- **Integridad referencial** - Mantener relaciones de BD intactas
- **Historial** - Preservar datos para reportes
- **Recuperación** - Posibilidad de deshacer acciones

## Comandos Útiles

```bash
# Ver productos eliminados en consola
php artisan tinker
>>> App\Models\Producto::onlyTrashed()->get()

# Restaurar todos los productos
>>> App\Models\Producto::onlyTrashed()->restore()

# Eliminar permanentemente productos antiguos
>>> App\Models\Producto::onlyTrashed()->where('deleted_at', '<', now()->subDays(30))->forceDelete()
```

## Consideraciones Técnicas

### Base de Datos
- **Campo `deleted_at`** en tabla `productos`
- **Índice recomendado** en `deleted_at` para performance
- **Backup regular** antes de eliminaciones permanentes

### Mantenimiento
- **Limpieza periódica** de productos muy antiguos
- **Monitoreo de tamaño** de tabla productos
- **Reportes de auditoría** de productos eliminados

---

**Funcionalidad de Productos Eliminados completamente implementada con Laravel Soft Deletes y Soft UI Design** ✨
