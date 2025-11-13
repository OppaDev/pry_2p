<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Factura extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'venta_id',
        'numero_secuencial',
        'numero_autorizacion',
        'clave_acceso_sri',
        'fecha_emision',
        'fecha_autorizacion',
        'subtotal',
        'impuestos',
        'total',
        'estado_autorizacion',
        'xml_factura',
        'respuesta_sri',
    ];
    
    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_autorizacion' => 'datetime',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'total' => 'decimal:2',
        'respuesta_sri' => 'array',
    ];
    
    protected $auditInclude = [
        'numero_secuencial',
        'numero_autorizacion',
        'clave_acceso_sri',
        'estado_autorizacion',
    ];
    
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
    /**
     * Atributo para motivo de auditoría
     */
    public $auditComment;
    
    // ==================== RELACIONES ====================
    
    /**
     * Venta asociada a esta factura
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    
    /**
     * Cliente de la factura
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'venta_id', 'id')
            ->join('ventas', 'ventas.cliente_id', '=', 'clientes.id')
            ->where('ventas.id', $this->venta_id);
    }
    
    /**
     * Usuario que emitió la factura
     */
    public function usuario()
    {
        return $this->hasOneThrough(User::class, Venta::class, 'id', 'id', 'venta_id', 'usuario_id');
    }
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Verifica si la factura está autorizada
     */
    public function estaAutorizada(): bool
    {
        return $this->estado_autorizacion === 'autorizada';
    }
    
    /**
     * Verifica si la factura está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado_autorizacion === 'pendiente';
    }
    
    /**
     * Verifica si la factura está anulada
     */
    public function estaAnulada(): bool
    {
        return $this->estado_autorizacion === 'anulada';
    }
    
    /**
     * Obtener badge HTML para estado
     */
    public function getBadgeEstadoAttribute(): string
    {
        $badges = [
            'pendiente' => '<span class="badge bg-warning">Pendiente</span>',
            'autorizada' => '<span class="badge bg-success">Autorizada</span>',
            'rechazada' => '<span class="badge bg-danger">Rechazada</span>',
            'anulada' => '<span class="badge bg-secondary">Anulada</span>',
        ];
        
        return $badges[$this->estado_autorizacion] ?? '<span class="badge bg-secondary">Desconocido</span>';
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para facturas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado_autorizacion', 'pendiente');
    }
    
    /**
     * Scope para facturas autorizadas
     */
    public function scopeAutorizadas($query)
    {
        return $query->where('estado_autorizacion', 'autorizada');
    }
    
    /**
     * Scope para facturas rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado_autorizacion', 'rechazada');
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
