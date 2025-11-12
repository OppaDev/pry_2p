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
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Generar la factura electrónica (XML)
     * Este método será implementado en el servicio de facturación
     */
    public function generarFacturaElectronica(): string
    {
        // Será implementado con el servicio de facturación
        return "XML generado";
    }
    
    /**
     * Enviar factura al SRI
     * Este método será implementado en el servicio de facturación
     */
    public function enviarSRI(): bool
    {
        // Será implementado con el servicio de facturación
        return true;
    }
    
    /**
     * Descargar factura en PDF (RIDE)
     * Este método será implementado en el servicio de facturación
     */
    public function descargarFacturaPDF(): string
    {
        // Será implementado con el servicio de facturación
        return "path/to/pdf";
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
