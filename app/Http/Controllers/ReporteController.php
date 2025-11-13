<?php

namespace App\Http\Controllers;

use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    protected ReporteService $reporteService;
    
    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
        
        // Middleware para cada tipo de reporte según rol
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
    }
    
    /**
     * Mostrar página principal de reportes
     */
    public function index()
    {
        return view('reportes.index');
    }
    
    /**
     * Generar reporte de ventas
     */
    public function ventas(Request $request)
    {
        $filtros = $request->only(['fecha_inicio', 'fecha_fin', 'vendedor_id', 'cliente_id', 'metodo_pago']);
        $datos = $this->reporteService->reporteVentas($filtros);
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarVentasExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarVentasPdf($datos);
        }
        
        // Obtener listas para filtros
        $vendedores = \App\Models\User::select('id', 'name', 'email')->get();
        $clientes = \App\Models\Cliente::select('id', 'nombres', 'apellidos', 'identificacion')->get();
        
        return view('reportes.ventas', compact('datos', 'vendedores', 'clientes'));
    }
    
    /**
     * Generar reporte de inventario
     */
    public function inventario(Request $request)
    {
        $filtros = $request->only(['categoria_id', 'estado', 'bajo_stock']);
        $datos = $this->reporteService->reporteInventario($filtros);
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarInventarioExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarInventarioPdf($datos);
        }
        
        // Obtener categorías para filtros
        $categorias = \App\Models\Categoria::select('id', 'nombre')->get();
        
        return view('reportes.inventario', compact('datos', 'categorias'));
    }
    
    /**
     * Generar reporte de productos más vendidos
     */
    public function productosMasVendidos(Request $request)
    {
        $limite = $request->input('limite', 10);
        $filtros = $request->only(['fecha_inicio', 'fecha_fin', 'categoria_id']);
        $datos = $this->reporteService->reporteProductosMasVendidos($limite, $filtros);
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarProductosMasVendidosExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarProductosMasVendidosPdf($datos);
        }
        
        // Obtener categorías para filtros
        $categorias = \App\Models\Categoria::select('id', 'nombre')->get();
        
        return view('reportes.productos-mas-vendidos', compact('datos', 'categorias'));
    }
    
    /**
     * Generar reporte de movimientos de inventario
     */
    public function movimientosInventario(Request $request)
    {
        $filtros = $request->only(['fecha_inicio', 'fecha_fin', 'tipo', 'producto_id', 'responsable_id']);
        $datos = $this->reporteService->reporteMovimientosInventario($filtros);
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarMovimientosExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarMovimientosPdf($datos);
        }
        
        // Obtener listas para filtros
        $productos = \App\Models\Producto::select('id', 'nombre', 'codigo')->get();
        $responsables = \App\Models\User::select('id', 'name')->get();
        
        return view('reportes.movimientos-inventario', compact('datos', 'productos', 'responsables'));
    }
    
    /**
     * Generar reporte de clientes
     */
    public function clientes(Request $request)
    {
        $filtros = $request->only(['estado']);
        $datos = $this->reporteService->reporteClientes($filtros);
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarClientesExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarClientesPdf($datos);
        }
        
        return view('reportes.clientes', compact('datos'));
    }
    
    /**
     * Generar reporte de ventas por vendedor
     */
    public function ventasPorVendedor(Request $request)
    {
        $filtros = $request->only(['fecha_inicio', 'fecha_fin']);
        $datos = $this->reporteService->reporteVentasPorVendedor($filtros);
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarVentasPorVendedorExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarVentasPorVendedorPdf($datos);
        }
        
        return view('reportes.ventas-por-vendedor', compact('datos'));
    }
    
    /**
     * Generar reporte de productos con bajo stock
     */
    public function bajoStock(Request $request)
    {
        $datos = $this->reporteService->reporteBajoStock();
        
        if ($request->input('formato') === 'excel') {
            return $this->exportarBajoStockExcel($datos);
        }
        
        if ($request->input('formato') === 'pdf') {
            return $this->exportarBajoStockPdf($datos);
        }
        
        return view('reportes.bajo-stock', compact('datos'));
    }
    
    // ==================== MÉTODOS DE EXPORTACIÓN EXCEL ====================
    
    private function exportarVentasExcel(array $datos)
    {
        $csv = $this->generarCsvVentas($datos);
        return $this->descargarCsv($csv, 'reporte-ventas-' . now()->format('Y-m-d') . '.csv');
    }
    
    private function exportarInventarioExcel(array $datos)
    {
        $csv = $this->generarCsvInventario($datos);
        return $this->descargarCsv($csv, 'reporte-inventario-' . now()->format('Y-m-d') . '.csv');
    }
    
    private function exportarProductosMasVendidosExcel(array $datos)
    {
        $csv = $this->generarCsvProductosMasVendidos($datos);
        return $this->descargarCsv($csv, 'productos-mas-vendidos-' . now()->format('Y-m-d') . '.csv');
    }
    
    private function exportarMovimientosExcel(array $datos)
    {
        $csv = $this->generarCsvMovimientos($datos);
        return $this->descargarCsv($csv, 'movimientos-inventario-' . now()->format('Y-m-d') . '.csv');
    }
    
    private function exportarClientesExcel(array $datos)
    {
        $csv = $this->generarCsvClientes($datos);
        return $this->descargarCsv($csv, 'reporte-clientes-' . now()->format('Y-m-d') . '.csv');
    }
    
    private function exportarVentasPorVendedorExcel(array $datos)
    {
        $csv = $this->generarCsvVentasPorVendedor($datos);
        return $this->descargarCsv($csv, 'ventas-por-vendedor-' . now()->format('Y-m-d') . '.csv');
    }
    
    private function exportarBajoStockExcel(array $datos)
    {
        $csv = $this->generarCsvBajoStock($datos);
        return $this->descargarCsv($csv, 'productos-bajo-stock-' . now()->format('Y-m-d') . '.csv');
    }
    
    // ==================== GENERADORES CSV ====================
    
    private function generarCsvVentas(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        // Encabezados
        fputcsv($output, ['Número', 'Fecha', 'Cliente', 'Vendedor', 'Subtotal', 'Impuestos', 'Total', 'Método Pago', 'Estado']);
        
        // Datos
        foreach ($datos['ventas'] as $venta) {
            fputcsv($output, [
                $venta->numero_secuencial,
                $venta->fecha->format('Y-m-d H:i'),
                $venta->cliente->nombre_completo,
                $venta->vendedor->name,
                number_format($venta->subtotal, 2),
                number_format($venta->impuestos, 2),
                number_format($venta->total, 2),
                $venta->metodo_pago,
                $venta->estado,
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    private function generarCsvInventario(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        fputcsv($output, ['Código', 'Nombre', 'Marca', 'Categoría', 'Presentación', 'Stock Actual', 'Stock Mínimo', 'Precio', 'Valor Total', 'Estado']);
        
        foreach ($datos['productos'] as $producto) {
            fputcsv($output, [
                $producto->codigo,
                $producto->nombre,
                $producto->marca,
                $producto->categoria->nombre ?? 'N/A',
                $producto->presentacion,
                $producto->stock_actual,
                $producto->stock_minimo,
                number_format($producto->precio, 2),
                number_format($producto->stock_actual * $producto->precio, 2),
                $producto->estado,
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    private function generarCsvProductosMasVendidos(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        fputcsv($output, ['Código', 'Nombre', 'Marca', 'Categoría', 'Cantidad Vendida', 'Total Ingresos', 'Número Ventas']);
        
        foreach ($datos['productos'] as $producto) {
            fputcsv($output, [
                $producto->codigo,
                $producto->nombre,
                $producto->marca,
                $producto->categoria ?? 'N/A',
                $producto->total_vendido,
                number_format($producto->total_ingresos, 2),
                $producto->numero_ventas,
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    private function generarCsvMovimientos(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        fputcsv($output, ['Fecha', 'Producto', 'Tipo', 'Cantidad', 'Stock Anterior', 'Stock Nuevo', 'Responsable', 'Descripción']);
        
        foreach ($datos['movimientos'] as $movimiento) {
            fputcsv($output, [
                $movimiento->fecha->format('Y-m-d H:i'),
                $movimiento->producto->nombre,
                ucfirst($movimiento->tipo),
                $movimiento->cantidad,
                $movimiento->stock_anterior,
                $movimiento->stock_nuevo,
                $movimiento->responsable->name ?? 'N/A',
                $movimiento->descripcion,
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    private function generarCsvClientes(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        fputcsv($output, ['Identificación', 'Nombre Completo', 'Email', 'Teléfono', 'Total Compras', 'Total Gastado', 'Promedio Compra', 'Estado']);
        
        foreach ($datos['clientes'] as $cliente) {
            fputcsv($output, [
                $cliente->identificacion,
                $cliente->nombre_completo,
                $cliente->correo,
                $cliente->telefono,
                $cliente->ventas_count,
                number_format($cliente->total_gastado, 2),
                number_format($cliente->promedio_compra, 2),
                $cliente->estado,
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    private function generarCsvVentasPorVendedor(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        fputcsv($output, ['Vendedor', 'Email', 'Total Ventas', 'Total Ingresos', 'Promedio Venta', 'Venta Mayor', 'Venta Menor']);
        
        foreach ($datos['vendedores'] as $vendedor) {
            fputcsv($output, [
                $vendedor->name,
                $vendedor->email,
                $vendedor->total_ventas,
                number_format($vendedor->total_ingresos, 2),
                number_format($vendedor->promedio_venta, 2),
                number_format($vendedor->venta_mayor, 2),
                number_format($vendedor->venta_menor, 2),
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    private function generarCsvBajoStock(array $datos): string
    {
        $output = fopen('php://temp', 'r+');
        
        fputcsv($output, ['Código', 'Nombre', 'Marca', 'Categoría', 'Stock Actual', 'Stock Mínimo', 'Unidades Faltantes', 'Valor Faltante', 'Precio']);
        
        foreach ($datos['productos'] as $producto) {
            fputcsv($output, [
                $producto->codigo,
                $producto->nombre,
                $producto->marca,
                $producto->categoria->nombre ?? 'N/A',
                $producto->stock_actual,
                $producto->stock_minimo,
                $producto->unidades_faltantes,
                number_format($producto->valor_faltante, 2),
                number_format($producto->precio, 2),
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    // ==================== MÉTODOS PDF ====================
    
    private function exportarVentasPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.ventas', ['datos' => $datos])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'enable_php' => true
            ]);
        return $pdf->download('reporte-ventas-' . date('Y-m-d') . '.pdf');
    }
    
    private function exportarInventarioPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.inventario', ['datos' => $datos])
            ->setPaper('a4', 'landscape')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('reporte-inventario-' . date('Y-m-d') . '.pdf');
    }
    
    private function exportarProductosMasVendidosPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.productos-mas-vendidos', ['datos' => $datos])
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('productos-mas-vendidos-' . date('Y-m-d') . '.pdf');
    }
    
    private function exportarMovimientosPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.movimientos', ['datos' => $datos])
            ->setPaper('a4', 'landscape')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('movimientos-inventario-' . date('Y-m-d') . '.pdf');
    }
    
    private function exportarClientesPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.clientes', ['datos' => $datos])
            ->setPaper('a4', 'landscape')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('reporte-clientes-' . date('Y-m-d') . '.pdf');
    }
    
    private function exportarVentasPorVendedorPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.ventas-por-vendedor', ['datos' => $datos])
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('ventas-por-vendedor-' . date('Y-m-d') . '.pdf');
    }
    
    private function exportarBajoStockPdf(array $datos)
    {
        $pdf = Pdf::loadView('reportes.pdf.bajo-stock', ['datos' => $datos])
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('productos-bajo-stock-' . date('Y-m-d') . '.pdf');
    }
    
    // ==================== HELPER ====================
    
    private function descargarCsv(string $contenido, string $nombreArchivo)
    {
        return Response::make($contenido, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$nombreArchivo}\"",
        ]);
    }
}
