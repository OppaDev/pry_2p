<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignaturaUser extends Model
{
    protected $table = 'asignatura_user';

    protected $fillable = [
        'user_id',
        'asignatura_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
