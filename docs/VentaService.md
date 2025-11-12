# VentaService - Documentación

## Descripción
Servicio centralizado para la gestión completa del proceso de ventas en Inferno Club. Implementa toda la lógica de negocio relacionada con ventas, validaciones, cálculos y gestión de inventario.

## Características Principales

### 1. Procesamiento de Ventas
- ✅ **Validación de edad del cliente** (≥18 años para venta de licor)
- ✅ **Verificación de stock** antes de procesar la venta
- ✅ **Generación automática** de número secuencial (VTA-YYYYMMDD-XXXX)
- ✅ **Cálculo automático** de subtotal, IVA (15%) y total
- ✅ **Registro de movimientos** de inventario por cada producto
- ✅ **Transacciones atómicas** (rollback en caso de error)
- ✅ **Logging completo** de operaciones exitosas y errores

### 2. Anulación de Ventas
- ✅ Restauración automática de stock
- ✅ Registro de movimientos de devolución
- ✅ Auditoría con motivo de anulación
- ✅ Validación de estado (no anular ventas ya anuladas)

### 3. Consultas y Estadísticas
- ✅ Estadísticas de ventas con filtros (fecha, vendedor)
- ✅ Búsqueda avanzada de ventas
- ✅ Detalle completo de venta con relaciones
- ✅ Verificación de disponibilidad de productos
- ✅ Cálculo previo de totales

## Métodos Públicos

### `procesarVenta(array $datos, User $vendedor): Venta`

Procesa una nueva venta completa.

**Parámetros:**
```php
$datos = [
    'cliente_id' => int,           // ID del cliente (requerido)
    'metodo_pago' => string,       // efectivo|tarjeta|transferencia (opcional, default: efectivo)
    'observaciones' => string,     // Observaciones adicionales (opcional)
    'items' => [                   // Array de productos (requerido, mínimo 1)
        [
            'producto_id' => int,      // ID del producto
            'cantidad' => int,         // Cantidad a vender (> 0)
            'precio_unitario' => float // Precio (opcional, usa precio del producto)
        ]
    ]
];
```

**Retorna:** Objeto `Venta` con relaciones cargadas (cliente, vendedor, detalles.producto)

**Excepciones:**
- `Exception` si el cliente es menor de edad
- `Exception` si hay stock insuficiente
- `Exception` si el producto está inactivo
- `Exception` si faltan datos requeridos

**Proceso interno:**
1. Valida datos de entrada
2. Verifica edad del cliente (≥18 años)
3. Valida stock disponible de todos los productos
4. Genera número secuencial único
5. Crea registro de venta
6. Procesa cada ítem:
   - Crea detalle de venta
   - Actualiza stock del producto
   - Registra movimiento de inventario (tipo: salida)
7. Calcula totales (subtotal + IVA 15%)
8. Actualiza la venta con totales
9. Registra log de éxito
10. Commit de transacción

**Ejemplo de uso:**
```php
$ventaService = new VentaService();

$datos = [
    'cliente_id' => 1,
    'metodo_pago' => 'tarjeta',
    'observaciones' => 'Cliente frecuente',
    'items' => [
        ['producto_id' => 5, 'cantidad' => 2],
        ['producto_id' => 8, 'cantidad' => 1, 'precio_unitario' => 45.00]
    ]
];

try {
    $venta = $ventaService->procesarVenta($datos, auth()->user());
    // Venta procesada exitosamente
    // $venta->numero_secuencial => "VTA-20251112-0001"
    // $venta->total => 125.50
} catch (Exception $e) {
    // Error: $e->getMessage()
}
```

---

### `anularVenta(int $ventaId, string $motivo, User $usuario): Venta`

Anula una venta existente y restaura el stock.

**Parámetros:**
- `$ventaId`: ID de la venta a anular
- `$motivo`: Razón de la anulación (se registra en auditoría)
- `$usuario`: Usuario responsable de la anulación

**Retorna:** Objeto `Venta` anulada

**Excepciones:**
- `Exception` si la venta ya está anulada
- `Exception` si la venta no existe

**Proceso interno:**
1. Busca la venta con detalles
2. Valida que no esté anulada
3. Por cada detalle:
   - Restaura stock del producto
   - Registra movimiento de inventario (tipo: ingreso)
4. Cambia estado a 'anulada'
5. Registra auditoría con motivo

**Ejemplo de uso:**
```php
try {
    $ventaAnulada = $ventaService->anularVenta(
        15, 
        'Cliente solicitó cancelación por error en pedido', 
        auth()->user()
    );
} catch (Exception $e) {
    // Error: $e->getMessage()
}
```

---

### `obtenerEstadisticas(array $filtros = []): array`

Obtiene estadísticas de ventas completadas.

**Filtros opcionales:**
```php
$filtros = [
    'fecha_inicio' => '2025-01-01',
    'fecha_fin' => '2025-12-31',
    'vendedor_id' => 5
];
```

**Retorna:**
```php
[
    'total_ventas' => 150,        // Cantidad de ventas
    'total_ingresos' => 15750.50, // Suma de totales
    'promedio_venta' => 105.00,   // Promedio por venta
    'venta_mayor' => 450.00,      // Venta más alta
    'venta_menor' => 25.50,       // Venta más baja
    'total_impuestos' => 2362.58  // Total de IVA recaudado
]
```

---

### `obtenerDetalleVenta(int $ventaId): Venta`

Obtiene el detalle completo de una venta.

**Retorna:** Venta con relaciones:
- cliente
- vendedor
- detalles.producto.categoria
- factura

---

### `buscarVentas(array $filtros = [])`

Busca ventas con múltiples filtros.

**Filtros disponibles:**
```php
$filtros = [
    'numero_secuencial' => 'VTA-202511',
    'cliente_id' => 10,
    'vendedor_id' => 3,
    'estado' => 'completada',
    'fecha_inicio' => '2025-11-01',
    'fecha_fin' => '2025-11-30',
    'metodo_pago' => 'tarjeta'
];
```

**Retorna:** Collection de ventas ordenadas por fecha descendente

---

### `calcularTotales(array $items): array`

Calcula totales sin crear la venta (útil para previsualización).

**Retorna:**
```php
[
    'subtotal' => 100.00,
    'impuestos' => 15.00,
    'total' => 115.00
]
```

---

### `verificarDisponibilidad(array $items): array`

Verifica si hay stock disponible para los productos.

**Retorna:**
```php
[
    'disponible' => false,
    'errores' => [
        "Stock insuficiente para 'Whisky Black Label'. Disponible: 5",
        "El producto 'Ron Havana' no está activo."
    ]
]
```

## Reglas de Negocio Implementadas

### 1. Validación de Edad
- **Requisito:** Cliente debe tener ≥18 años
- **Verificación:** Usa atributo computado `$cliente->es_mayor_edad`
- **Mensaje:** "El cliente {nombre} es menor de edad ({edad} años). No se permite la venta de bebidas alcohólicas a menores de 18 años."

### 2. Gestión de Stock
- **Stock se reduce** al procesar venta
- **Stock se restaura** al anular venta
- **Validación previa** antes de confirmar venta
- **Movimientos registrados** en `movimientos_inventario`

### 3. Cálculo de Impuestos
- **IVA:** 15% sobre el subtotal
- **Subtotal:** Suma de (cantidad × precio_unitario) de todos los ítems
- **Total:** Subtotal + IVA
- **Redondeo:** 2 decimales

### 4. Número Secuencial
- **Formato:** VTA-YYYYMMDD-XXXX
- **Secuencia:** Reinicia cada día
- **Ejemplo:** VTA-20251112-0001, VTA-20251112-0002...

### 5. Transacciones Atómicas
- **DB::beginTransaction()** al inicio
- **DB::commit()** si todo es exitoso
- **DB::rollBack()** en caso de error
- Garantiza integridad de datos

## Logging

### Eventos Registrados

#### Venta Exitosa
```php
Log::info('Venta procesada exitosamente', [
    'venta_id' => 15,
    'numero_secuencial' => 'VTA-20251112-0001',
    'total' => 115.50,
    'vendedor_id' => 3
]);
```

#### Error en Venta
```php
Log::error('Error al procesar venta', [
    'error' => 'Stock insuficiente...',
    'trace' => '...',
    'datos' => [...]
]);
```

#### Venta Anulada
```php
Log::info('Venta anulada', [
    'venta_id' => 15,
    'motivo' => 'Cliente solicitó cancelación',
    'usuario_id' => 3
]);
```

## Tests Incluidos

Se incluye suite completa de tests unitarios en `tests/Unit/Services/VentaServiceTest.php`:

1. ✅ `puede_procesar_venta_exitosamente`
2. ✅ `no_permite_venta_a_menor_de_edad`
3. ✅ `no_permite_venta_sin_stock_suficiente`
4. ✅ `puede_anular_venta_y_restaurar_stock`
5. ✅ `calcula_totales_correctamente`
6. ✅ `verifica_disponibilidad_de_productos`
7. ✅ `detecta_productos_sin_stock`
8. ✅ `genera_numero_secuencial_unico`
9. ✅ `obtiene_estadisticas_de_ventas`
10. ✅ `no_permite_venta_de_producto_inactivo`
11. ✅ `procesa_venta_con_multiples_productos`

**Ejecutar tests:**
```bash
php artisan test --filter=VentaServiceTest
```

## Dependencias

### Modelos Utilizados
- `App\Models\Venta`
- `App\Models\DetalleVenta`
- `App\Models\Cliente`
- `App\Models\Producto`
- `App\Models\MovimientoInventario`
- `App\Models\User`

### Facades
- `Illuminate\Support\Facades\DB` (Transacciones)
- `Illuminate\Support\Facades\Log` (Logging)

## Ejemplo Completo de Uso

```php
<?php

namespace App\Http\Controllers;

use App\Services\VentaService;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    protected $ventaService;
    
    public function __construct(VentaService $ventaService)
    {
        $this->ventaService = $ventaService;
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'observaciones' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'nullable|numeric|min:0',
        ]);
        
        try {
            $venta = $this->ventaService->procesarVenta($validated, auth()->user());
            
            return redirect()
                ->route('ventas.show', $venta->id)
                ->with('success', "Venta {$venta->numero_secuencial} procesada exitosamente");
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $motivo = request('motivo') ?? 'Anulación solicitada';
            $venta = $this->ventaService->anularVenta($id, $motivo, auth()->user());
            
            return redirect()
                ->route('ventas.index')
                ->with('success', "Venta {$venta->numero_secuencial} anulada exitosamente");
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

## Mejoras Futuras

1. **Descuentos y Promociones**
   - Sistema de cupones
   - Descuentos por cantidad
   - Precios especiales por cliente

2. **Facturación Electrónica**
   - Integración con SRI Ecuador
   - Generación de XML
   - Firma digital

3. **Notificaciones**
   - Email de confirmación
   - SMS al cliente
   - Alertas de stock bajo

4. **Reportes Avanzados**
   - Ventas por período
   - Productos más vendidos
   - Análisis de rentabilidad

5. **Métodos de Pago Adicionales**
   - Pagos en cuotas
   - Múltiples formas de pago en una venta
   - Integración con pasarelas de pago
