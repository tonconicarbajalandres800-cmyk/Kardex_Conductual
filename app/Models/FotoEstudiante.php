<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoEstudiante extends Model
{
    protected $table = 'fotos_estudiantes';
    
    protected $fillable = [
        'estudiante_id',
        'nombre_archivo',
        'ruta'
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }
}