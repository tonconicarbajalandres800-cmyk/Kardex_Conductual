<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el login y redirige según el rol.
     */
    public function login(Request $request)
    {
        // Reglas base de validación
        $rules = [
            'email'    => 'required|email',
            'password' => 'required',
        ];

        // El captcha solo se omite en entorno de pruebas (php artisan test).
        // En producción, local y staging se sigue exigiendo normalmente.
        if (!app()->environment('testing')) {
            $rules['captcha'] = 'required|captcha';
        }

        $credentials = $request->validate($rules, [
            'captcha.required' => 'Debes ingresar el código de seguridad.',
            'captcha.captcha'  => 'El código de seguridad es incorrecto. Intenta de nuevo.',
        ]);

        // Quitamos el captcha de las credenciales (no es parte del login)
        unset($credentials['captcha']);

        // Intentar autenticar
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirigir según el rol
            switch ($user->rol) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'docente':
                    return redirect()->route('docente.dashboard');
                case 'padre':
                    return redirect()->route('padre.dashboard');
                case 'regente':
                    return redirect()->route('regente.dashboard');
               // case 'estudiante':
                 //   return redirect()->route('estudiante.dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        // Si falla, volver con error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}