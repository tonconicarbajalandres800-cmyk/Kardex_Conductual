<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function kardex($id)
    {
        $estudiante = Estudiante::with(['incidencias.docente', 'curso', 'padre'])->findOrFail($id);
        
        // Mapeo de códigos de falta (según el documento físico)
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

        return view('docente.estudiante.kardex', compact('estudiante', 'tiposFalta'));
    }
}



