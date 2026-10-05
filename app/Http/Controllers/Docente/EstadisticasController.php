<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Incidencia;
use App\Models\Citacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadisticasController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el curso seleccionado
        $cursoId = $request->get('curso', session('docente_curso_id', 1));
        $curso = Curso::with(['estudiantes.incidencias', 'estudiantes.citaciones', 'estudiantes.fotos'])->find($cursoId);
        
        if (!$curso) {
            $curso = Curso::first();
        }

        session(['docente_curso_id' => $curso->id]);

        // ESTADÍSTICAS GENERALES
        $totalEstudiantes = $curso->estudiantes->count();
        $totalIncidencias = $curso->estudiantes->sum(function($e) {
            return $e->incidencias->count();
        });
        
        $tiposGraves = ['Indisciplina', 'Falto a clases', 'Sin uniforme'];
        $totalFaltasGraves = $curso->estudiantes->sum(function($e) use ($tiposGraves) {
            return $e->incidencias->whereIn('tipo', $tiposGraves)->count();
        });

        $totalCitaciones = Citacion::whereHas('estudiante', function($q) use ($curso) {
            $q->where('curso_id', $curso->id);
        })->count();

        $citacionesPendientes = Citacion::whereHas('estudiante', function($q) use ($curso) {
            $q->where('curso_id', $curso->id);
        })->where('estado', 'pendiente')->count();

        // INCIDENCIAS POR TIPO
        $incidenciasPorTipo = Incidencia::whereHas('estudiante', function($q) use ($curso) {
            $q->where('curso_id', $curso->id);
        })->selectRaw('tipo, count(*) as total')
          ->groupBy('tipo')
          ->orderBy('total', 'desc')
          ->get();

        // INCIDENCIAS POR MATERIA
        $incidenciasPorMateria = Incidencia::whereHas('estudiante', function($q) use ($curso) {
            $q->where('curso_id', $curso->id);
        })->selectRaw('materia, count(*) as total')
          ->whereNotNull('materia')
          ->groupBy('materia')
          ->orderBy('total', 'desc')
          ->get();

        // TOP 5 ESTUDIANTES CON MÁS FALTAS
        $topEstudiantes = $curso->estudiantes->map(function($e) use ($tiposGraves) {
            return [
                'id' => $e->id,
                'nombre' => $e->nombre . ' ' . $e->apellido,
                'total' => $e->incidencias->count(),
                'graves' => $e->incidencias->whereIn('tipo', $tiposGraves)->count(),
                'foto' => $e->foto_url, // USAR EL ACCESSOR
            ];
        })->sortByDesc('total')->take(5)->values();

        $faltasLeves = $totalIncidencias - $totalFaltasGraves;
        $cursos = Curso::orderBy('nivel')->orderBy('paralelo')->get();

        return view('docente.estadisticas.index', compact(
            'curso',
            'cursos',
            'totalEstudiantes',
            'totalIncidencias',
            'totalFaltasGraves',
            'totalCitaciones',
            'citacionesPendientes',
            'incidenciasPorTipo',
            'incidenciasPorMateria',
            'topEstudiantes',
            'faltasLeves'
        ));
    }

    public function estudiante($id)
    {
        $estudiante = Estudiante::with(['incidencias', 'curso', 'padre', 'fotos'])->findOrFail($id);
        
        $totalFaltas = $estudiante->incidencias->count();
        
        $tiposGraves = ['Indisciplina', 'Falto a clases', 'Sin uniforme'];
        $faltasGraves = $estudiante->incidencias->whereIn('tipo', $tiposGraves)->count();
        $faltasLeves = $totalFaltas - $faltasGraves;
        
        $incidenciasPorMateria = $estudiante->incidencias
            ->groupBy('materia')
            ->map(function($item) {
                return $item->count();
            })
            ->sortDesc();

        $incidencias = $estudiante->incidencias()
            ->orderBy('created_at', 'desc')
            ->get();

        $tieneCitacionPendiente = Citacion::where('estudiante_id', $estudiante->id)
            ->where('estado', 'pendiente')
            ->exists();

        return view('docente.estadisticas.estudiante', compact(
            'estudiante',
            'totalFaltas',
            'faltasGraves',
            'faltasLeves',
            'incidenciasPorMateria',
            'incidencias',
            'tieneCitacionPendiente'
        ));
    }
}