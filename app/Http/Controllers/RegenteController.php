<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Asistencia;
use App\Models\Estudiante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegenteController extends Controller
{
    public function dashboard()
    {
        $cursos = Curso::with('estudiantes')->get();
        
        $fecha = Carbon::now()->format('Y-m-d');
        $presentesHoy = Asistencia::whereDate('fecha', $fecha)->where('estado', 'presente')->count();
        $ausentesHoy  = Asistencia::whereDate('fecha', $fecha)->where('estado', 'ausente')->count();
        $permisosHoy  = Asistencia::whereDate('fecha', $fecha)->where('estado', 'permiso')->count();

        return view('regente.dashboard', compact('cursos', 'presentesHoy', 'ausentesHoy', 'permisosHoy'));
    }

    public function asistencia(Request $request)
    {
        $cursoId = $request->get('curso');
        $fecha   = $request->get('fecha') ?? Carbon::now()->format('Y-m-d');
        
        $cursos = Curso::all();
        $cursoSeleccionado = Curso::find($cursoId);
        
        // Traer estudiantes
        if ($cursoSeleccionado) {
            $estudiantes = Estudiante::where('curso_id', $cursoId)->get();
        } else {
            $estudiantes = Estudiante::all();
        }
        
        $asistencias = Asistencia::whereDate('fecha', $fecha)
            ->when($cursoId, function ($query) use ($cursoId) {
                return $query->where('curso_id', $cursoId);
            })
            ->get()
            ->keyBy('estudiante_id');

        return view('regente.asistencia', compact('cursos', 'cursoSeleccionado', 'estudiantes', 'asistencias', 'fecha'));
    }

    /**
     * Guarda la asistencia MASIVA de todos los estudiantes de una vez.
     */
    public function registrarAsistencia(Request $request)
    {
        // 1. Validar payload
        $request->validate([
            'fecha'      => 'required|date',
            'estado'     => 'required|array|min:1',
            'estado.*'   => 'required|in:presente,ausente,permiso,tardanza',
            'curso_id'   => 'required|array',
            'curso_id.*' => 'nullable|exists:cursos,id',
            'motivo'     => 'nullable|array',
            'motivo.*'   => 'nullable|string|max:255',
        ], [
            'estado.required' => 'Debes marcar al menos un estudiante antes de guardar.',
            'estado.min'      => 'Debes marcar al menos un estudiante antes de guardar.',
        ]);

        $fecha         = $request->fecha;
        $estados       = $request->estado;
        $cursos        = $request->curso_id ?? [];
        $motivos       = $request->motivo ?? [];
        $registradoPor = Auth::user()->nombre . ' ' . Auth::user()->apellidos;

        $resumen = [
            'presente' => 0,
            'ausente'  => 0,
            'permiso'  => 0,
            'tardanza' => 0,
            'total'    => 0,
            'omitidos' => 0,
        ];

        // 2. Guardar con transacción
        DB::transaction(function () use (
            $estados, $cursos, $motivos, $fecha, $registradoPor, &$resumen
        ) {
            foreach ($estados as $estudianteId => $estado) {
                if (!in_array($estado, ['presente', 'ausente', 'permiso', 'tardanza'])) {
                    $resumen['omitidos']++;
                    continue;
                }

                $estudiante = Estudiante::find($estudianteId);
                if (!$estudiante) {
                    $resumen['omitidos']++;
                    continue;
                }

                $cursoId = $cursos[$estudianteId] ?? $estudiante->curso_id;
                $motivo  = ($estado === 'permiso' && !empty($motivos[$estudianteId]))
                    ? $motivos[$estudianteId]
                    : null;

                Asistencia::updateOrCreate(
                    [
                        'estudiante_id' => $estudianteId,
                        'fecha'         => $fecha,
                    ],
                    [
                        'curso_id'       => $cursoId,
                        'estado'         => $estado,
                        'motivo'         => $motivo,
                        'registrado_por' => $registradoPor,
                    ]
                );

                $resumen[$estado]++;
                $resumen['total']++;
            }
        });

        // 3. Redirigir a la misma vista con el resumen en sesión
        return redirect()
            ->route('regente.asistencia', [
                'fecha' => $fecha,
                'curso' => $request->input('curso_filtro'),
            ])
            ->with('resumen_asistencia', $resumen);
    }

    public function asistenciaEstudiante($id)
    {
        $estudiante = Estudiante::with('curso')->findOrFail($id);
        $asistencias = Asistencia::where('estudiante_id', $id)
            ->orderBy('fecha', 'desc')
            ->paginate(15);
        
        $estadisticas = [
            'presente' => Asistencia::where('estudiante_id', $id)->where('estado', 'presente')->count(),
            'ausente'  => Asistencia::where('estudiante_id', $id)->where('estado', 'ausente')->count(),
            'permiso'  => Asistencia::where('estudiante_id', $id)->where('estado', 'permiso')->count(),
            'tardanza' => Asistencia::where('estudiante_id', $id)->where('estado', 'tardanza')->count(),
        ];

        return view('regente.asistencia_estudiante', compact('estudiante', 'asistencias', 'estadisticas'));
    }
}