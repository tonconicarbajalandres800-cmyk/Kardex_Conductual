<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Incidencia;
use App\Models\Citacion;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsuarios = User::count();
        $totalEstudiantes = Estudiante::count();
        $totalDocentes = Docente::count();
        $totalCursos = Curso::count();
        $totalIncidencias = Incidencia::count();
        $incidenciasHoy = Incidencia::whereDate('created_at', today())->count();
        $citacionesPendientes = Citacion::where('estado', 'pendiente')->count();

        // Actividades recientes (últimas 5 incidencias)
        $actividades = Incidencia::with(['estudiante', 'docente.user'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($incidencia) {
                return [
                    'fecha' => $incidencia->created_at->format('d/m/Y H:i'),
                    'actividad' => $incidencia->tipo . ' - ' . ($incidencia->materia ?? 'Sin materia'),
                    'docente' => $incidencia->docente ? $incidencia->docente->user->nombre . ' ' . $incidencia->docente->user->apellidos : 'N/A',
                    'estudiante' => $incidencia->estudiante ? $incidencia->estudiante->nombre . ' ' . $incidencia->estudiante->apellido : 'N/A',
                ];
            });

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalEstudiantes',
            'totalDocentes',
            'totalCursos',
            'totalIncidencias',
            'incidenciasHoy',
            'citacionesPendientes',
            'actividades'
        ));
    }
}