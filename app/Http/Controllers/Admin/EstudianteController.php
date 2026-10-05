<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\FotoEstudiante; // ← AGREGAR ESTA LÍNEA
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function kardex($id)
    {
        $estudiante = Estudiante::with(['incidencias.docente', 'curso', 'padre'])->findOrFail($id);
        
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

        return view('admin.estudiantes.kardex', compact('estudiante', 'tiposFalta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'ci' => 'required|unique:estudiantes',
            'curso_id' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Crear estudiante
        $estudiante = Estudiante::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'ci' => $request->ci,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'curso_id' => $request->curso_id,
            'padre_id' => $request->padre_id,
            'direccion' => $request->direccion,
            'numero_lista' => $request->numero_lista,
        ]);

        // Guardar la foto si se subió
        if ($request->hasFile('foto')) {
            $nombreArchivo = uniqid() . '_' . time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/fotos', $nombreArchivo);
            
            FotoEstudiante::create([
                'estudiante_id' => $estudiante->id,
                'nombre_archivo' => $nombreArchivo,
                'ruta' => 'storage/fotos/' . $nombreArchivo
            ]);
        }

        return redirect()->route('admin.estudiantes.index')
                         ->with('success', 'Estudiante registrado correctamente');
    }
}