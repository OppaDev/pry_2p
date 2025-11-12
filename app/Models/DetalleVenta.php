<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal_item',
    ];
    
    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal_item' => 'decimal:2',
    ];
    
    // ==================== RELACIONES ====================
    
    /**
     * Venta a la que pertenece este detalle
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    
    /**
     * Producto vendido
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Calcular el subtotal del ítem
     */
    public function calcularSubtotalItem(): float
    {
        return $this->cantidad * $this->precio_unitario;
    }
    
    // ==================== BOOT ====================
    
    /**
     * Boot del modelo para cálculo automático
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($detalle) {
            if (!$detalle->subtotal_item) {
                $detalle->subtotal_item = $detalle->calcularSubtotalItem();
            }
        });
        
        static::updating(function ($detalle) {
            $detalle->subtotal_item = $detalle->calcularSubtotalItem();
        });
    }
}
