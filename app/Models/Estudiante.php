<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    
    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'ci',
        'fecha_nacimiento',
        'curso_id',
        'padre_id',
        'numero_lista',
        'direccion',
        'foto',
    ];

    // RELACIÓN CON INCIDENCIAS
    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'estudiante_id');
    }

    // RELACIÓN CON FOTOS
    public function fotos(): HasMany
    {
        return $this->hasMany(FotoEstudiante::class, 'estudiante_id');
    }

    // RELACIÓN CON CURSO
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    // RELACIÓN CON PADRE
    public function padre()
    {
        return $this->belongsTo(Padre::class);
    }

    // RELACIÓN CON CITACIONES
    public function citaciones()
    {
        return $this->hasMany(Citacion::class);
    }

    // OBTENER URL DE LA FOTO
    public function getFotoUrlAttribute()
    {
        // Buscar en la relación fotos
        if ($this->fotos && $this->fotos->count() > 0) {
            $ruta = $this->fotos->last()->ruta;
            return asset('storage/' . $ruta);
        }
        
        // Buscar en el campo directo
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        
        return null;
    }

    // NOMBRE COMPLETO
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    // TOTAL DE INCIDENCIAS
    public function getTotalIncidenciasAttribute()
    {
        return $this->incidencias()->count();
    }

    // VERIFICAR SI TIENE 3+ FALTAS
    public function getTieneCitacionAttribute()
    {
        return $this->incidencias()->count() >= 3;
    }
}