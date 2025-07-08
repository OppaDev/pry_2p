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
}
