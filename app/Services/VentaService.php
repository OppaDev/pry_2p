<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class VentaService
{
    /**
     * Procesar una nueva venta
     * 
     * @param array $datos Datos de la venta (cliente_id, items[], metodo_pago, observaciones)
     * @param User $vendedor Usuario vendedor
     * @return Venta
     * @throws Exception
     */
    public function procesarVenta(array $datos, User $vendedor): Venta
    {
        // Validaciones iniciales
        $this->validarDatosVenta($datos);
        
        DB::beginTransaction();
        
        try {
            // 1. Validar que el cliente sea mayor de edad
            $cliente = Cliente::findOrFail($datos['cliente_id']);
            $this->validarEdadCliente($cliente);
            
            // 2. Validar stock de todos los productos antes de procesar
            $this->validarStockProductos($datos['items']);
            
            // 3. Generar número secuencial de venta
            $numeroSecuencial = $this->generarNumeroSecuencial();
            
            // 4. Crear registro de venta
            $venta = Venta::create([
                'numero_secuencial' => $numeroSecuencial,
                'cliente_id' => $cliente->id,
                'vendedor_id' => $vendedor->id,
                'fecha' => now(),
                'subtotal' => 0,
                'impuestos' => 0,
                'total' => 0,
                'estado' => 'completada',
                'metodo_pago' => $datos['metodo_pago'] ?? 'efectivo',
                'observaciones' => $datos['observaciones'] ?? null,
                'edad_verificada' => true,
            ]);
            
            // 5. Procesar cada ítem de la venta
            $subtotal = 0;
            
            foreach ($datos['items'] as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                $cantidad = (int) $item['cantidad'];
                $precioUnitario = isset($item['precio_unitario']) 
                    ? (float) $item['precio_unitario'] 
                    : $producto->precio;
                
                // Crear detalle de venta
                $detalle = DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal_item' => $cantidad * $precioUnitario,
                ]);
                
                $subtotal += $detalle->subtotal_item;
                
                // Actualizar stock del producto
                $producto->stock_actual -= $cantidad;
                $producto->save();
                
                // Registrar movimiento de inventario
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'fecha' => now(),
                    'cantidad' => -$cantidad,
                    'stock_anterior' => $producto->stock_actual + $cantidad,
                    'stock_nuevo' => $producto->stock_actual,
                    'descripcion' => "Venta #{$numeroSecuencial} - {$producto->nombre}",
                    'responsable_id' => $vendedor->id,
                    'referencia_tipo' => 'venta',
                    'referencia_id' => $venta->id,
                ]);
            }
            
            // 6. Calcular impuestos y total (IVA 15%)
            $impuestos = round($subtotal * 0.15, 2);
            $total = $subtotal + $impuestos;
            
            // 7. Actualizar totales de la venta
            $venta->update([
                'subtotal' => $subtotal,
                'impuestos' => $impuestos,
                'total' => $total,
            ]);
            
            // 8. Log de éxito
            Log::info('Venta procesada exitosamente', [
                'venta_id' => $venta->id,
                'numero_secuencial' => $numeroSecuencial,
                'total' => $total,
                'vendedor_id' => $vendedor->id,
            ]);
            
            DB::commit();
            
            return $venta->load(['cliente', 'vendedor', 'detalles.producto']);
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error al procesar venta', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datos' => $datos,
            ]);
            
            throw new Exception('Error al procesar la venta: ' . $e->getMessage());
        }
    }
    
    /**
     * Anular una venta existente
     * 
     * @param int $ventaId ID de la venta
     * @param string $motivo Motivo de anulación
     * @param User $usuario Usuario responsable
     * @return Venta
     * @throws Exception
     */
    public function anularVenta(int $ventaId, string $motivo, User $usuario): Venta
    {
        DB::beginTransaction();
        
        try {
            $venta = Venta::with('detalles.producto')->findOrFail($ventaId);
            
            // Validar que la venta se pueda anular
            if ($venta->estado === 'anulada') {
                throw new Exception('La venta ya está anulada.');
            }
            
            // Restaurar stock de cada producto
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
                
                // Registrar movimiento de devolución
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'ingreso',
                    'fecha' => now(),
                    'cantidad' => $detalle->cantidad,
                    'stock_anterior' => $producto->stock_actual - $detalle->cantidad,
                    'stock_nuevo' => $producto->stock_actual,
                    'descripcion' => "Anulación venta #{$venta->numero_secuencial} - {$motivo}",
                    'responsable_id' => $usuario->id,
                    'referencia_tipo' => 'anulacion_venta',
                    'referencia_id' => $venta->id,
                ]);
            }
            
            // Anular la venta
            $venta->auditComment = $motivo;
            $venta->estado = 'anulada';
            $venta->save();
            
            Log::info('Venta anulada', [
                'venta_id' => $venta->id,
                'motivo' => $motivo,
                'usuario_id' => $usuario->id,
            ]);
            
            DB::commit();
            
            return $venta;
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error al anular venta', [
                'venta_id' => $ventaId,
                'error' => $e->getMessage(),
            ]);
            
            throw new Exception('Error al anular la venta: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener estadísticas de ventas
     * 
     * @param array $filtros Filtros opcionales (fecha_inicio, fecha_fin, vendedor_id)
     * @return array
     */
    public function obtenerEstadisticas(array $filtros = []): array
    {
        $query = Venta::completadas();
        
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
        
        $ventas = $query->get();
        
        return [
            'total_ventas' => $ventas->count(),
            'total_ingresos' => $ventas->sum('total'),
            'promedio_venta' => $ventas->avg('total'),
            'venta_mayor' => $ventas->max('total'),
            'venta_menor' => $ventas->min('total'),
            'total_impuestos' => $ventas->sum('impuestos'),
        ];
    }
    
    /**
     * Obtener detalle completo de una venta
     * 
     * @param int $ventaId
     * @return Venta
     */
    public function obtenerDetalleVenta(int $ventaId): Venta
    {
        return Venta::with([
            'cliente',
            'vendedor',
            'detalles.producto.categoria',
            'factura'
        ])->findOrFail($ventaId);
    }
    
    /**
     * Buscar ventas con filtros
     * 
     * @param array $filtros
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function buscarVentas(array $filtros = [])
    {
        $query = Venta::with(['cliente', 'vendedor']);
        
        if (isset($filtros['numero_secuencial'])) {
            $query->where('numero_secuencial', 'like', '%' . $filtros['numero_secuencial'] . '%');
        }
        
        if (isset($filtros['cliente_id'])) {
            $query->where('cliente_id', $filtros['cliente_id']);
        }
        
        if (isset($filtros['vendedor_id'])) {
            $query->where('vendedor_id', $filtros['vendedor_id']);
        }
        
        if (isset($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }
        
        if (isset($filtros['fecha_inicio'])) {
            $query->whereDate('fecha', '>=', $filtros['fecha_inicio']);
        }
        
        if (isset($filtros['fecha_fin'])) {
            $query->whereDate('fecha', '<=', $filtros['fecha_fin']);
        }
        
        if (isset($filtros['metodo_pago'])) {
            $query->where('metodo_pago', $filtros['metodo_pago']);
        }
        
        return $query->orderBy('fecha', 'desc')->get();
    }
    
    // ==================== MÉTODOS PRIVADOS DE VALIDACIÓN ====================
    
    /**
     * Validar datos básicos de la venta
     * 
     * @param array $datos
     * @throws Exception
     */
    private function validarDatosVenta(array $datos): void
    {
        if (empty($datos['cliente_id'])) {
            throw new Exception('El ID del cliente es requerido.');
        }
        
        if (empty($datos['items']) || !is_array($datos['items'])) {
            throw new Exception('Debe incluir al menos un producto en la venta.');
        }
        
        foreach ($datos['items'] as $item) {
            if (empty($item['producto_id'])) {
                throw new Exception('Cada ítem debe tener un ID de producto.');
            }
            
            if (empty($item['cantidad']) || $item['cantidad'] <= 0) {
                throw new Exception('La cantidad debe ser mayor a cero.');
            }
        }
    }
    
    /**
     * Validar que el cliente sea mayor de edad (18+)
     * 
     * @param Cliente $cliente
     * @throws Exception
     */
    private function validarEdadCliente(Cliente $cliente): void
    {
        if (!$cliente->es_mayor_edad) {
            throw new Exception(
                "El cliente {$cliente->nombre_completo} es menor de edad ({$cliente->edad} años). " .
                "No se permite la venta de bebidas alcohólicas a menores de 18 años."
            );
        }
    }
    
    /**
     * Validar que todos los productos tengan stock suficiente
     * 
     * @param array $items
     * @throws Exception
     */
    private function validarStockProductos(array $items): void
    {
        foreach ($items as $item) {
            $producto = Producto::findOrFail($item['producto_id']);
            $cantidad = (int) $item['cantidad'];
            
            // Verificar estado del producto
            if ($producto->estado !== 'activo') {
                throw new Exception(
                    "El producto '{$producto->nombre}' no está activo y no puede ser vendido."
                );
            }
            
            // Verificar stock disponible
            if (!$producto->tieneStock($cantidad)) {
                throw new Exception(
                    "Stock insuficiente para el producto '{$producto->nombre}'. " .
                    "Disponible: {$producto->stock_actual}, Solicitado: {$cantidad}"
                );
            }
        }
    }
    
    /**
     * Generar número secuencial único para la venta
     * Formato: VTA-YYYYMMDD-XXXX
     * 
     * @return string
     */
    private function generarNumeroSecuencial(): string
    {
        $fecha = now()->format('Ymd');
        $ultimaVenta = Venta::whereDate('fecha', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $secuencia = $ultimaVenta ? 
            ((int) substr($ultimaVenta->numero_secuencial, -4)) + 1 : 
            1;
        
        return sprintf('VTA-%s-%04d', $fecha, $secuencia);
    }
    
    /**
     * Calcular totales de venta
     * 
     * @param array $items
     * @return array ['subtotal' => float, 'impuestos' => float, 'total' => float]
     */
    public function calcularTotales(array $items): array
    {
        $subtotal = 0;
        
        foreach ($items as $item) {
            $producto = Producto::find($item['producto_id']);
            if ($producto) {
                $precioUnitario = isset($item['precio_unitario']) 
                    ? (float) $item['precio_unitario'] 
                    : $producto->precio;
                $subtotal += $precioUnitario * (int) $item['cantidad'];
            }
        }
        
        $impuestos = round($subtotal * 0.15, 2);
        $total = $subtotal + $impuestos;
        
        return [
            'subtotal' => round($subtotal, 2),
            'impuestos' => $impuestos,
            'total' => round($total, 2),
        ];
    }
    
    /**
     * Verificar disponibilidad de productos
     * 
     * @param array $items
     * @return array ['disponible' => bool, 'errores' => array]
     */
    public function verificarDisponibilidad(array $items): array
    {
        $errores = [];
        
        foreach ($items as $item) {
            $producto = Producto::find($item['producto_id']);
            
            if (!$producto) {
                $errores[] = "Producto con ID {$item['producto_id']} no encontrado.";
                continue;
            }
            
            if ($producto->estado !== 'activo') {
                $errores[] = "El producto '{$producto->nombre}' no está activo.";
            }
            
            if (!$producto->tieneStock((int) $item['cantidad'])) {
                $errores[] = "Stock insuficiente para '{$producto->nombre}'. " .
                           "Disponible: {$producto->stock_actual}";
            }
        }
        
        return [
            'disponible' => empty($errores),
            'errores' => $errores,
        ];
    }
}
