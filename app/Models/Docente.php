<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $fillable = [
        'user_id',
        'especialidad',        // ⭐ AGREGADO
        'telefono',
        'email',
        'es_encargado',
        'curso_encargado_id',
        'materias',            // ⭐ AGREGADO
    ];

    protected $casts = [
        'materias' => 'array',
        'es_encargado' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cursoEncargado()
    {
        return $this->belongsTo(Curso::class, 'curso_encargado_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}