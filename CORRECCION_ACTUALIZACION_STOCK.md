# 🔧 Corrección: Actualización de Stock en Productos

**Fecha:** 12 de Noviembre de 2025  
**Problema:** El stock no se actualiza en la tabla `productos` aunque los movimientos se registran correctamente

---

## 🔍 Problema Identificado

### Síntomas:
- ✅ Los movimientos de inventario se registran en `movimientos_inventario`
- ✅ Los reportes muestran los movimientos correctamente
- ❌ El campo `stock_actual` en la tabla `productos` NO se actualiza
- ❌ En la vista de producto sigue mostrando el stock antiguo

### Causa Raíz:
Los métodos estáticos en `MovimientoInventario` estaban:
1. Calculando correctamente el `stock_nuevo`
2. Guardando el registro del movimiento
3. **PERO NO actualizaban el campo `stock_actual` en la tabla `productos`**

---

## ✅ Solución Implementada

### Archivo: `app/Models/MovimientoInventario.php`

Se agregó la actualización del stock en los 3 métodos principales:

### 1. `registrarIngreso()` - Ingresos de mercadería

**ANTES:**
```php
public static function registrarIngreso(
    Producto $producto, 
    int $cantidad, 
    User $responsable, 
    ?string $descripcion = null
): self {
    return self::create([
        'producto_id' => $producto->id,
        'tipo' => 'ingreso',
        'cantidad' => $cantidad,
        'stock_anterior' => $producto->stock_actual,
        'stock_nuevo' => $producto->stock_actual + $cantidad, // ❌ Solo calcula, no actualiza
        ...
    ]);
}
```

**DESPUÉS:**
```php
public static function registrarIngreso(
    Producto $producto, 
    int $cantidad, 
    User $responsable, 
    ?string $descripcion = null
): self {
    $stockAnterior = $producto->stock_actual;
    $stockNuevo = $stockAnterior + $cantidad;
    
    // ✅ Actualizar el stock del producto
    $producto->stock_actual = $stockNuevo;
    $producto->save();
    
    return self::create([
        'producto_id' => $producto->id,
        'tipo' => 'ingreso',
        'cantidad' => $cantidad,
        'stock_anterior' => $stockAnterior,
        'stock_nuevo' => $stockNuevo,
        ...
    ]);
}
```

---

### 2. `registrarSalida()` - Salidas de mercadería

**ANTES:**
```php
public static function registrarSalida(...): self {
    return self::create([
        'stock_nuevo' => $producto->stock_actual - $cantidad, // ❌ Solo calcula
        ...
    ]);
}
```

**DESPUÉS:**
```php
public static function registrarSalida(...): self {
    $stockAnterior = $producto->stock_actual;
    $stockNuevo = $stockAnterior - $cantidad;
    
    // ✅ Actualizar el stock del producto
    $producto->stock_actual = $stockNuevo;
    $producto->save();
    
    return self::create([
        'stock_anterior' => $stockAnterior,
        'stock_nuevo' => $stockNuevo,
        ...
    ]);
}
```

---

### 3. `registrarAjuste()` - Ajustes de inventario

**ANTES:**
```php
public static function registrarAjuste(
    Producto $producto, 
    int $nuevoStock, 
    ...
): self {
    $diferencia = $nuevoStock - $producto->stock_actual;
    
    return self::create([
        'stock_nuevo' => $nuevoStock, // ❌ Solo registra el movimiento
        ...
    ]);
}
```

**DESPUÉS:**
```php
public static function registrarAjuste(
    Producto $producto, 
    int $nuevoStock, 
    ...
): self {
    $stockAnterior = $producto->stock_actual;
    $diferencia = $nuevoStock - $stockAnterior;
    
    // ✅ Actualizar el stock del producto
    $producto->stock_actual = $nuevoStock;
    $producto->save();
    
    return self::create([
        'stock_anterior' => $stockAnterior,
        'stock_nuevo' => $nuevoStock,
        ...
    ]);
}
```

---

## 🎯 Flujo Correcto Ahora

### Ejemplo: Entrada de 10 unidades

**ANTES:**
1. Usuario hace ajuste: +10 unidades
2. ✅ Se crea registro en `movimientos_inventario`: stock_nuevo = 13
3. ❌ Producto sigue con stock_actual = 3
4. ❌ Vista muestra: "Stock: 3" (desactualizado)

**DESPUÉS:**
1. Usuario hace ajuste: +10 unidades
2. ✅ Se actualiza `productos.stock_actual = 13`
3. ✅ Se crea registro en `movimientos_inventario`: stock_nuevo = 13
4. ✅ Vista muestra: "Stock: 13" (correcto)

---

## 🧪 Validación

### Prueba 1: Ajuste de Stock (Entrada)
1. Ir a **Productos → Ver Producto → Ajustar Stock**
2. Seleccionar "Entrada"
3. Cantidad: 10
4. Guardar
5. ✅ Verificar que el stock en la vista se actualice inmediatamente
6. ✅ Verificar en el reporte de movimientos

### Prueba 2: Ajuste de Stock (Salida)
1. Seleccionar "Salida"
2. Cantidad: 5
3. Guardar
4. ✅ Stock debe disminuir correctamente

### Prueba 3: Ajuste Manual
1. Seleccionar "Ajuste"
2. Establecer nuevo stock: 20
3. Guardar
4. ✅ Stock debe cambiar a exactamente 20

### Prueba 4: Venta (genera salida automática)
1. Crear una venta con productos
2. Completar la venta
3. ✅ Stock debe disminuir automáticamente

---

## 📊 Tablas Afectadas

### Tabla `productos`
```sql
UPDATE productos 
SET stock_actual = [nuevo_valor]
WHERE id = [producto_id];
```

### Tabla `movimientos_inventario`
```sql
INSERT INTO movimientos_inventario (
    producto_id,
    tipo,
    cantidad,
    stock_anterior,
    stock_nuevo,  -- ✅ Ahora coincide con productos.stock_actual
    ...
);
```

---

## ⚠️ Notas Importantes

### Consistencia de Datos:
Ahora hay **doble garantía** de integridad:
1. Campo `stock_actual` en tabla `productos` (valor actual)
2. Campo `stock_nuevo` en última fila de `movimientos_inventario` (histórico)

Ambos valores deben coincidir. Si no coinciden, indica un problema de integridad.

### Transacciones:
El método `ajustarStock()` en `ProductoController` ya usa `DB::transaction()`, por lo que:
- Si falla la actualización del producto → se revierte el movimiento
- Si falla el registro del movimiento → se revierte la actualización del producto

---

## 🔄 Comandos de Validación SQL

### Verificar consistencia:
```sql
-- Ver productos con discrepancia entre stock y último movimiento
SELECT 
    p.id,
    p.nombre,
    p.stock_actual AS stock_producto,
    m.stock_nuevo AS stock_movimiento,
    m.fecha AS ultimo_movimiento
FROM productos p
LEFT JOIN LATERAL (
    SELECT stock_nuevo, fecha
    FROM movimientos_inventario
    WHERE producto_id = p.id
    ORDER BY fecha DESC, id DESC
    LIMIT 1
) m ON true
WHERE p.stock_actual != m.stock_nuevo OR m.stock_nuevo IS NULL;
```

---

## 📝 Resumen de Cambios

| Método | Cambio | Impacto |
|--------|--------|---------|
| `registrarIngreso()` | Agregado `$producto->save()` | ✅ Stock aumenta |
| `registrarSalida()` | Agregado `$producto->save()` | ✅ Stock disminuye |
| `registrarAjuste()` | Agregado `$producto->save()` | ✅ Stock se ajusta |

---

## 🎉 Resultado Final

✅ **Stock se actualiza correctamente en `productos`**
✅ **Movimientos se registran en `movimientos_inventario`**
✅ **Vista de producto muestra stock actualizado**
✅ **Reportes coinciden con realidad**
✅ **Transacciones garantizan integridad**

---

**LISTO PARA PROBAR** 🚀

Haz un ajuste de stock y verifica que el número cambie inmediatamente en la vista del producto.
