<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

class Cliente extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'tipo_identificacion',
        'identificacion',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'correo',
        'estado',
    ];
    
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];
    
    protected $auditInclude = [
        'identificacion',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
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
    
    // ==================== ATRIBUTOS COMPUTADOS ====================
    
    /**
     * Calcula la edad del cliente
     */
    public function getEdadAttribute(): int
    {
        return Carbon::parse($this->fecha_nacimiento)->age;
    }
    
    /**
     * Verifica si el cliente es mayor de edad (18 años)
     */
    public function getEsMayorEdadAttribute(): bool
    {
        return $this->edad >= 18;
    }
    
    /**
     * Retorna el nombre completo del cliente
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
    
    // ==================== RELACIONES ====================
    
    /**
     * Ventas realizadas por el cliente
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para filtrar solo clientes mayores de edad
     */
    public function scopeMayoresDeEdad($query)
    {
        return $query->whereRaw('EXTRACT(YEAR FROM AGE(fecha_nacimiento)) >= 18');
    }
    
    /**
     * Scope para filtrar solo clientes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
    
    /**
     * Scope para buscar por identificación
     */
    public function scopePorIdentificacion($query, $identificacion)
    {
        return $query->where('identificacion', $identificacion);
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

