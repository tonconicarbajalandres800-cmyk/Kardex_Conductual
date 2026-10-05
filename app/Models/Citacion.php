<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citacion extends Model
{
    protected $table = 'citaciones';

    protected $fillable = [
        'estudiante_id',
        'docente_id',
        'fecha_programada',
        'motivo',
        'estado'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}