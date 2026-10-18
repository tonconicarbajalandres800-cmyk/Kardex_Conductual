<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function estudiantes($cursoId)
    {
        $curso = Curso::with(['estudiantes.incidencias', 'estudiantes.fotos'])->findOrFail($cursoId);
        
        // Preparar datos para cada estudiante
        $estudiantes = $curso->estudiantes->map(function ($estudiante) {
            $incidencias = $estudiante->incidencias;
            $totalFaltas = $incidencias->count();
            $faltasGraves = $incidencias->where('tipo', 'grave')->count();
            $ultimaFalta = $incidencias->last();
            
            // 👇 OBTENER LA URL DE LA FOTO DESDE LA CARPETA CORRECTA
            $fotoUrl = null;
if ($estudiante->fotos && $estudiante->fotos->count() > 0) {
    $ultimaFoto = $estudiante->fotos->last();
    $nombreArchivo = basename($ultimaFoto->ruta);
    $fotoUrl = asset('storage/fotos/' . $nombreArchivo);
}
            
            return [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre . ' ' . $estudiante->apellido,
                'total_faltas' => $totalFaltas,
                'faltas_graves' => $faltasGraves,
                'ultima_falta_fecha' => $ultimaFalta ? $ultimaFalta->created_at->format('d/m/Y') : 'Sin faltas',
                'ultima_falta_desc' => $ultimaFalta ? $ultimaFalta->tipo : '-',
                'ultima_falta_materia' => $ultimaFalta ? $ultimaFalta->materia : '-',
                'foto_url' => $fotoUrl,
                'fotos' => $estudiante->fotos,
            ];
        });

        return view('docente.curso.estudiantes', compact('curso', 'estudiantes'));
    }

           public function descargarListaPDF($id)
    {
        $curso = \App\Models\Curso::findOrFail($id);
        
        // ✅ ORDENADO ALFABÉTICAMENTE DE LA A A LA Z
        $estudiantes = \App\Models\Estudiante::where('curso_id', $curso->id)
                        ->orderBy('apellido', 'asc') // Ordena por apellido de forma ascendente
                        ->orderBy('nombre', 'asc')   // Si hay dos apellidos iguales, ordena por nombre
                        ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('docente.cursos.lista_pdf', compact('curso', 'estudiantes'));
        
        $pdf->setPaper('A4', 'portrait');

        $nombreArchivo = 'Lista_' . str_replace(' ', '_', $curso->nombre) . '_' . $curso->paralelo . '.pdf';
        return $pdf->download($nombreArchivo);
    }
}