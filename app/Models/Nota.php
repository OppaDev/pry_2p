<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
<<<<<<< HEAD
use OwenIt\Auditing\Contracts\Auditable;

class Nota extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'asignatura_id', 
        'nota_1',
        'nota_2',
        'nota_3',
        'promedio',
        'estado_final'
    ];

    protected $casts = [
        'nota_1' => 'decimal:2',
        'nota_2' => 'decimal:2',
        'nota_3' => 'decimal:2',
        'promedio' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Events to audit.
     */
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    /**
     * Relación con el estudiante (User)
     */
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con la asignatura
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    /**
     * Calcular automáticamente el promedio de las 3 notas
     */
    public function calcularPromedio()
    {
        if ($this->nota_1 !== null && $this->nota_2 !== null && $this->nota_3 !== null) {
            $promedio = ($this->nota_1 + $this->nota_2 + $this->nota_3) / 3;
            return round($promedio, 2);
        }
        return null;
    }

    /**
     * Determinar el estado final basado en el promedio
     */
    public function determinarEstadoFinal()
    {
        $promedio = $this->promedio ?? $this->calcularPromedio();
        
        if ($promedio !== null) {
            return $promedio >= 14.5 ? 'aprobado' : 'reprobado';
        }
        
        return 'pendiente';
    }

    /**
     * Mutator para calcular automáticamente el promedio al guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($nota) {
            // Calcular promedio automáticamente
            $nota->promedio = $nota->calcularPromedio();
            
            // Determinar estado final automáticamente
            $nota->estado_final = $nota->determinarEstadoFinal();
        });
    }

    /**
     * Scope para filtrar por estudiante
     */
    public function scopeDelEstudiante($query, $estudianteId)
    {
        return $query->where('user_id', $estudianteId);
    }

    /**
     * Scope para filtrar por asignatura
     */
    public function scopeDeAsignatura($query, $asignaturaId)
    {
        return $query->where('asignatura_id', $asignaturaId);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_final', $estado);
    }
=======
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Nota extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;
>>>>>>> 6f801e22343a10ee3292ae004e5915e4cecaf779
}
