<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Incidencia;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\IncidenciaRegistrada;

class IncidenciaController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        $materiasDocente = [];
        if ($docente) {
            if ($docente->materias) {
                if (is_array($docente->materias)) {
                    $materiasDocente = $docente->materias;
                } else {
                    $materiasDocente = json_decode($docente->materias, true) ?? [];
                }
            }
            if (empty($materiasDocente) && $docente->especialidad) {
                $materiasDocente = [$docente->especialidad];
            }
        }
        
        if (empty($materiasDocente)) {
            $materiasDocente = ['Matemáticas', 'Física', 'Química', 'Lenguaje', 'Historia', 'Biología', 'Educación Física', 'Inglés', 'Arte', 'Música', 'Religión'];
            $mostrarAdvertencia = true;
        } else {
            $mostrarAdvertencia = false;
        }

        $cursos = Curso::all();
        
        // ============================================
        // PRIORIDAD DE SELECCIÓN DE CURSO:
        // 1. Curso desde URL (?curso=X)
        // 2. Curso desde sesión (último seleccionado)
        // 3. Primer curso disponible
        // ============================================
        $cursoSeleccionado = null;
        $cursoId = $request->get('curso') ?? session('curso_seleccionado_id');
        
        if ($cursoId) {
            $cursoSeleccionado = Curso::with(['estudiantes.fotos', 'estudiantes.incidencias', 'estudiantes.padre'])
                ->find($cursoId);
        }

        if (!$cursoSeleccionado && $cursos->count() > 0) {
            $cursoSeleccionado = Curso::with(['estudiantes.fotos', 'estudiantes.incidencias', 'estudiantes.padre'])
                ->find($cursos->first()->id);
        }
        
        // ============================================
        // GUARDAR CURSO EN SESIÓN
        // ============================================
        if ($cursoSeleccionado) {
            session([
                'curso_seleccionado' => $cursoSeleccionado->nombre . ' ' . $cursoSeleccionado->paralelo,
                'curso_seleccionado_id' => $cursoSeleccionado->id,
                'curso_id' => $cursoSeleccionado->id,
            ]);
        }
        
        $estudiantes = $cursoSeleccionado ? $cursoSeleccionado->estudiantes : collect();

        $estudianteSeleccionado = null;
        $incidenciasEstudiante = collect();
        
        if ($request->get('estudiante')) {
            $estudianteSeleccionado = Estudiante::with('fotos', 'incidencias', 'padre')->find($request->get('estudiante'));
            if ($estudianteSeleccionado) {
                $incidenciasEstudiante = $estudianteSeleccionado->incidencias()
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } elseif ($estudiantes->count() > 0) {
            $estudianteSeleccionado = $estudiantes->first();
            if ($estudianteSeleccionado) {
                $incidenciasEstudiante = $estudianteSeleccionado->incidencias()
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('docente.incidencia.create', compact(
            'cursos', 
            'materiasDocente',
            'cursoSeleccionado', 
            'estudiantes', 
            'estudianteSeleccionado',
            'incidenciasEstudiante',
            'mostrarAdvertencia'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'tipo' => 'required|string',
            'materia' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        // PROCESAR TIPO DE FALTA (SOPORTE PARA "OTRO")
        $tipoFalta = $request->tipo;
        if ($tipoFalta === 'Otro') {
            $request->validate([
                'otro_tipo' => 'required|string|max:100',
            ]);
            $tipoFalta = $request->otro_tipo;
        }

        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        $materiasDocente = [];
        if ($docente) {
            if ($docente->materias) {
                if (is_array($docente->materias)) {
                    $materiasDocente = $docente->materias;
                } else {
                    $materiasDocente = json_decode($docente->materias, true) ?? [];
                }
            }
            if (empty($materiasDocente) && $docente->especialidad) {
                $materiasDocente = [$docente->especialidad];
            }
        }
        
        if (!empty($materiasDocente) && !in_array($request->materia, $materiasDocente)) {
            return redirect()->back()
                ->with('error', '❌ La materia seleccionada no está asignada a tu perfil.')
                ->withInput();
        }

        if (!$docente) {
            $docente = Docente::create([
                'user_id' => $user->id,
                'especialidad' => $user->materia ?? 'Matemáticas',
                'telefono' => $user->telefono,
                'email' => $user->email,
                'es_encargado' => false,
                'curso_encargado_id' => null,
            ]);
        }

        $incidencia = Incidencia::create([
            'estudiante_id' => $request->estudiante_id,
            'docente_id' => $docente->id,
            'tipo' => $tipoFalta,
            'materia' => $request->materia,
            'descripcion' => $request->descripcion,
            'fecha_hora' => Carbon::now(),
        ]);

        event(new IncidenciaRegistrada($incidencia));

        $estudiante = Estudiante::with('padre')->find($request->estudiante_id);
        
        session()->flash('modal_confirmacion', [
            'total' => 1,
            'estudiantes' => [$estudiante->nombre . ' ' . $estudiante->apellido],
            'tipo' => $tipoFalta,
            'materia' => $request->materia,
            'descripcion' => $request->descripcion,
            'curso_id' => $request->curso_id ?? $estudiante->curso_id,
            'incidencia_id' => $incidencia->id,
            'estudiante_id' => $estudiante->id,
            'telefono_padre' => $estudiante->padre ? $estudiante->padre->telefono : null,
            'nombre_padre' => $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'Padre de familia',
        ]);

        return redirect()->route('docente.incidencia.create')
            ->with('mostrar_modal', true);
    }

    public function storeMasiva(Request $request)
    {
        $request->validate([
            'estudiantes_ids' => 'required|string',
            'tipo' => 'required|string',
            'materia' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        // PROCESAR TIPO DE FALTA (SOPORTE PARA "OTRO")
        $tipoFalta = $request->tipo;
        if ($tipoFalta === 'Otro') {
            $request->validate([
                'otro_tipo' => 'required|string|max:100',
            ]);
            $tipoFalta = $request->otro_tipo;
        }

        $ids = explode(',', $request->estudiantes_ids);
        
        if (empty($ids)) {
            return redirect()->route('docente.incidencia.create')
                ->with('error', 'No seleccionaste ningún estudiante');
        }

        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        $materiasDocente = [];
        if ($docente) {
            if ($docente->materias) {
                if (is_array($docente->materias)) {
                    $materiasDocente = $docente->materias;
                } else {
                    $materiasDocente = json_decode($docente->materias, true) ?? [];
                }
            }
            if (empty($materiasDocente) && $docente->especialidad) {
                $materiasDocente = [$docente->especialidad];
            }
        }
        
        if (!empty($materiasDocente) && !in_array($request->materia, $materiasDocente)) {
            return redirect()->back()
                ->with('error', '❌ La materia seleccionada no está asignada a tu perfil.')
                ->withInput();
        }
        
        if (!$docente) {
            $docente = Docente::create([
                'user_id' => $user->id,
                'especialidad' => $user->materia ?? 'Matemáticas',
                'telefono' => $user->telefono,
                'email' => $user->email,
                'es_encargado' => false,
                'curso_encargado_id' => null,
            ]);
        }

        $registradas = 0;
        $estudiantesInfo = Estudiante::with('padre')->whereIn('id', $ids)->get();

        foreach ($ids as $estudianteId) {
            try {
                Incidencia::create([
                    'estudiante_id' => $estudianteId,
                    'docente_id' => $docente->id,
                    'tipo' => $tipoFalta,
                    'materia' => $request->materia,
                    'descripcion' => $request->descripcion,
                    'fecha_hora' => Carbon::now(),
                ]);
                $registradas++;
                
            } catch (\Exception $e) {
                // Error
            }
        }

        $nombresEstudiantes = $estudiantesInfo->map(function($e) {
            return $e->nombre . ' ' . $e->apellido;
        })->toArray();

        $telefonosPadres = $estudiantesInfo->filter(function($e) {
            return $e->padre && $e->padre->telefono;
        })->map(function($e) {
            return $e->padre->telefono;
        })->toArray();

        session()->flash('modal_confirmacion', [
            'total' => $registradas,
            'estudiantes' => $nombresEstudiantes,
            'tipo' => $tipoFalta,
            'materia' => $request->materia,
            'descripcion' => $request->descripcion,
            'curso_id' => $request->curso_id,
            'telefonos_padres' => $telefonosPadres,
            'estudiantes_ids' => $ids,
        ]);

        return redirect()->route('docente.incidencia.create')
            ->with('mostrar_modal', true);
    }

    // ============================================
    // MÉTODOS PARA WHATSAPP
    // ============================================

    public function enviarWhatsAppIncidencia($id)
    {
        $incidencia = Incidencia::with(['estudiante.padre', 'docente'])->find($id);
        
        if (!$incidencia) {
            return response()->json(['error' => 'Incidencia no encontrada'], 404);
        }

        $estudiante = $incidencia->estudiante;
        
        if (!$estudiante->padre) {
            return response()->json(['error' => 'El estudiante no tiene padre asignado'], 400);
        }

        if (!$estudiante->padre->telefono) {
            return response()->json(['error' => 'El padre no tiene número de teléfono registrado'], 400);
        }

        $mensaje = "📋 *NOTIFICACIÓN DE INCIDENCIA* 📋\n\n";
        $mensaje .= "🏫 U.E. GRAL. JOSÉ DE SAN MARTÍN\n\n";
        $mensaje .= "Estimado(a) *" . $estudiante->padre->nombre . " " . $estudiante->padre->apellido . "*\n\n";
        $mensaje .= "Le informamos que su hijo(a) *" . $estudiante->nombre . " " . $estudiante->apellido . "*\n";
        $mensaje .= "ha registrado una incidencia en el colegio:\n\n";
        $mensaje .= "⚠️ *Tipo:* " . $incidencia->tipo . "\n";
        $mensaje .= "📚 *Materia:* " . $incidencia->materia . "\n";
        $mensaje .= "📅 *Fecha:* " . Carbon::parse($incidencia->fecha_hora)->format('d/m/Y H:i') . "\n";
        
        if ($incidencia->descripcion) {
            $mensaje .= "\n📝 *Descripción:*\n" . $incidencia->descripcion . "\n";
        }
        
        $mensaje .= "\n---\n";
        $mensaje .= "📱 *Docente:* " . ($incidencia->docente && $incidencia->docente->user ? $incidencia->docente->user->name : 'Docente') . "\n";
        $mensaje .= "\n*Por favor, tomar las medidas correspondientes.*\n";

        $telefono = preg_replace('/[^0-9]/', '', $estudiante->padre->telefono);
        if (strlen($telefono) == 8) {
            $telefono = '591' . $telefono;
        }

        $whatsappUrl = "https://wa.me/" . $telefono . "?text=" . urlencode($mensaje);

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'telefono' => $telefono,
            'mensaje' => $mensaje,
        ]);
    }

    public function enviarWhatsAppMasivo(Request $request)
    {
        $request->validate([
            'estudiantes_ids' => 'required|string',
            'tipo' => 'required|string',
            'materia' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        // PROCESAR TIPO DE FALTA (SOPORTE PARA "OTRO")
        $tipoFalta = $request->tipo;
        if ($tipoFalta === 'Otro') {
            $request->validate([
                'otro_tipo' => 'required|string|max:100',
            ]);
            $tipoFalta = $request->otro_tipo;
        }

        $ids = explode(',', $request->estudiantes_ids);
        $estudiantes = Estudiante::with('padre')->whereIn('id', $ids)->get();
        
        $mensajes = [];
        $telefonos = [];

        foreach ($estudiantes as $estudiante) {
            if ($estudiante->padre && $estudiante->padre->telefono) {
                $mensaje = "📋 *NOTIFICACIÓN DE INCIDENCIA* 📋\n\n";
                $mensaje .= "🏫 U.E. GRAL. JOSÉ DE SAN MARTÍN\n\n";
                $mensaje .= "Estimado(a) *" . $estudiante->padre->nombre . " " . $estudiante->padre->apellido . "*\n\n";
                $mensaje .= "Le informamos que su hijo(a) *" . $estudiante->nombre . " " . $estudiante->apellido . "*\n";
                $mensaje .= "ha registrado una incidencia en el colegio:\n\n";
                $mensaje .= "⚠️ *Tipo:* " . $tipoFalta . "\n";
                $mensaje .= "📚 *Materia:* " . $request->materia . "\n";
                $mensaje .= "📅 *Fecha:* " . Carbon::now()->format('d/m/Y H:i') . "\n";
                
                if ($request->descripcion) {
                    $mensaje .= "\n📝 *Descripción:*\n" . $request->descripcion . "\n";
                }
                
                $mensaje .= "\n---\n";
                $mensaje .= "*Por favor, tomar las medidas correspondientes.*\n";

                $telefono = preg_replace('/[^0-9]/', '', $estudiante->padre->telefono);
                if (strlen($telefono) == 8) {
                    $telefono = '591' . $telefono;
                }

                $telefonos[] = $telefono;
                $mensajes[] = [
                    'estudiante' => $estudiante->nombre . ' ' . $estudiante->apellido,
                    'telefono' => $telefono,
                    'mensaje' => $mensaje,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'total' => count($mensajes),
            'mensajes' => $mensajes,
            'telefonos' => $telefonos,
        ]);
    }

    // ============================================
    // MÉTODOS PARA AJAX
    // ============================================

    public function getIncidenciasEstudiante($id)
    {
        $estudiante = Estudiante::with('incidencias')->find($id);
        
        if (!$estudiante) {
            return response()->json(['incidencias' => []]);
        }
        
        $incidencias = $estudiante->incidencias()
            ->orderBy('created_at', 'desc')
            ->get(['id', 'tipo', 'materia', 'descripcion', 'created_at']);
        
        return response()->json([
            'incidencias' => $incidencias
        ]);
    }

    public function getDetalleIncidencia($id)
    {
        $incidencia = Incidencia::with(['estudiante', 'docente.user'])->find($id);
        
        if (!$incidencia) {
            return response()->json(['error' => 'Incidencia no encontrada'], 404);
        }
        
        return response()->json([
            'id' => $incidencia->id,
            'tipo' => $incidencia->tipo,
            'materia' => $incidencia->materia,
            'descripcion' => $incidencia->descripcion,
            'created_at' => $incidencia->created_at,
            'estudiante' => $incidencia->estudiante ? $incidencia->estudiante->nombre . ' ' . $incidencia->estudiante->apellido : 'N/A',
            'docente' => $incidencia->docente && $incidencia->docente->user ? $incidencia->docente->user->name : 'Docente',
        ]);
    }

    public function listadoIncidencias($id)
    {
        $estudiante = Estudiante::with('incidencias', 'curso')->find($id);
        
        if (!$estudiante) {
            return redirect()->route('docente.incidencia.create')
                ->with('error', 'Estudiante no encontrado');
        }
        
        $incidencias = $estudiante->incidencias()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('docente.incidencia.listado', compact('estudiante', 'incidencias'));
    }

    // ============================================
    // MÉTODOS PARA CITACIONES
    // ============================================

    public function mostrarCitacion($estudianteId)
    {
        $estudiante = Estudiante::with('padre', 'incidencias', 'curso')->find($estudianteId);
        
        if (!$estudiante) {
            return redirect()->route('docente.incidencia.create')
                ->with('error', 'Estudiante no encontrado');
        }

        $totalFaltas = $estudiante->incidencias()->count();
        
        return view('docente.incidencia.citacion', compact('estudiante', 'totalFaltas'));
    }

    public function enviarCitacionWhatsApp($estudianteId)
    {
        $estudiante = Estudiante::with('padre', 'curso')->find($estudianteId);
        
        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        if (!$estudiante->padre) {
            return response()->json(['error' => 'El estudiante no tiene padre asignado'], 400);
        }

        if (!$estudiante->padre->telefono) {
            return response()->json(['error' => 'El padre no tiene número de teléfono registrado'], 400);
        }

        $totalFaltas = $estudiante->incidencias()->count();
        if ($totalFaltas < 3) {
            return response()->json(['error' => 'El estudiante tiene menos de 3 faltas'], 400);
        }

        $mensaje = "📋 *CITACIÓN URGENTE* 📋\n\n";
        $mensaje .= "UNIDAD EDUCATIVA \"GRAL. JOSÉ DE SAN MARTÍN\"\n\n";
        $mensaje .= "Señor(a): *" . $estudiante->padre->nombre . " " . $estudiante->padre->apellido . "*\n";
        $mensaje .= "Apoderado de: *" . $estudiante->nombre . " " . $estudiante->apellido . "*\n";
        $mensaje .= "Curso: *" . ($estudiante->curso ? $estudiante->curso->nombre . " " . $estudiante->curso->paralelo : 'Sin curso') . "*\n\n";
        $mensaje .= "Por medio de la presente, se le cita a una reunión URGENTE\n";
        $mensaje .= "debido a las reiteradas faltas disciplinarias de su hijo(a).\n\n";
        $mensaje .= "📅 Fecha: *" . Carbon::now()->format('d/m/Y') . "*\n";
        $mensaje .= "🕐 Hora: *9:00 AM*\n";
        $mensaje .= "📍 Lugar: *Dirección de la Unidad Educativa*\n\n";
        $mensaje .= "Firma: ___________________\n";
        $mensaje .= "DIRECCIÓN\n\n";
        $mensaje .= "📱 *Faltas registradas: " . $totalFaltas . "*";

        $telefono = preg_replace('/[^0-9]/', '', $estudiante->padre->telefono);
        if (strlen($telefono) == 8) {
            $telefono = '591' . $telefono;
        }

        $whatsappUrl = "https://wa.me/" . $telefono . "?text=" . urlencode($mensaje);

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'telefono' => $telefono,
            'estudiante' => $estudiante->nombre . ' ' . $estudiante->apellido,
            'padre' => $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido,
            'total_faltas' => $totalFaltas
        ]);
    }
}