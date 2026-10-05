<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('admin.configuracion.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'fondo_login' => 'nullable|image|max:5120', // 5MB para fondo
            'escudo_colegio' => 'nullable|image|max:2048',
            'escudo_promocion' => 'nullable|image|max:2048',
            'frase_promocion' => 'nullable|string|max:255',
            'anio_promocion' => 'nullable|string|max:10',
        ]);

        // Fondo del login
        if ($request->hasFile('fondo_login')) {
            $path = $request->file('fondo_login')->store('configuraciones', 'public');
            Configuracion::set('fondo_login', $path);
        }

        // Escudo del colegio
        if ($request->hasFile('escudo_colegio')) {
            $path = $request->file('escudo_colegio')->store('configuraciones', 'public');
            Configuracion::set('escudo_colegio', $path);
        }

        // Escudo de la promoción
        if ($request->hasFile('escudo_promocion')) {
            $path = $request->file('escudo_promocion')->store('configuraciones', 'public');
            Configuracion::set('escudo_promocion', $path);
        }

        // Frase de la promoción
        if ($request->filled('frase_promocion')) {
            Configuracion::set('frase_promocion', $request->frase_promocion);
        }

        // Año de la promoción
        if ($request->filled('anio_promocion')) {
            Configuracion::set('anio_promocion', $request->anio_promocion);
        }

        return redirect()->route('admin.configuracion.index')
            ->with('success', '✅ Configuración actualizada correctamente.');
    }
}