# Reporte de Correcciones #5
**Fecha:** 12 de noviembre de 2025  
**Problemas corregidos:** 2

---

## 🔴 PROBLEMA 1: TypeError al ajustar stock de productos

### Error reportado:
```
TypeError: App\Models\MovimientoInventario::registrarIngreso(): 
Argument #1 ($producto) must be of type App\Models\Producto, 
int given
```

### Causa raíz:
En `ProductoController.php`, los métodos `registrarIngreso()`, `registrarSalida()` y `registrarAjuste()` esperan objetos de tipo `Producto` y `User`, pero se estaban pasando IDs (enteros).

### Solución implementada:

**Archivo:** `app/Http/Controllers/ProductoController.php` (líneas 459-487)

**ANTES:**
```php
case 'entrada':
    MovimientoInventario::registrarIngreso(
        $producto->id,           // ❌ ID en lugar de objeto
        $cantidad,
        $request->descripcion,
        Auth::id()               // ❌ ID en lugar de objeto
    );
    break;
    
case 'salida':
    MovimientoInventario::registrarSalida(
        $producto->id,           // ❌ ID en lugar de objeto
        $cantidad,
        $request->descripcion,
        Auth::id()               // ❌ ID en lugar de objeto
    );
    break;
    
case 'ajuste':
    MovimientoInventario::registrarAjuste(
        $producto->id,           // ❌ ID en lugar de objeto
        $cantidad,
        $request->descripcion,
        Auth::id()               // ❌ ID en lugar de objeto
    );
    break;
```

**DESPUÉS:**
```php
case 'entrada':
    MovimientoInventario::registrarIngreso(
        $producto,               // ✅ Objeto Producto
        $cantidad,
        Auth::user(),            // ✅ Objeto User
        $request->descripcion
    );
    break;
    
case 'salida':
    MovimientoInventario::registrarSalida(
        $producto,               // ✅ Objeto Producto
        $cantidad,
        Auth::user(),            // ✅ Objeto User
        $request->descripcion
    );
    break;
    
case 'ajuste':
    MovimientoInventario::registrarAjuste(
        $producto,               // ✅ Objeto Producto
        $cantidad,
        Auth::user(),            // ✅ Objeto User
        $request->descripcion
    );
    break;
```

### Firma correcta de los métodos:
```php
// MovimientoInventario.php
public static function registrarIngreso(
    Producto $producto,    // ← Objeto, no ID
    int $cantidad, 
    User $responsable,     // ← Objeto, no ID
    ?string $descripcion = null
): self

public static function registrarSalida(
    Producto $producto,    // ← Objeto, no ID
    int $cantidad, 
    User $responsable,     // ← Objeto, no ID
    ?string $descripcion = null,
    ?string $refTipo = null,
    ?int $refId = null
): self

public static function registrarAjuste(
    Producto $producto,    // ← Objeto, no ID
    int $nuevoStock, 
    User $responsable,     // ← Objeto, no ID
    string $descripcion
): self
```

---

## 🔴 PROBLEMA 2: Botón "Agregar" no añade productos a la lista en ventas

### Error reportado:
Al hacer clic en "Agregar" en la página de crear venta, los productos no se añadían a la tabla del carrito.

### Causa raíz:
En `ventas/create.blade.php`, la función `actualizarTabla()` reemplazaba todo el contenido del `tbody` con `innerHTML`, lo que eliminaba el elemento `empty-row`. En la siguiente llamada, intentaba acceder a `emptyRow` con `getElementById()` pero ya no existía en el DOM, causando un error silencioso de JavaScript.

### Solución implementada:

**Archivo:** `resources/views/ventas/create.blade.php` (función `actualizarTabla`)

**ANTES:**
```javascript
function actualizarTabla() {
    const tbody = document.getElementById('tbody-productos');
    const emptyRow = document.getElementById('empty-row');  // ❌ Referencia externa
    
    if (carrito.length === 0) {
        emptyRow.style.display = 'table-row';  // ❌ Falla si ya fue eliminado
        document.getElementById('btn-procesar').disabled = true;
        return;
    }
    
    emptyRow.style.display = 'none';  // ❌ Oculta pero no elimina
    document.getElementById('btn-procesar').disabled = false;
    
    let html = '';
    carrito.forEach((producto, index) => {
        // ... construcción del HTML
    });
    
    tbody.innerHTML = html;  // ❌ Reemplaza TODO, eliminando empty-row
    actualizarTotales();
}
```

**DESPUÉS:**
```javascript
function actualizarTabla() {
    const tbody = document.getElementById('tbody-productos');
    const btnProcesar = document.getElementById('btn-procesar');  // ✅ Solo una referencia
    
    if (carrito.length === 0) {
        tbody.innerHTML = `  // ✅ Recrea empty-row cada vez
            <tr id="empty-row">
                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                    <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                    <p>No hay productos agregados</p>
                </td>
            </tr>
        `;
        btnProcesar.disabled = true;
        return;
    }
    
    btnProcesar.disabled = false;  // ✅ Directo al botón
    
    let html = '';
    carrito.forEach((producto, index) => {
        // ... construcción del HTML (sin cambios)
    });
    
    tbody.innerHTML = html;  // ✅ Ahora funciona correctamente
    actualizarTotales();
}
```

### Cambios clave:
1. ✅ Eliminada la referencia externa a `emptyRow` que causaba errores
2. ✅ `empty-row` ahora se recrea dinámicamente cuando el carrito está vacío
3. ✅ Referencia directa a `btnProcesar` en lugar de buscar múltiples veces
4. ✅ Flujo más limpio y predecible del DOM

---

## 🛠️ CORRECCIÓN ADICIONAL: Warning de number_format

### Warning detectado:
```
Argument '1' passed to number_format() is expected to be of type float, 
decimal|null given
```

### Ubicación:
`app/Http/Controllers/ProductoController.php` línea 610

### Solución:
```php
// ANTES
number_format($producto->precio, 2)

// DESPUÉS
number_format((float)$producto->precio, 2)  // ✅ Cast explícito a float
```

---

## ✅ Verificación

### Tests realizados:
1. ✅ Ajustar stock de un producto (entrada/salida/ajuste)
2. ✅ Agregar productos al carrito en crear venta
3. ✅ Verificar que el botón "Procesar Venta" se habilita/deshabilita correctamente
4. ✅ Exportar productos a CSV sin warnings

### Archivos modificados:
1. `app/Http/Controllers/ProductoController.php` (3 cambios)
   - Corrección de parámetros en registrarIngreso/Salida/Ajuste
   - Cast de precio a float en exportación CSV
   
2. `resources/views/ventas/create.blade.php` (1 cambio)
   - Refactorización de función actualizarTabla()

### Sin errores de sintaxis:
```bash
✅ ProductoController.php - 0 errores
✅ ventas/create.blade.php - 0 errores
```

---

## 📝 Lecciones aprendidas

### Type Hints en PHP:
- Los métodos estáticos con type hints estrictos requieren los tipos exactos
- `Auth::id()` devuelve `int`, pero se necesita `Auth::user()` para obtener el objeto `User`
- Siempre verificar las firmas de los métodos antes de llamarlos

### JavaScript y DOM:
- Evitar referencias externas a elementos que serán eliminados del DOM
- `innerHTML` reemplaza TODO el contenido, no hace merge
- Mejor recrear elementos dinámicamente que intentar mantener referencias
- Los errores de JavaScript pueden ser silenciosos si no se revisa la consola

### Debugging:
- TypeErrors de PHP son muy explícitos sobre qué espera vs qué recibe
- Errores de JavaScript requieren revisar la consola del navegador
- El modal funcionaba bien, el problema estaba en el flujo del carrito

---

**Estado final:** ✅ AMBOS PROBLEMAS RESUELTOS
