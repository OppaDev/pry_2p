<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asignatura extends Model
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['nombre', 'codigo'];

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
     * Get the audits for the asignatura.
     */
    public function audits()
    {
        return $this->morphMany('App\Models\Audit', 'auditable');
    }

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
}
