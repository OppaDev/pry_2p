<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Producto extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'codigo',
        'nombre',
        'marca',
        'precio',
        'stock_actual',
        'estado',
    ];
    
    protected $fillable = [
        'codigo',
        'nombre',
        'marca',
        'presentacion',
        'capacidad',
        'volumen_ml',
        'precio',
        'stock_actual',
        'stock_minimo',
        'estado',
        'descripcion',
        'categoria_id',
    ];
    
    protected $casts = [
        'precio' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'volumen_ml' => 'integer',
    ];
    
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    /**
     * Custom audit comment attribute.
     *
     * @var string
     */
    public $auditComment;

    /**
     * Generate audit tags.
     *
     * @return array
     */
    public function generateTags(): array
    {
        $tags = [];
        
        if ($this->auditComment) {
            $tags[] = 'motivo:' . $this->auditComment;
        }
        
        return $tags;
    }

    /**
     * Transform the audit data.
     *
     * @param array $data
     * @return array
     */
    public function transformAudit(array $data): array
    {
        if ($this->auditComment) {
            // Obtener tags actuales o crear array vacío
            $currentTags = $data['tags'] ?? [];
            
            // Si es string, decodificar
            if (is_string($currentTags)) {
                $currentTags = json_decode($currentTags, true) ?? [];
            }
            
            // Asegurar que es array
            if (!is_array($currentTags)) {
                $currentTags = [];
            }
            
            // Agregar el motivo
            $currentTags[] = 'motivo:' . $this->auditComment;
            
            // Convertir a JSON string para PostgreSQL
            $data['tags'] = json_encode($currentTags);
        }
        
        return $data;
    }
    
    // ==================== RELACIONES ====================
    
    /**
     * Categoría del producto
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    
    /**
     * Detalles de venta de este producto
     */
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }
    
    /**
     * Movimientos de inventario de este producto
     */
    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Actualizar el precio del producto
     */
    public function actualizarPrecio(float $nuevoPrecio): bool
    {
        $this->precio = $nuevoPrecio;
        return $this->save();
    }
    
    /**
     * Actualizar el estado del producto
     */
    public function actualizarEstado(string $nuevoEstado): bool
    {
        $this->estado = $nuevoEstado;
        return $this->save();
    }
    
    /**
     * Consultar el stock actual
     */
    public function consultarStock(): int
    {
        return $this->stock_actual;
    }
    
    /**
     * Verificar si está en bajo stock
     */
    public function estaEnBajoStock(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }
    
    /**
     * Verificar si tiene stock suficiente
     */
    public function tieneStock(int $cantidad): bool
    {
        return $this->stock_actual >= $cantidad;
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para filtrar solo productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
    
    /**
     * Scope para filtrar productos con bajo stock
     */
    public function scopeBajoStock($query)
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }
    
    /**
     * Scope para filtrar productos con stock disponible
     */
    public function scopeConStock($query)
    {
        return $query->where('stock_actual', '>', 0);
    }

}
