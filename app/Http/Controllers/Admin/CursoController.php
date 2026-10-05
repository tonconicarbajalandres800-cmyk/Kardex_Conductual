<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Incidencia;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $query = Curso::with(['estudiantes.incidencias']);

        if ($request->filled('gestion')) {
            $query->where('gestion', $request->gestion);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('paralelo', 'LIKE', "%{$search}%");
            });
        }

        $cursos = $query->orderBy('nivel')->orderBy('paralelo')->get();
        $gestiones = Curso::distinct()->pluck('gestion');

        // ==========================================
        // ESTADÍSTICAS DESTACADAS
        // ==========================================

        // 1. CURSO MÁS INDISCIPLINADO (más incidencias)
        $cursoMasIndisciplinado = null;
        $maxIncidencias = 0;
        foreach ($cursos as $curso) {
            $totalInc = $curso->estudiantes->sum(function($e) {
                return $e->incidencias->count();
            });
            if ($totalInc > $maxIncidencias) {
                $maxIncidencias = $totalInc;
                $cursoMasIndisciplinado = [
                    'nombre' => $curso->nombre,
                    'paralelo' => 'Paralelo ' . $curso->paralelo . ' — ' . $curso->gestion,
                    'total_incidencias' => $totalInc,
                ];
            }
        }

        // 2. MATERIA CON MÁS FALTAS
        $materiaMasFaltas = Incidencia::selectRaw('materia, count(*) as total')
            ->whereNotNull('materia')
            ->groupBy('materia')
            ->orderBy('total', 'desc')
            ->first();

        $materiaMasFaltasData = null;
        if ($materiaMasFaltas) {
            $materiaMasFaltasData = [
                'nombre' => $materiaMasFaltas->materia,
                'total' => $materiaMasFaltas->total,
            ];
        }

        // 3. DOCENTE DE ESA MATERIA (el que más registró incidencias en esa materia)
        $docenteMateria = null;
        if ($materiaMasFaltas) {
            $docenteData = Incidencia::with('docente.user')
                ->where('materia', $materiaMasFaltas->materia)
                ->selectRaw('docente_id, count(*) as total')
                ->groupBy('docente_id')
                ->orderBy('total', 'desc')
                ->first();

            if ($docenteData && $docenteData->docente && $docenteData->docente->user) {
                $docenteMateria = [
                    'nombre' => $docenteData->docente->user->nombre . ' ' . $docenteData->docente->user->apellidos,
                    'email' => $docenteData->docente->user->email,
                ];
            }
        }

        return view('admin.cursos.index', compact(
            'cursos',
            'gestiones',
            'cursoMasIndisciplinado',
            'materiaMasFaltasData',
            'docenteMateria'
        ));
    }

    public function create()
    {
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'paralelo' => 'required|string|max:2',
            'nivel' => 'required|integer|min:1|max:6',
            'gestion' => 'required|string|size:4',
        ]);

        Curso::create($validated);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso creado correctamente.');
    }

    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('admin.cursos.edit', compact('curso'));
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'paralelo' => 'required|string|max:2',
            'nivel' => 'required|integer|min:1|max:6',
            'gestion' => 'required|string|size:4',
        ]);

        $curso->update($validated);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso eliminado correctamente.');
    }

    public function estudiantes($id)
    {
        $curso = Curso::with('estudiantes')->findOrFail($id);
        return view('admin.cursos.estudiantes', compact('curso'));
    }
}