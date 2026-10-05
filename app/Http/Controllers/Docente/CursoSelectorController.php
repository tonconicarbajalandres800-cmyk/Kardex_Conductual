<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoSelectorController extends Controller
{
    public function index()
    {
        $cursos = Curso::orderBy('nivel')->orderBy('paralelo')->get();
        return view('docente.cursos.seleccionar', compact('cursos'));
    }

    public function seleccionar($id)
{
    $curso = Curso::findOrFail($id);
    
    // Guardar en sesión
    session(['curso_seleccionado_id' => $curso->id]);
    session(['curso_seleccionado' => $curso->nombre . ' ' . $curso->paralelo]);
    
    return redirect()->route('docente.curso.estudiantes', $curso->id)
        ->with('success', '✅ Curso "' . $curso->nombre . ' ' . $curso->paralelo . '" seleccionado');
}
}