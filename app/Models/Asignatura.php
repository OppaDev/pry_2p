<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Asignatura extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = ['nombre', 'codigo'];

    protected $auditInclude = [
        'nombre',
        'codigo',
    ];

    /**
     * The events that should trigger an audit.
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con docentes (muchos a muchos)
     */
    public function docentes()
    {
        return $this->belongsToMany(User::class, 'asignatura_users');
    }

    /**
     * Relación con notas
     */
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Verificar si un docente está asignado a esta asignatura
     */
    public function tieneDocente($docenteId)
    {
        return $this->docentes()->where('user_id', $docenteId)->exists();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'asignatura_user', 'asignatura_id', 'user_id');
    }
}
