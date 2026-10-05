<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Incidencia;
use App\Models\Citacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FotoHelper;

class DocenteDashboardController extends Controller
{
    public function index(Request $request)
    {
        $docente = Auth::user()->docente;
        
        if (!$docente) {
            return redirect()->route('docente.dashboard')
                ->with('error', 'No tienes un perfil de docente asociado.');
        }
        
        $cursoId = $request->get('curso', session('docente_curso_id', 1));
        $curso = Curso::with('estudiantes.incidencias')->find($cursoId);
        
        if (!$curso) {
            $curso = Curso::first();
            $cursoId = $curso ? $curso->id : null;
        }
        
        session(['docente_curso_id' => $cursoId]);
        
        $totalEstudiantes = $curso ? $curso->estudiantes->count() : 0;
        
        $incidenciasQuery = Incidencia::whereHas('estudiante', function($q) use ($curso) {
            if ($curso) $q->where('curso_id', $curso->id);
        });
        
        if (!$docente->es_encargado) {
            $incidenciasQuery->where('materia', $docente->especialidad);
        }
        
        $totalIncidencias = $incidenciasQuery->count();
        
        $faltasGraves = $curso ? $curso->estudiantes->sum(function($e) { 
            return $e->incidencias->where('tipo', 'grave')->count(); 
        }) : 0;
        
        $citacionesActivas = Citacion::where('estado', 'pendiente')
            ->whereHas('estudiante', function($q) use ($curso) {
                if ($curso) $q->where('curso_id', $curso->id);
            })
            ->count();
        
        $incidenciasRecientes = $incidenciasQuery
            ->with(['estudiante', 'docente.user'])
            ->latest()
            ->take(5)
            ->get();
        
        $cursos = Curso::orderBy('nivel')->orderBy('paralelo')->get();
        
        $citaciones = Citacion::where('estado', 'pendiente')
            ->whereHas('estudiante', function($q) use ($curso) {
                if ($curso) $q->where('curso_id', $curso->id);
            })
            ->with(['estudiante.padre'])
            ->latest()
            ->take(3)
            ->get();
        
        return view('docente.dashboard', compact(
            'curso',
            'cursos',
            'totalEstudiantes',
            'totalIncidencias',
            'faltasGraves',
            'citacionesActivas',
            'incidenciasRecientes',
            'citaciones',
            'docente'
        ));
    }

    public function listarEstudiantes($cursoId)
{
    // ... tu código existente ...
    
    $estudiantes = $curso->estudiantes()->get()->map(function($estudiante) {
        $estudiante->foto_url = FotoHelper::getFotoEstudiante($estudiante->id);
        return $estudiante;
    });
    
    return view('docente.curso.estudiantes', compact('estudiantes', 'curso'));
}
}