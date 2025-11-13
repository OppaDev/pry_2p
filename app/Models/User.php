<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;


class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'password',
        'remember_token',
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'email',
        'cedula',
        'email_verified_at',
    ];

    /**
     * Events to audit.
     *
     * @var array
     */
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cedula',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // ==================== RELACIONES ====================
    
    /**
     * Ventas realizadas por el vendedor
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }
    
    /**
     * Movimientos de inventario registrados por el usuario
     */
    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class, 'responsable_id');
    }
    
    // ==================== MÉTODOS DE NEGOCIO ====================
    
    /**
     * Verifica si el usuario tiene el rol de Administrador
     */
    public function esAdministrador(): bool
    {
        return $this->hasRole('administrador');
    }
    
    /**
     * Verifica si el usuario tiene el rol de Vendedor
     */
    public function esVendedor(): bool
    {
        return $this->hasRole('vendedor');
    }
    
    /**
     * Verifica si el usuario tiene el rol de Jefe de Bodega
     */
    public function esJefeBodega(): bool
    {
        return $this->hasRole('jefe_bodega');
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope para filtrar solo administradores
     */
    public function scopeAdministradores($query)
    {
        return $query->role('administrador');
    }
    
    /**
     * Scope para filtrar solo vendedores
     */
    public function scopeVendedores($query)
    {
        return $query->role('vendedor');
    }
    
    /**
     * Scope para filtrar solo jefes de bodega
     */
    public function scopeJefesBodega($query)
    {
        return $query->role('jefe_bodega');
    }
}
