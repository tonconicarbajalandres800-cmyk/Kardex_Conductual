<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoEstudianteController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::all();
        return view('admin.fotos.index', compact('estudiantes'));
    }

    public function upload(Request $request, $estudianteId)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $estudiante = Estudiante::findOrFail($estudianteId);
        
        // Eliminar foto anterior si existe
        if ($estudiante->foto) {
            $rutaAnterior = str_replace('fotos/', '', $estudiante->foto);
            if (Storage::exists('public/fotos/' . $rutaAnterior)) {
                Storage::delete('public/fotos/' . $rutaAnterior);
            }
        }
        
        // Generar nombre único
        $nombreArchivo = uniqid() . '.' . $request->foto->extension();
        
        // Guardar en storage/app/public/fotos/
        $path = $request->foto->storeAs('public/fotos', $nombreArchivo);
        
        // Guardar la ruta en la base de datos
        $estudiante->foto = 'fotos/' . $nombreArchivo;
        $estudiante->save();
        
        return redirect()->back()->with('success', '📸 Foto subida correctamente para ' . $estudiante->nombre);
    }
}