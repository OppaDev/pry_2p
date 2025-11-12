<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MovimientoInventario extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'movimientos_inventario';
    
    protected $fillable = [
        'producto_id',
        'tipo',
        'fecha',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'descripcion',
        'responsable_id',
        'referencia_tipo',
        'referencia_id',
    ];
    
    protected $casts = [
        'fecha' => 'datetime',
        'cantidad' => 'integer',
        'stock_anterior' => 'integer',
        'stock_nuevo' => 'integer',
    ];
    
    protected $auditInclude = [
        'producto_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
    ];
    
    protected $auditEvents = [
        'created',
    ];
    
    // ==================== RELACIONES ====================
    
    /**
     * Producto al que pertenece este movimiento
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    /**
     * Usuario responsable del movimiento
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    
    // ==================== MÉTODOS ESTÁTICOS DE NEGOCIO ====================
    
    /**
     * Registrar un ingreso de mercadería
     */
    public static function registrarIngreso(
        Producto $producto, 
        int $cantidad, 
        User $responsable, 
        ?string $descripcion = null
    ): self {
        return self::create([
            'producto_id' => $producto->id,
            'tipo' => 'ingreso',
            'fecha' => now(),
            'cantidad' => $cantidad,
            'stock_anterior' => $producto->stock_actual,
            'stock_nuevo' => $producto->stock_actual + $cantidad,
            'descripcion' => $descripcion ?? 'Ingreso de mercadería',
            'responsable_id' => $responsable->id,
        ]);
    }
    
    /**
     * Registrar una salida de mercadería
     */
    public static function registrarSalida(
        Producto $producto, 
        int $cantidad, 
        User $responsable, 
        ?string $descripcion = null,
        ?string $refTipo = null,
        ?int $refId = null
    ): self {
        return self::create([
            'producto_id' => $producto->id,
            'tipo' => 'salida',
            'fecha' => now(),
            'cantidad' => -$cantidad,
            'stock_anterior' => $producto->stock_actual,
            'stock_nuevo' => $producto->stock_actual - $cantidad,
            'descripcion' => $descripcion ?? 'Salida de mercadería',
            'responsable_id' => $responsable->id,
            'referencia_tipo' => $refTipo,
            'referencia_id' => $refId,
        ]);
    }
    
    /**
     * Registrar un ajuste de inventario
     */
    public static function registrarAjuste(
        Producto $producto, 
        int $nuevoStock, 
        User $responsable, 
        string $descripcion
    ): self {
        $diferencia = $nuevoStock - $producto->stock_actual;
        
        return self::create([
            'producto_id' => $producto->id,
            'tipo' => 'ajuste',
            'fecha' => now(),
            'cantidad' => $diferencia,
            'stock_anterior' => $producto->stock_actual,
            'stock_nuevo' => $nuevoStock,
            'descripcion' => $descripcion,
            'responsable_id' => $responsable->id,
        ]);
    }
    
    /**
     * Consultar todos los movimientos
     */
    public static function consultarMovimientos()
    {
        return self::with(['producto', 'responsable'])
            ->orderBy('fecha', 'desc')
            ->get();
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para filtrar ingresos
     */
    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }
    
    /**
     * Scope para filtrar salidas
     */
    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'salida');
    }
    
    /**
     * Scope para filtrar ajustes
     */
    public function scopeAjustes($query)
    {
        return $query->where('tipo', 'ajuste');
    }
    
    /**
     * Scope para filtrar por producto
     */
    public function scopePorProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }
}
