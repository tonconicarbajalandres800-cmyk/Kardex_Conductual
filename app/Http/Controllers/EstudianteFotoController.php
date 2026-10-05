<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EstudianteFotoController extends Controller
{
    public function subirFoto(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $estudiante = Estudiante::findOrFail($request->estudiante_id);
        
        // Generar nombre único para la foto
        $nombreArchivo = uniqid() . '.' . $request->foto->extension();
        
        // Guardar en storage/app/public/fotos/
        $path = $request->foto->storeAs('public/fotos', $nombreArchivo);
        
        // Guardar la ruta en la base de datos
        $estudiante->foto = 'fotos/' . $nombreArchivo;
        $estudiante->save();
        
        return back()->with('success', 'Foto subida correctamente para ' . $estudiante->nombre);
    }
}