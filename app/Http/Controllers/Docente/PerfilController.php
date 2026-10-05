<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = $user->docente;
        
        return view('docente.perfil.index', compact('user', 'docente'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $docente = $user->docente;

        // Validar datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|max:5120',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Datos a actualizar
        $data = [
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
        ];

        // Actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Actualizar foto si se sube
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        // Actualizar usuario
        $user->update($data);

        // Actualizar docente (teléfono y email)
        if ($docente) {
            $docente->update([
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('docente.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}