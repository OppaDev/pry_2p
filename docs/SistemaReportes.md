# Sistema de Reportes - Inferno Club

## Descripción General
Sistema completo de generación de reportes con exportación a múltiples formatos (Excel/CSV y PDF) para análisis de ventas, inventario, clientes y desempeño del negocio.

## Características Principales

### ✅ Reportes Implementados (7)

1. **Reporte de Ventas**
   - Detalle completo de ventas por período
   - Filtros: fecha_inicio, fecha_fin, vendedor_id, cliente_id, metodo_pago
   - Estadísticas: total ventas, ingresos, promedio, mayor/menor
   - Exportación: CSV, PDF, Vista Web

2. **Reporte de Inventario**
   - Estado actual del stock de productos
   - Filtros: categoria_id, estado, bajo_stock
   - Estadísticas: total productos, bajo stock, valor total, stock total
   - Exportación: CSV, PDF, Vista Web

3. **Productos Más Vendidos**
   - Ranking de productos por cantidad vendida
   - Filtros: fecha_inicio, fecha_fin, categoria_id, limite (top N)
   - Métricas: total vendido, ingresos, número de ventas
   - Exportación: CSV, PDF, Vista Web

4. **Movimientos de Inventario**
   - Historial de entrada/salida/ajustes
   - Filtros: fecha_inicio, fecha_fin, tipo, producto_id, responsable_id
   - Estadísticas por tipo: cantidad y unidades totales
   - Exportación: CSV, PDF, Vista Web

5. **Reporte de Clientes**
   - Análisis de clientes y comportamiento de compra
   - Filtros: estado
   - Métricas: total compras, gasto total, promedio compra
   - Exportación: CSV, PDF, Vista Web

6. **Ventas por Vendedor**
   - Desempeño individual de vendedores
   - Filtros: fecha_inicio, fecha_fin
   - Métricas: ventas, ingresos, promedio, mayor/menor venta
   - Exportación: CSV, PDF, Vista Web

7. **Productos con Bajo Stock**
   - Alertas de reposición de inventario
   - Sin filtros (automático)
   - Métricas: unidades faltantes, valor de reposición
   - Exportación: CSV, PDF, Vista Web

## Arquitectura

### Services Layer
**`app/Services/ReporteService.php`** - Lógica de negocio de reportes

#### Métodos Públicos (9):

```php
reporteVentas(array $filtros): array
reporteInventario(array $filtros): array
reporteProductosMasVendidos(int $limite, array $filtros): array
reporteMovimientosInventario(array $filtros): array
reporteClientes(array $filtros): array
reporteVentasPorVendedor(array $filtros): array
reporteVentasPorPeriodo(string $periodo, array $filtros): array
reporteBajoStock(): array
datosDashboard(): array
```

### Controller Layer
**`app/Http/Controllers/ReporteController.php`** - Gestión de peticiones y exportación

#### Métodos Principales:
- `index()` - Vista principal de reportes
- `ventas(Request)` - Generar reporte de ventas
- `inventario(Request)` - Generar reporte de inventario
- `productosMasVendidos(Request)` - Top productos
- `movimientosInventario(Request)` - Historial movimientos
- `clientes(Request)` - Análisis clientes
- `ventasPorVendedor(Request)` - Desempeño vendedores
- `bajoStock(Request)` - Alertas stock

#### Exportación CSV:
Cada reporte tiene su método dedicado:
- `exportarVentasExcel()`
- `exportarInventarioExcel()`
- `exportarProductosMasVendidosExcel()`
- `exportarMovimientosExcel()`
- `exportarClientesExcel()`
- `exportarVentasPorVendedorExcel()`
- `exportarBajoStockExcel()`

#### Generadores CSV:
Métodos privados para construir archivos CSV:
- `generarCsvVentas()`
- `generarCsvInventario()`
- `generarCsvProductosMasVendidos()`
- `generarCsvMovimientos()`
- `generarCsvClientes()`
- `generarCsvVentasPorVendedor()`
- `generarCsvBajoStock()`

## Rutas

```php
Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');
    Route::get('ventas', [ReporteController::class, 'ventas'])->name('ventas');
    Route::get('inventario', [ReporteController::class, 'inventario'])->name('inventario');
    Route::get('productos-mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('productos-mas-vendidos');
    Route::get('movimientos-inventario', [ReporteController::class, 'movimientosInventario'])->name('movimientos-inventario');
    Route::get('clientes', [ReporteController::class, 'clientes'])->name('clientes');
    Route::get('ventas-por-vendedor', [ReporteController::class, 'ventasPorVendedor'])->name('ventas-por-vendedor');
    Route::get('bajo-stock', [ReporteController::class, 'bajoStock'])->name('bajo-stock');
});
```

## Uso

### Desde Vista Web

1. Navegar a `/reportes`
2. Seleccionar tipo de reporte
3. Aplicar filtros deseados
4. Elegir formato de exportación:
   - **Vista Web**: Ver en navegador
   - **Excel**: Descargar CSV
   - **PDF**: Vista imprimible

### Desde Código

```php
use App\Services\ReporteService;

$reporteService = app(ReporteService::class);

// Reporte de ventas del mes
$datos = $reporteService->reporteVentas([
    'fecha_inicio' => '2025-11-01',
    'fecha_fin' => '2025-11-30',
    'vendedor_id' => 3
]);

// Estructura de retorno
[
    'ventas' => Collection,        // Colección de ventas
    'estadisticas' => [            // Métricas calculadas
        'total_ventas' => 150,
        'total_ingresos' => 15750.50,
        'promedio_venta' => 105.00,
        'venta_mayor' => 450.00,
        'venta_menor' => 25.50
    ],
    'filtros' => [...],            // Filtros aplicados
    'fecha_generacion' => Carbon   // Timestamp generación
]
```

### Exportación Programática

```php
// En un controlador
use App\Services\ReporteService;
use Illuminate\Support\Facades\Response;

public function exportar(Request $request, ReporteService $reporteService)
{
    $datos = $reporteService->reporteInventario([
        'categoria_id' => $request->categoria_id
    ]);
    
    // Generar CSV
    $csv = $this->generarCsvInventario($datos);
    
    return Response::make($csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="inventario.csv"'
    ]);
}
```

## Estructura de Datos

### Reporte de Ventas
```php
[
    'ventas' => [
        [
            'numero_secuencial' => 'VTA-20251112-0001',
            'fecha' => '2025-11-12 14:30:00',
            'cliente' => [
                'nombre_completo' => 'Juan Pérez',
                'identificacion' => '1234567890'
            ],
            'vendedor' => [
                'name' => 'María González',
                'email' => 'maria@ejemplo.com'
            ],
            'subtotal' => 100.00,
            'impuestos' => 15.00,
            'total' => 115.00,
            'metodo_pago' => 'tarjeta',
            'estado' => 'completada'
        ]
    ],
    'estadisticas' => [...]
]
```

### Reporte de Inventario
```php
[
    'productos' => [
        [
            'codigo' => 'PROD-001',
            'nombre' => 'Whisky Black Label',
            'marca' => 'Johnnie Walker',
            'categoria' => ['nombre' => 'Whisky'],
            'presentacion' => 'botella',
            'stock_actual' => 50,
            'stock_minimo' => 10,
            'precio' => 85.00,
            'valor_total' => 4250.00,
            'estado' => 'activo'
        ]
    ],
    'estadisticas' => [
        'total_productos' => 120,
        'productos_bajo_stock' => 8,
        'valor_total_inventario' => 125000.00,
        'stock_total' => 2500
    ]
]
```

### Productos Más Vendidos
```php
[
    'productos' => [
        [
            'codigo' => 'PROD-005',
            'nombre' => 'Cerveza Pilsener',
            'marca' => 'Pilsener',
            'categoria' => 'Cerveza',
            'total_vendido' => 450,        // Unidades vendidas
            'total_ingresos' => 2250.00,   // $ generados
            'numero_ventas' => 85           // Cantidad de ventas
        ]
    ],
    'limite' => 10
]
```

### Movimientos de Inventario
```php
[
    'movimientos' => [
        [
            'fecha' => '2025-11-12 10:15:00',
            'producto' => ['nombre' => 'Ron Bacardi'],
            'tipo' => 'salida',            // ingreso/salida/ajuste
            'cantidad' => -5,
            'stock_anterior' => 50,
            'stock_nuevo' => 45,
            'responsable' => ['name' => 'Pedro López'],
            'descripcion' => 'Venta #VTA-20251112-0001'
        ]
    ],
    'estadisticas' => [
        'total_movimientos' => 250,
        'ingresos' => [
            'cantidad' => 80,
            'total_unidades' => 1200
        ],
        'salidas' => [
            'cantidad' => 150,
            'total_unidades' => 800
        ],
        'ajustes' => [
            'cantidad' => 20,
            'total_unidades' => 50
        ]
    ]
]
```

## Formato CSV

### Ejemplo: Ventas
```csv
Número,Fecha,Cliente,Vendedor,Subtotal,Impuestos,Total,Método Pago,Estado
VTA-20251112-0001,2025-11-12 14:30,Juan Pérez,María González,100.00,15.00,115.00,tarjeta,completada
VTA-20251112-0002,2025-11-12 15:45,Ana Martínez,Carlos Ruiz,85.00,12.75,97.75,efectivo,completada
```

### Ejemplo: Inventario
```csv
Código,Nombre,Marca,Categoría,Presentación,Stock Actual,Stock Mínimo,Precio,Valor Total,Estado
PROD-001,Whisky Black Label,Johnnie Walker,Whisky,botella,50,10,85.00,4250.00,activo
PROD-002,Ron Bacardi,Bacardi,Ron,botella,75,15,45.00,3375.00,activo
```

## Dashboard Integration

El método `datosDashboard()` proporciona datos para widgets:

```php
$dashboard = $reporteService->datosDashboard();

// Retorna:
[
    'ventas_hoy' => [
        'total' => 15,
        'ingresos' => 1250.50
    ],
    'ventas_mes' => [
        'total' => 245,
        'ingresos' => 28750.00
    ],
    'productos_bajo_stock' => 8,
    'total_productos' => 120,
    'total_clientes' => 356,
    'ultimas_ventas' => Collection,    // Últimas 5 ventas
    'top_productos' => Collection      // Top 5 del mes
]
```

## Optimización

### Consultas Eficientes
- Uso de `DB::table()` para consultas agregadas
- Eager loading con `with()` para relaciones
- Índices en campos filtrados (fecha, estado, etc.)
- Paginación cuando sea necesario

### Caché (Futura Implementación)
```php
Cache::remember("reporte-ventas-{$hash}", 3600, function() {
    return $reporteService->reporteVentas($filtros);
});
```

## Permisos Sugeridos

```php
// Spatie Permission
'reportes.ver'              // Ver reportes
'reportes.exportar'         // Exportar a Excel/PDF
'reportes.ventas'           // Reporte de ventas
'reportes.inventario'       // Reporte de inventario
'reportes.clientes'         // Reporte de clientes
'reportes.estadisticas'     // Estadísticas avanzadas
```

## Mejoras Futuras

1. **Reportes Adicionales**
   - Reporte de rentabilidad por producto
   - Análisis de tendencias de venta
   - Forecast de demanda
   - Comparativa mensual/anual

2. **Formatos Avanzados**
   - Gráficos en PDF con Chart.js
   - Excel con fórmulas (PhpSpreadsheet)
   - Reportes programados por email

3. **Filtros Dinámicos**
   - Comparación entre períodos
   - Agrupación por categorías
   - Filtros guardados por usuario

4. **Visualizaciones**
   - Gráficos interactivos
   - Mapas de calor
   - Dashboards personalizables

## Notas Técnicas

### Por qué CSV en lugar de XLSX
- ✅ No requiere librerías externas
- ✅ Compatible con Excel, Google Sheets, LibreOffice
- ✅ Archivos más ligeros
- ✅ Procesamiento más rápido
- ✅ Fácil de generar con PHP nativo

### Migración a Excel Real (Opcional)
Si se requiere XLSX con formato:
```bash
composer require phpoffice/phpspreadsheet
```

```php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// ... construir hoja
$writer = new Xlsx($spreadsheet);
$writer->save('reporte.xlsx');
```

## Testing

```php
// Test de generación de reporte
public function test_genera_reporte_ventas()
{
    $reporteService = new ReporteService();
    
    $datos = $reporteService->reporteVentas([
        'fecha_inicio' => '2025-11-01',
        'fecha_fin' => '2025-11-30'
    ]);
    
    $this->assertArrayHasKey('ventas', $datos);
    $this->assertArrayHasKey('estadisticas', $datos);
    $this->assertIsInt($datos['estadisticas']['total_ventas']);
}
```
