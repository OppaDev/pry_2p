<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Venta extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'numero_secuencial',
        'cliente_id',
        'vendedor_id',
        'fecha',
        'subtotal',
        'impuestos',
        'total',
        'estado',
        'metodo_pago',
        'observaciones',
        'edad_verificada',
    ];
    
    protected $casts = [
        'fecha' => 'datetime',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'total' => 'decimal:2',
        'edad_verificada' => 'boolean',
    ];
    
    protected $auditInclude = [
        'numero_secuencial',
        'cliente_id',
        'vendedor_id',
        'total',
        'estado',
    ];
    
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];
    
    /**
     * Atributo para motivo de auditoría
     */
    public $auditComment;
    
    // ==================== RELACIONES ====================
    
    /**
     * Cliente que realizó la compra
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    /**
     * Vendedor que procesó la venta
     */
    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }
    
    /**
     * Detalles de la venta (Composición)
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
    
    /**
     * Factura asociada a la venta (Relación 1:1)
     */
    public function factura()
    {
        return $this->hasOne(Factura::class);
    }
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Calcular el subtotal de la venta
     */
    public function calcularSubtotal(): float
    {
        return $this->detalles->sum('subtotal_item');
    }
    
    /**
     * Calcular los impuestos (IVA 15%)
     */
    public function calcularImpuestos(): float
    {
        return round($this->subtotal * 0.15, 2);
    }
    
    /**
     * Calcular el total de la venta
     */
    public function calcularTotal(): float
    {
        return $this->subtotal + $this->impuestos;
    }
    
    /**
     * Agregar un detalle a la venta
     */
    public function agregarDetalle(DetalleVenta $detalle): void
    {
        $this->detalles()->save($detalle);
        $this->recalcular();
    }
    
    /**
     * Anular la venta
     */
    public function anularVenta(string $motivo): bool
    {
        $this->auditComment = "Venta anulada: {$motivo}";
        $this->estado = 'anulada';
        return $this->save();
    }
    
    /**
     * Recalcular totales
     */
    private function recalcular(): void
    {
        $this->subtotal = $this->calcularSubtotal();
        $this->impuestos = $this->calcularImpuestos();
        $this->total = $this->calcularTotal();
        $this->save();
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para ventas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }
    
    /**
     * Scope para ventas anuladas
     */
    public function scopeAnuladas($query)
    {
        return $query->where('estado', 'anulada');
    }
    
    /**
     * Scope para ventas del día
     */
    public function scopeDelDia($query)
    {
        return $query->whereDate('fecha', today());
    }
    
    /**
     * Scope para ventas por vendedor
     */
    public function scopePorVendedor($query, $vendedorId)
    {
        return $query->where('vendedor_id', $vendedorId);
    }
    
    // ==================== MÉTODOS DE AUDITORÍA ====================
    
    public function generateTags(): array
    {
        $tags = [];
        if ($this->auditComment) {
            $tags[] = 'motivo:' . $this->auditComment;
        }
        return $tags;
    }
    
    public function transformAudit(array $data): array
    {
        if ($this->auditComment) {
            $currentTags = $data['tags'] ?? [];
            if (is_string($currentTags)) {
                $currentTags = json_decode($currentTags, true) ?? [];
            }
            if (!is_array($currentTags)) {
                $currentTags = [];
            }
            $currentTags[] = 'motivo:' . $this->auditComment;
            $data['tags'] = json_encode($currentTags);
        }
        return $data;
    }
}
