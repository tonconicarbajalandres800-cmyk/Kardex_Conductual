<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Citacion;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Citacion::with(['estudiante.curso', 'docente.user']);
        
        // Filtros
        if ($request->curso) {
            $query->whereHas('estudiante', function($q) use ($request) {
                $q->where('curso_id', $request->curso);
            });
        }
        
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->buscar) {
            $query->whereHas('estudiante', function($q) use ($request) {
                $q->where('nombre', 'LIKE', "%{$request->buscar}%")
                  ->orWhere('apellido', 'LIKE', "%{$request->buscar}%");
            });
        }
        
        if ($request->fecha) {
            $query->whereDate('fecha_programada', $request->fecha);
        }
        
        $citaciones = $query->orderBy('created_at', 'desc')->paginate(15);
        $cursos = Curso::all();
        $estados = ['pendiente', 'confirmada', 'reprogramada', 'justificada', 'cancelada'];
        
        // Estadísticas
        $pendientes = Citacion::where('estado', 'pendiente')->count();
        $confirmadas = Citacion::where('estado', 'confirmada')->count();
        $reprogramadas = Citacion::where('estado', 'reprogramada')->count();
        $canceladas = Citacion::where('estado', 'cancelada')->count();
        
        return view('admin.citaciones.index', compact(
            'citaciones', 
            'cursos', 
            'estados',
            'pendientes',
            'confirmadas',
            'reprogramadas',
            'canceladas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $estudiantes = Estudiante::with('curso')->get();
        $docentes = Docente::with('user')->get();
        return view('admin.citaciones.create', compact('estudiantes', 'docentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'docente_id' => 'required|exists:docentes,id',
            'motivo' => 'required|string|max:500',
            'fecha_programada' => 'required|date|after:now',
        ]);
        
        Citacion::create([
            'estudiante_id' => $request->estudiante_id,
            'docente_id' => $request->docente_id,
            'motivo' => $request->motivo,
            'fecha_programada' => $request->fecha_programada,
            'estado' => 'pendiente',
        ]);
        
        return redirect()->route('admin.citaciones.index')
            ->with('success', 'Citación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $citacion = Citacion::with(['estudiante.curso', 'docente.user'])->findOrFail($id);
        return view('admin.citaciones.show', compact('citacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $citacion = Citacion::findOrFail($id);
        $estudiantes = Estudiante::with('curso')->get();
        $docentes = Docente::with('user')->get();
        return view('admin.citaciones.edit', compact('citacion', 'estudiantes', 'docentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $citacion = Citacion::findOrFail($id);
        
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'docente_id' => 'required|exists:docentes,id',
            'motivo' => 'required|string|max:500',
            'fecha_programada' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,reprogramada,justificada,cancelada',
        ]);
        
        $citacion->update($request->all());
        
        return redirect()->route('admin.citaciones.index')
            ->with('success', 'Citación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $citacion = Citacion::findOrFail($id);
        $citacion->delete();
        
        return redirect()->route('admin.citaciones.index')
            ->with('success', 'Citación eliminada exitosamente.');
    }

    /**
     * Enviar citación por WhatsApp (REDIRECCIÓN DIRECTA)
     */
    public function enviarWhatsApp($id)
    {
        try {
            $citacion = Citacion::with(['estudiante.padre', 'docente.user'])->findOrFail($id);
            
            // Validar que esté pendiente
            if ($citacion->estado !== 'pendiente') {
                return redirect()->route('admin.citaciones.index')
                    ->with('error', '❌ Esta citación ya fue enviada o procesada.');
            }
            
            // Obtener teléfono del padre
            $telefono = null;
            if ($citacion->estudiante && $citacion->estudiante->padre) {
                $telefono = $citacion->estudiante->padre->telefono;
            }
            
            if (!$telefono) {
                return redirect()->route('admin.citaciones.index')
                    ->with('error', '❌ El estudiante no tiene un padre con número de teléfono registrado.');
            }
            
            // Limpiar teléfono
            $telefono = preg_replace('/[^0-9]/', '', $telefono);
            if (strlen($telefono) === 8) {
                $telefono = '591' . $telefono;
            }
            
            // Construir mensaje
            $estudiante = $citacion->estudiante;
            $padre = $estudiante->padre;
            $docente = $citacion->docente;
            
            $mensaje = "📋 *CITACIÓN ESCOLAR* 📋\n\n";
            $mensaje .= "🏫 U.E. GRAL. JOSÉ DE SAN MARTÍN\n";
            $mensaje .= "────────────────────\n\n";
            
            $mensaje .= "Estimado(a) *" . ($padre->nombre ?? 'Padre de Familia') . "*\n\n";
            $mensaje .= "Le informamos que su hijo(a) *" . ($estudiante->nombre ?? 'N/A') . " " . ($estudiante->apellido ?? '') . "*\n";
            $mensaje .= "ha sido citado(a) a una reunión en el colegio:\n\n";
            
            $mensaje .= "📝 *Motivo:*\n";
            $mensaje .= $citacion->motivo . "\n\n";
            
            $mensaje .= "📅 *Fecha:* " . \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y') . "\n";
            $mensaje .= "🕐 *Hora:* " . \Carbon\Carbon::parse($citacion->fecha_programada)->format('H:i') . " hrs\n\n";
            
            $mensaje .= "📍 *Lugar:* Dirección de la Unidad Educativa\n";
            $mensaje .= "👨‍🏫 *Docente:* " . ($docente && $docente->user ? $docente->user->nombre . ' ' . $docente->user->apellidos : 'Docente') . "\n\n";
            
            $mensaje .= "────────────────────\n";
            $mensaje .= "📌 *Favor confirmar su asistencia.*\n";
            $mensaje .= "📞 Contacto: " . ($docente && $docente->telefono ? $docente->telefono : 'Secretaría') . "\n\n";
            $mensaje .= "*Gracias por su atención.*";
            
            // Generar URL de WhatsApp
            $whatsappUrl = "https://wa.me/" . $telefono . "?text=" . urlencode($mensaje);
            
            // Actualizar estado de la citación
            $citacion->update(['estado' => 'confirmada']);
            
            // Redirigir directamente a WhatsApp
            return redirect()->away($whatsappUrl);
            
        } catch (\Exception $e) {
            return redirect()->route('admin.citaciones.index')
                ->with('error', '❌ Error al enviar: ' . $e->getMessage());
        }
    }

    /**
     * Enviar citación por email (método original).
     */
    public function enviar($id)
    {
        try {
            $citacion = Citacion::with(['estudiante.padre', 'docente.user'])->findOrFail($id);
            
            if ($citacion->estado !== 'pendiente') {
                return redirect()->route('admin.citaciones.index')
                    ->with('error', '❌ Esta citación ya fue enviada o procesada.');
            }
            
            $telefono = null;
            if ($citacion->estudiante && $citacion->estudiante->padre) {
                $telefono = $citacion->estudiante->padre->telefono;
            }
            
            if (!$telefono) {
                return redirect()->route('admin.citaciones.index')
                    ->with('error', '❌ El estudiante no tiene un padre con número de teléfono registrado.');
            }
            
            $telefono = preg_replace('/[^0-9]/', '', $telefono);
            if (strlen($telefono) === 8) {
                $telefono = '591' . $telefono;
            }
            
            $estudiante = $citacion->estudiante;
            $padre = $estudiante->padre;
            $docente = $citacion->docente;
            
            $mensaje = "📋 *CITACIÓN ESCOLAR* 📋\n\n";
            $mensaje .= "🏫 U.E. GRAL. JOSÉ DE SAN MARTÍN\n";
            $mensaje .= "────────────────────\n\n";
            
            $mensaje .= "Estimado(a) *" . ($padre->nombre ?? 'Padre de Familia') . "*\n\n";
            $mensaje .= "Le informamos que su hijo(a) *" . ($estudiante->nombre ?? 'N/A') . " " . ($estudiante->apellido ?? '') . "*\n";
            $mensaje .= "ha sido citado(a) a una reunión en el colegio:\n\n";
            
            $mensaje .= "📝 *Motivo:*\n";
            $mensaje .= $citacion->motivo . "\n\n";
            
            $mensaje .= "📅 *Fecha:* " . \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y') . "\n";
            $mensaje .= "🕐 *Hora:* " . \Carbon\Carbon::parse($citacion->fecha_programada)->format('H:i') . " hrs\n\n";
            
            $mensaje .= "📍 *Lugar:* Dirección de la Unidad Educativa\n";
            $mensaje .= "👨‍🏫 *Docente:* " . ($docente && $docente->user ? $docente->user->nombre . ' ' . $docente->user->apellidos : 'Docente') . "\n\n";
            
            $mensaje .= "────────────────────\n";
            $mensaje .= "📌 *Favor confirmar su asistencia.*\n";
            $mensaje .= "📞 Contacto: " . ($docente && $docente->telefono ? $docente->telefono : 'Secretaría') . "\n\n";
            $mensaje .= "*Gracias por su atención.*";
            
            $whatsappUrl = "https://wa.me/" . $telefono . "?text=" . urlencode($mensaje);
            
            $citacion->update(['estado' => 'confirmada']);
            
            return redirect()->route('admin.citaciones.index')
                ->with('success', '✅ Citación enviada exitosamente por WhatsApp')
                ->with('whatsapp_url', $whatsappUrl);
            
        } catch (\Exception $e) {
            return redirect()->route('admin.citaciones.index')
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Historial de citaciones.
     */
    public function historial()
    {
        $citaciones = Citacion::with(['estudiante.curso', 'docente.user'])
            ->whereIn('estado', ['confirmada', 'justificada', 'cancelada'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.citaciones.historial', compact('citaciones'));
    }
}