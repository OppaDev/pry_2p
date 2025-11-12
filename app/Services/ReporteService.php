<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteService
{
    /**
     * Generar reporte de ventas por período
     * 
     * @param array $filtros
     * @return array
     */
    public function reporteVentas(array $filtros = []): array
    {
        $query = Venta::with(['cliente', 'vendedor', 'detalles.producto'])
            ->completadas();
        
        // Aplicar filtros
        if (isset($filtros['fecha_inicio'])) {
            $query->whereDate('fecha', '>=', $filtros['fecha_inicio']);
        }
        
        if (isset($filtros['fecha_fin'])) {
            $query->whereDate('fecha', '<=', $filtros['fecha_fin']);
        }
        
        if (isset($filtros['vendedor_id'])) {
            $query->where('vendedor_id', $filtros['vendedor_id']);
        }
        
        if (isset($filtros['cliente_id'])) {
            $query->where('cliente_id', $filtros['cliente_id']);
        }
        
        if (isset($filtros['metodo_pago'])) {
            $query->where('metodo_pago', $filtros['metodo_pago']);
        }
        
        $ventas = $query->orderBy('fecha', 'desc')->get();
        
        // Calcular estadísticas
        $estadisticas = [
            'total_ventas' => $ventas->count(),
            'total_ingresos' => $ventas->sum('total'),
            'total_subtotal' => $ventas->sum('subtotal'),
            'total_impuestos' => $ventas->sum('impuestos'),
            'promedio_venta' => $ventas->avg('total'),
            'venta_mayor' => $ventas->max('total'),
            'venta_menor' => $ventas->min('total'),
        ];
        
        return [
            'ventas' => $ventas,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de inventario
     * 
     * @param array $filtros
     * @return array
     */
    public function reporteInventario(array $filtros = []): array
    {
        $query = Producto::with('categoria');
        
        // Aplicar filtros
        if (isset($filtros['categoria_id'])) {
            $query->where('categoria_id', $filtros['categoria_id']);
        }
        
        if (isset($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        } else {
            $query->activos();
        }
        
        if (isset($filtros['bajo_stock']) && $filtros['bajo_stock']) {
            $query->bajoStock();
        }
        
        $productos = $query->orderBy('nombre')->get();
        
        // Calcular estadísticas
        $estadisticas = [
            'total_productos' => $productos->count(),
            'productos_bajo_stock' => $productos->filter->estaEnBajoStock()->count(),
            'valor_total_inventario' => $productos->sum(function($p) {
                return $p->stock_actual * $p->precio;
            }),
            'stock_total' => $productos->sum('stock_actual'),
        ];
        
        return [
            'productos' => $productos,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de productos más vendidos
     * 
     * @param int $limite
     * @param array $filtros
     * @return array
     */
    public function reporteProductosMasVendidos(int $limite = 10, array $filtros = []): array
    {
        $query = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->where('ventas.estado', 'completada')
            ->select(
                'productos.id',
                'productos.codigo',
                'productos.nombre',
                'productos.marca',
                'productos.precio',
                'categorias.nombre as categoria',
                DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_ventas.subtotal_item) as total_ingresos'),
                DB::raw('COUNT(DISTINCT ventas.id) as numero_ventas')
            );
        
        // Aplicar filtros de fecha
        if (isset($filtros['fecha_inicio'])) {
            $query->whereDate('ventas.fecha', '>=', $filtros['fecha_inicio']);
        }
        
        if (isset($filtros['fecha_fin'])) {
            $query->whereDate('ventas.fecha', '<=', $filtros['fecha_fin']);
        }
        
        if (isset($filtros['categoria_id'])) {
            $query->where('productos.categoria_id', $filtros['categoria_id']);
        }
        
        $productos = $query->groupBy(
                'productos.id',
                'productos.codigo',
                'productos.nombre',
                'productos.marca',
                'productos.precio',
                'categorias.nombre'
            )
            ->orderBy('total_vendido', 'desc')
            ->limit($limite)
            ->get();
        
        return [
            'productos' => $productos,
            'limite' => $limite,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de movimientos de inventario
     * 
     * @param array $filtros
     * @return array
     */
    public function reporteMovimientosInventario(array $filtros = []): array
    {
        $query = MovimientoInventario::with(['producto.categoria', 'responsable']);
        
        // Aplicar filtros
        if (isset($filtros['fecha_inicio'])) {
            $query->whereDate('fecha', '>=', $filtros['fecha_inicio']);
        }
        
        if (isset($filtros['fecha_fin'])) {
            $query->whereDate('fecha', '<=', $filtros['fecha_fin']);
        }
        
        if (isset($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }
        
        if (isset($filtros['producto_id'])) {
            $query->where('producto_id', $filtros['producto_id']);
        }
        
        if (isset($filtros['responsable_id'])) {
            $query->where('responsable_id', $filtros['responsable_id']);
        }
        
        $movimientos = $query->orderBy('fecha', 'desc')->get();
        
        // Calcular estadísticas por tipo
        $estadisticas = [
            'total_movimientos' => $movimientos->count(),
            'ingresos' => [
                'cantidad' => $movimientos->where('tipo', 'ingreso')->count(),
                'total_unidades' => $movimientos->where('tipo', 'ingreso')->sum('cantidad'),
            ],
            'salidas' => [
                'cantidad' => $movimientos->where('tipo', 'salida')->count(),
                'total_unidades' => abs($movimientos->where('tipo', 'salida')->sum('cantidad')),
            ],
            'ajustes' => [
                'cantidad' => $movimientos->where('tipo', 'ajuste')->count(),
                'total_unidades' => $movimientos->where('tipo', 'ajuste')->sum('cantidad'),
            ],
        ];
        
        return [
            'movimientos' => $movimientos,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de clientes
     * 
     * @param array $filtros
     * @return array
     */
    public function reporteClientes(array $filtros = []): array
    {
        $query = Cliente::withCount(['ventas' => function($q) {
            $q->completadas();
        }])
        ->with(['ventas' => function($q) {
            $q->completadas();
        }]);
        
        // Aplicar filtros
        if (isset($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        } else {
            $query->activos();
        }
        
        $clientes = $query->orderBy('ventas_count', 'desc')->get();
        
        // Agregar total gastado por cliente
        $clientes->each(function($cliente) {
            $cliente->total_gastado = $cliente->ventas->sum('total');
            $cliente->promedio_compra = $cliente->ventas_count > 0 
                ? $cliente->total_gastado / $cliente->ventas_count 
                : 0;
        });
        
        // Calcular estadísticas
        $estadisticas = [
            'total_clientes' => $clientes->count(),
            'clientes_con_compras' => $clientes->filter(function($c) {
                return $c->ventas_count > 0;
            })->count(),
            'clientes_sin_compras' => $clientes->filter(function($c) {
                return $c->ventas_count == 0;
            })->count(),
            'total_ventas_generadas' => $clientes->sum('total_gastado'),
            'promedio_gasto_cliente' => $clientes->avg('total_gastado'),
        ];
        
        return [
            'clientes' => $clientes,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de ventas por vendedor
     * 
     * @param array $filtros
     * @return array
     */
    public function reporteVentasPorVendedor(array $filtros = []): array
    {
        $query = DB::table('ventas')
            ->join('users', 'ventas.vendedor_id', '=', 'users.id')
            ->where('ventas.estado', 'completada')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(ventas.id) as total_ventas'),
                DB::raw('SUM(ventas.total) as total_ingresos'),
                DB::raw('AVG(ventas.total) as promedio_venta'),
                DB::raw('MAX(ventas.total) as venta_mayor'),
                DB::raw('MIN(ventas.total) as venta_menor')
            );
        
        // Aplicar filtros de fecha
        if (isset($filtros['fecha_inicio'])) {
            $query->whereDate('ventas.fecha', '>=', $filtros['fecha_inicio']);
        }
        
        if (isset($filtros['fecha_fin'])) {
            $query->whereDate('ventas.fecha', '<=', $filtros['fecha_fin']);
        }
        
        $vendedores = $query->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_ingresos', 'desc')
            ->get();
        
        // Calcular estadísticas generales
        $estadisticas = [
            'total_vendedores' => $vendedores->count(),
            'total_ventas' => $vendedores->sum('total_ventas'),
            'total_ingresos' => $vendedores->sum('total_ingresos'),
            'promedio_por_vendedor' => $vendedores->avg('total_ingresos'),
        ];
        
        return [
            'vendedores' => $vendedores,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de ventas diarias/mensuales
     * 
     * @param string $periodo 'diario' o 'mensual'
     * @param array $filtros
     * @return array
     */
    public function reporteVentasPorPeriodo(string $periodo = 'diario', array $filtros = []): array
    {
        $fechaInicio = isset($filtros['fecha_inicio']) 
            ? Carbon::parse($filtros['fecha_inicio']) 
            : now()->subMonth();
            
        $fechaFin = isset($filtros['fecha_fin']) 
            ? Carbon::parse($filtros['fecha_fin']) 
            : now();
        
        $dateFormat = $periodo === 'mensual' ? '%Y-%m' : '%Y-%m-%d';
        $dateFormatLabel = $periodo === 'mensual' ? 'Y-m' : 'Y-m-d';
        
        $ventas = DB::table('ventas')
            ->where('estado', 'completada')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw("TO_CHAR(fecha, '{$dateFormat}') as periodo"),
                DB::raw('COUNT(id) as total_ventas'),
                DB::raw('SUM(total) as total_ingresos'),
                DB::raw('SUM(subtotal) as total_subtotal'),
                DB::raw('SUM(impuestos) as total_impuestos'),
                DB::raw('AVG(total) as promedio_venta')
            )
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();
        
        // Calcular totales
        $estadisticas = [
            'periodo_tipo' => $periodo,
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'total_periodos' => $ventas->count(),
            'total_ventas' => $ventas->sum('total_ventas'),
            'total_ingresos' => $ventas->sum('total_ingresos'),
            'promedio_diario' => $ventas->avg('total_ingresos'),
        ];
        
        return [
            'ventas_periodo' => $ventas,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Generar reporte de productos con bajo stock
     * 
     * @return array
     */
    public function reporteBajoStock(): array
    {
        $productos = Producto::with('categoria')
            ->activos()
            ->bajoStock()
            ->orderBy('stock_actual')
            ->get();
        
        // Calcular información adicional
        $productos->each(function($producto) {
            $producto->unidades_faltantes = max(0, $producto->stock_minimo - $producto->stock_actual);
            $producto->valor_faltante = $producto->unidades_faltantes * $producto->precio;
        });
        
        $estadisticas = [
            'total_productos' => $productos->count(),
            'total_unidades_faltantes' => $productos->sum('unidades_faltantes'),
            'valor_total_reposicion' => $productos->sum('valor_faltante'),
        ];
        
        return [
            'productos' => $productos,
            'estadisticas' => $estadisticas,
            'fecha_generacion' => now(),
        ];
    }
    
    /**
     * Obtener datos para dashboard principal
     * 
     * @return array
     */
    public function datosDashboard(): array
    {
        $hoy = now();
        $inicioMes = $hoy->copy()->startOfMonth();
        
        return [
            // Ventas de hoy
            'ventas_hoy' => [
                'total' => Venta::delDia()->completadas()->count(),
                'ingresos' => Venta::delDia()->completadas()->sum('total'),
            ],
            
            // Ventas del mes
            'ventas_mes' => [
                'total' => Venta::whereBetween('fecha', [$inicioMes, $hoy])
                    ->completadas()
                    ->count(),
                'ingresos' => Venta::whereBetween('fecha', [$inicioMes, $hoy])
                    ->completadas()
                    ->sum('total'),
            ],
            
            // Productos con bajo stock
            'productos_bajo_stock' => Producto::activos()->bajoStock()->count(),
            
            // Total productos activos
            'total_productos' => Producto::activos()->count(),
            
            // Total clientes activos
            'total_clientes' => Cliente::activos()->count(),
            
            // Últimas 5 ventas
            'ultimas_ventas' => Venta::with(['cliente', 'vendedor'])
                ->completadas()
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get(),
            
            // Top 5 productos más vendidos (este mes)
            'top_productos' => DB::table('detalle_ventas')
                ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
                ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
                ->whereBetween('ventas.fecha', [$inicioMes, $hoy])
                ->where('ventas.estado', 'completada')
                ->select(
                    'productos.nombre',
                    DB::raw('SUM(detalle_ventas.cantidad) as total_vendido')
                )
                ->groupBy('productos.id', 'productos.nombre')
                ->orderBy('total_vendido', 'desc')
                ->limit(5)
                ->get(),
        ];
    }
}
