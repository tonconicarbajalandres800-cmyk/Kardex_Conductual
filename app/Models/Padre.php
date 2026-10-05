<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Padre extends Model
{
    protected $fillable = ['user_id', 'nombre', 'apellido', 'telefono', 'email_secundario'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}