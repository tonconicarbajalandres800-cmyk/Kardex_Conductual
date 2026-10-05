<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register-mensaje');
    }

    public function register(Request $request)
    {
        // Validar
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:users',
            'rol' => 'required|in:admin,docente,padre',
            'curso' => 'nullable|string|max:100',
            'materia' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Guardar foto
        $fotoPath = $request->file('foto') 
            ? $request->file('foto')->store('fotos', 'public') 
            : null;

        // Crear usuario
        $user = User::create([
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'ci' => $validated['ci'],
            'rol' => $validated['rol'],
            'curso' => $validated['curso'],
            'materia' => $validated['materia'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'foto' => $fotoPath,
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir según rol
        return $this->redirectToDashboard($user);
    }

    protected function redirectToDashboard($user)
    {
        switch ($user->rol) {
            case 'admin': return redirect()->route('admin.dashboard');
            case 'docente': return redirect()->route('docente.dashboard');
            case 'padre': return redirect()->route('padre.dashboard');
            default: return redirect('/dashboard');
        }
    }
}