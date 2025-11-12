<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Categoria extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];
    
    protected $auditInclude = [
        'nombre',
        'descripcion',
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
    
    // ==================== RELACIONES (Agregación) ====================
    
    /**
     * Productos de esta categoría
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Agregar un producto a esta categoría
     */
    public function agregarProducto(Producto $producto): void
    {
        $producto->categoria_id = $this->id;
        $producto->save();
    }
    
    /**
     * Consultar productos de esta categoría
     */
    public function consultarProductos()
    {
        return $this->productos;
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para filtrar solo categorías activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
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

