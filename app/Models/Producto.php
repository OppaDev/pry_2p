<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Producto extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'nombre',
        'codigo',
        'cantidad',
        'precio'
    ];
    protected $fillable = [
        'nombre',
        'codigo',
        'cantidad',
        'precio'
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

}
