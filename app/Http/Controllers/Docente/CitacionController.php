<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Citacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitacionController extends Controller
{
    public function create($estudianteId)
    {
        $estudiante = Estudiante::with(['curso', 'padre'])->findOrFail($estudianteId);
        return view('docente.citacion.create', compact('estudiante'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'motivo' => 'required|string|max:255',
            'fecha_programada' => 'required|date|after:now',
        ]);

        $docente = Auth::user()->docente;

        if (!$docente) {
            return back()->with('error', 'No tienes un perfil de docente asociado.');
        }

        $citacion = Citacion::create([
            'estudiante_id' => $request->estudiante_id,
            'docente_id' => $docente->id,
            'fecha_programada' => Carbon::parse($request->fecha_programada),
            'motivo' => $request->motivo,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('docente.dashboard')
            ->with('success', '✅ Citación creada correctamente.');
    }
}