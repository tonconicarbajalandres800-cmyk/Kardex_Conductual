<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellidos',
        'ci',
        'rol',
        'curso',
        'materia',
        'telefono',
        'email',
        'password',
        'foto',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================================
    // RELACIONES
    // ============================================
    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    public function padre()
    {
        return $this->hasOne(Padre::class);
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    public function regente()
    {
        return $this->hasOne(Regente::class);
    }

    // ⭐⭐⭐ NUEVA RELACIÓN: ESTUDIANTES HIJOS ⭐⭐⭐
    public function estudiantesHijos()
    {
        // Un padre tiene muchos estudiantes (hijos)
        return $this->hasMany(Estudiante::class, 'padre_id', 'id');
    }

    // ============================================
    // MÉTODO PARA REDIRECCIÓN SEGÚN ROL
    // ============================================
    public function getRedirectRouteAttribute()
    {
        return match ($this->rol) {
            'admin' => 'admin.dashboard',
            'docente' => 'docente.dashboard',
            'padre' => 'padre.dashboard',
            'regente' => 'regente.dashboard',
            'estudiante' => 'estudiante.dashboard',
            default => 'dashboard',
        };
    }

    // ============================================
    // MÉTODO PARA VERIFICAR SI ES REGENTE
    // ============================================
    public function isRegente()
    {
        return $this->rol === 'regente';
    }

    // ============================================
    // MÉTODO PARA OBTENER NOMBRE COMPLETO
    // ============================================
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    // ============================================
    // ACCESOR PARA LA FOTO
    // ============================================
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return null;
    }

    // ============================================
    // SCOPES
    // ============================================
    public function scopeRegentes($query)
    {
        return $query->where('rol', 'regente');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}