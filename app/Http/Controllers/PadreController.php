<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Citacion;
use App\Models\Incidencia;
use App\Models\User;
use App\Models\Padre;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PadreController extends Controller
{
    // ========================================
    // DASHBOARD DEL PADRE
    // ========================================
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $padre = $user->padre;
        
        if (!$padre) {
            return redirect()->route('login')->with('error', 'No tienes un perfil de padre asociado.');
        }

        // Obtener todos los hijos del padre
        $hijos = Estudiante::with(['curso', 'incidencias', 'citaciones'])
            ->where('padre_id', $padre->id)
            ->get();

        // Seleccionar un hijo (por defecto el primero)
        $hijoSeleccionado = $request->get('hijo') 
            ? $hijos->where('id', $request->get('hijo'))->first() 
            : $hijos->first();

        // Si no hay hijos, mostrar mensaje
        if ($hijos->isEmpty()) {
            return view('padre.dashboard', compact('hijos', 'hijoSeleccionado', 'padre'))
                ->with('info', 'Aún no tienes hijos registrados en el sistema.');
        }

        // Estadísticas del hijo seleccionado
        $totalFaltas = $hijoSeleccionado ? $hijoSeleccionado->incidencias->count() : 0;
        $faltasGraves = $hijoSeleccionado ? $hijoSeleccionado->incidencias->where('tipo', 'grave')->count() : 0;
        $faltasLeves = $totalFaltas - $faltasGraves;
        
        // Estado del estudiante
        $estado = 'Normal';
        $mensajeEstado = 'Sin faltas registradas.';
        if ($totalFaltas >= 3) {
            $estado = 'Alerta';
            $mensajeEstado = '⚠️ El estudiante ha acumulado 3 o más faltas. Se recomienda tomar acción.';
        } elseif ($totalFaltas > 0) {
            $estado = 'Observación';
            $mensajeEstado = '📋 El estudiante tiene ' . $totalFaltas . ' falta(s) registrada(s).';
        }

        // Citaciones pendientes del hijo
        $citacionesPendientes = Citacion::whereHas('estudiante', function($q) use ($hijoSeleccionado) {
            $q->where('id', $hijoSeleccionado->id);
        })->where('estado', 'pendiente')->get();

        // Próximas reuniones (citaciones confirmadas o pendientes con fecha futura)
        $proximasReuniones = Citacion::whereHas('estudiante', function($q) use ($hijoSeleccionado) {
            $q->where('id', $hijoSeleccionado->id);
        })->whereIn('estado', ['pendiente', 'confirmada'])
          ->where('fecha_programada', '>=', Carbon::now())
          ->orderBy('fecha_programada')
          ->take(3)
          ->get();

        // Historial de incidencias (últimas 5)
        $historialIncidencias = $hijoSeleccionado 
            ? $hijoSeleccionado->incidencias()->orderBy('created_at', 'desc')->take(5)->get() 
            : collect();

        // Todas las citaciones del hijo
        $todasCitaciones = Citacion::whereHas('estudiante', function($q) use ($hijoSeleccionado) {
            $q->where('id', $hijoSeleccionado->id);
        })->orderBy('created_at', 'desc')->get();

        return view('padre.dashboard', compact(
            'padre',
            'hijos',
            'hijoSeleccionado',
            'totalFaltas',
            'faltasGraves',
            'faltasLeves',
            'estado',
            'mensajeEstado',
            'citacionesPendientes',
            'proximasReuniones',
            'historialIncidencias',
            'todasCitaciones'
        ));
    }

    // ========================================
    // VER KARDEX DEL HIJO
    // ========================================
    public function kardex($id)
    {
        $padre = Auth::user()->padre;
        
        // Verificar que el estudiante sea hijo del padre
        $estudiante = Estudiante::with(['incidencias', 'curso', 'padre'])
            ->where('id', $id)
            ->where('padre_id', $padre->id)
            ->firstOrFail();

        $tiposFalta = [
            'Atraso' => 1,
            'Falto a clases' => 2,
            'Falta con licencia' => 3,
            'Sin uniforme' => 4,
            'Indisciplina' => 5,
            'No hizo tarea' => 6,
            'No dio examen' => 7,
            'No trabaja en clases' => 8,
            'Tiene citación' => 9,
            'Tarea incompleta' => 10,
        ];

        return view('padre.kardex', compact('estudiante', 'tiposFalta'));
    }

    // ========================================
    // CONFIRMAR CITACIÓN
    // ========================================
    public function confirmarCitacion($id)
    {
        $citacion = Citacion::findOrFail($id);
        
        // Verificar que la citación pertenece a un hijo del padre
        $padre = Auth::user()->padre;
        $estudiante = $citacion->estudiante;
        
        if ($estudiante->padre_id != $padre->id) {
            return redirect()->back()->with('error', 'No tienes permiso para confirmar esta citación.');
        }

        $citacion->estado = 'confirmada';
        $citacion->save();

        return redirect()->back()->with('success', '✅ Citación confirmada correctamente.');
    }

    // ========================================
    // REPROGRAMAR CITACIÓN
    // ========================================
    public function reprogramarCitacion(Request $request, $id)
    {
        $citacion = Citacion::findOrFail($id);
        
        $padre = Auth::user()->padre;
        $estudiante = $citacion->estudiante;
        
        if ($estudiante->padre_id != $padre->id) {
            return redirect()->back()->with('error', 'No tienes permiso para reprogramar esta citación.');
        }

        $request->validate([
            'fecha_programada' => 'required|date|after:now',
        ]);

        $citacion->fecha_programada = Carbon::parse($request->fecha_programada);
        $citacion->estado = 'reprogramada';
        $citacion->save();

        return redirect()->back()->with('success', '🔄 Citación reprogramada para el ' . $citacion->fecha_programada->format('d/m/Y H:i'));
    }

    // ========================================
    // HISTORIAL DE CITACIONES
    // ========================================
    public function historialCitaciones()
    {
        $padre = Auth::user()->padre;
        
        if (!$padre) {
            return redirect()->route('padre.dashboard')->with('error', 'No tienes un perfil de padre asociado.');
        }

        $citaciones = Citacion::whereHas('estudiante', function($q) use ($padre) {
            $q->where('padre_id', $padre->id);
        })->with(['estudiante', 'estudiante.curso'])
          ->orderBy('created_at', 'desc')
          ->get();

        $estadisticas = [
            'total' => $citaciones->count(),
            'pendientes' => $citaciones->where('estado', 'pendiente')->count(),
            'confirmadas' => $citaciones->where('estado', 'confirmada')->count(),
            'reprogramadas' => $citaciones->where('estado', 'reprogramada')->count(),
            'justificadas' => $citaciones->where('estado', 'justificada')->count(),
            'canceladas' => $citaciones->where('estado', 'cancelada')->count(),
        ];

        return view('padre.historial-citaciones', compact('citaciones', 'estadisticas'));
    }

    // ========================================
    // PERFIL DEL PADRE
    // ========================================
    public function perfil()
{
    $user = Auth::user();
    $padre = $user->padre;
    
    // Si no tiene perfil de padre, redirigir
    if (!$padre) {
        return redirect()->route('padre.dashboard')->with('error', 'No tienes un perfil de padre asociado.');
    }
    
    return view('padre.perfil', compact('user', 'padre'));
}

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();
        $padre = $user->padre;

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($padre) {
            $padre->update([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellidos'],
                'telefono' => $validated['telefono'],
            ]);
        }

        return redirect()->route('padre.perfil')->with('success', '✅ Perfil actualizado correctamente.');
    }

    public function descargarPDF($id)
{
    $padre = Auth::user()->padre;
    
    // Verificar que el estudiante sea hijo del padre
    $estudiante = Estudiante::with(['incidencias', 'curso', 'padre'])
        ->where('id', $id)
        ->where('padre_id', $padre->id)
        ->firstOrFail();

    $tiposFalta = [
        'Atraso' => 1,
        'Falto a clases' => 2,
        'Falta con licencia' => 3,
        'Sin uniforme' => 4,
        'Indisciplina' => 5,
        'No hizo tarea' => 6,
        'No dio examen' => 7,
        'No trabaja en clases' => 8,
        'Tiene citación' => 9,
        'Tarea incompleta' => 10,
    ];

    $pdf = Pdf::loadView('padre.pdf.kardex', compact('estudiante', 'tiposFalta'));
    
    return $pdf->download('Kardex_' . $estudiante->nombre . '_' . $estudiante->apellido . '.pdf');
}

public function asistencia(Request $request)
{
    // Obtener el padre autenticado
    $padre = Auth::user()->padre;
    
    if (!$padre) {
        return redirect()->route('padre.dashboard')->with('error', 'No tienes un perfil de padre asociado.');
    }

    // Obtener todos los hijos del padre
    $hijos = Estudiante::where('padre_id', $padre->id)->with('curso')->get();
    
    if ($hijos->isEmpty()) {
        return redirect()->route('padre.dashboard')->with('error', 'No tienes hijos registrados.');
    }

    // Seleccionar un hijo (por parámetro o el primero)
    $estudianteId = $request->get('estudiante_id');
    $estudiante = $estudianteId ? Estudiante::with('curso')->find($estudianteId) : $hijos->first();
    
    if (!$estudiante) {
        return redirect()->route('padre.dashboard')->with('error', 'Estudiante no encontrado.');
    }
    
    // Filtros de fecha
    $fechaDesde = $request->get('fecha_desde') ?? Carbon::now()->subMonth()->format('Y-m-d');
    $fechaHasta = $request->get('fecha_hasta') ?? Carbon::now()->format('Y-m-d');
    
    // ==========================================
    // CONSULTAS PARA LAS 3 TABLAS SEPARADAS
    // ==========================================
    
    // 1. AUSENTES (Asistencias injustificadas)
    $ausentes = Asistencia::where('estudiante_id', $estudiante->id)
        ->where('estado', 'ausente')
        ->where('fecha', '>=', $fechaDesde)
        ->where('fecha', '<=', $fechaHasta)
        ->orderBy('fecha', 'desc')
        ->get();
    
    // 2. ATRASOS (Tardanzas)
    $atrasos = Asistencia::where('estudiante_id', $estudiante->id)
        ->where('estado', 'tardanza')
        ->where('fecha', '>=', $fechaDesde)
        ->where('fecha', '<=', $fechaHasta)
        ->orderBy('fecha', 'desc')
        ->get();
    
    // 3. PERMISOS
    $permisos = Asistencia::where('estudiante_id', $estudiante->id)
        ->where('estado', 'permiso')
        ->where('fecha', '>=', $fechaDesde)
        ->where('fecha', '<=', $fechaHasta)
        ->orderBy('fecha', 'desc')
        ->get();
    
    // Estadísticas generales
    $estadisticas = [
        'presente' => Asistencia::where('estudiante_id', $estudiante->id)
            ->where('estado', 'presente')
            ->where('fecha', '>=', $fechaDesde)
            ->where('fecha', '<=', $fechaHasta)
            ->count(),
        'ausente' => $ausentes->count(),
        'permiso' => $permisos->count(),
        'tardanza' => $atrasos->count(),
    ];
    
    return view('padre.asistencia', compact(
        'estudiante', 
        'hijos', 
        'ausentes',
        'atrasos',
        'permisos',
        'estadisticas',
        'fechaDesde',
        'fechaHasta'
    ));
}
}