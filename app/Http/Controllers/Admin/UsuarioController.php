<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Citacion;
use App\Models\Incidencia;
use App\Models\Padre;
use App\Models\User;
use App\Models\FotoEstudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['docente', 'estudiante', 'estudiante.fotos']);

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'ILIKE', "%{$search}%")
                  ->orWhere('apellidos', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('ci', 'ILIKE', "%{$search}%");
            });
        }

        $usuarios = $query->orderBy('id', 'desc')->paginate(15);
        $roles = ['admin', 'docente', 'padre', 'estudiante', 'regente']; // AGREGADO regente
        $estados = ['activo', 'inactivo', 'suspendido'];

        $cursos = Curso::orderBy('nombre')->orderBy('paralelo')->get();
        
        return view('admin.usuarios.index', compact('usuarios', 'roles', 'estados', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::all();
        $padres = Padre::with('user')->get(); 
        return view('admin.usuarios.create', compact('cursos', 'padres'));
    }

    public function store(Request $request)
    {
        if ($request->rol === 'estudiante') {
            $validated = $request->validate([
                'rol' => 'required|in:estudiante',
                'padre_nombre' => 'required|string|max:255',
                'padre_apellidos' => 'nullable|string|max:255',
                'padre_telefono' => 'required|string|max:20',
                'padre_email' => 'required|email|max:255',
                'password' => 'nullable|min:8',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'hermanos' => 'nullable|array',
                'hermanos.*.nombre' => 'required|string|max:255',
                'hermanos.*.apellidos' => 'required|string|max:255',
                'hermanos.*.ci' => 'required|string|max:20',
                'hermanos.*.fecha_nacimiento' => 'nullable|date',
                'hermanos.*.curso' => 'nullable|exists:cursos,id',
                'hermanos.*.numero_lista' => 'nullable|integer|min:1',
            ]);

            $padreExistente = User::where('email', $request->padre_email)->first();
            if ($padreExistente) {
                return back()->with('error', '❌ El email del padre ya está registrado. Usa otro email o asigna un padre existente.')
                    ->withInput();
            }

            if ($request->has('hermanos') && is_array($request->hermanos)) {
                foreach ($request->hermanos as $hijo) {
                    $estudianteExistente = User::where('ci', $hijo['ci'])->first();
                    if ($estudianteExistente) {
                        return back()->with('error', '❌ El CI del estudiante ' . $hijo['nombre'] . ' ya está registrado. Usa otro CI.')
                            ->withInput();
                    }
                }
            }

        } else {
            // ⭐⭐⭐ VALIDACIÓN CON REGENTE ⭐⭐⭐
            $validated = $request->validate([
                'rol' => 'required|in:admin,docente,padre,regente',
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'ci' => 'required|string|max:20|unique:users',
                'email' => 'required|email|unique:users',
                'telefono' => 'required|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'password' => 'nullable|min:8',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'materia' => 'nullable|string|max:100',
                'es_encargado' => 'nullable|boolean',
                'curso_encargado' => 'nullable|exists:cursos,id',
            ]);
        }

        // ==========================================
        // 1. ROL: ESTUDIANTE
        // ==========================================
        if ($request->rol === 'estudiante') {
            $userPadre = User::create([
                'nombre' => $request->padre_nombre,
                'apellidos' => $request->padre_apellidos ?? '',
                'ci' => 'P' . uniqid(),
                'rol' => 'padre',
                'email' => $request->padre_email,
                'telefono' => $request->padre_telefono,
                'password' => Hash::make($request->password ?? 'password123'),
                'estado' => 'activo',
                'foto' => null,
            ]);

            $padre = Padre::create([
                'user_id' => $userPadre->id,
                'nombre' => $request->padre_nombre,
                'apellido' => $request->padre_apellidos ?? '',
                'telefono' => $request->padre_telefono,
                'email_secundario' => $request->padre_email,
            ]);

            if ($request->has('hermanos') && is_array($request->hermanos)) {
                foreach ($request->hermanos as $hijo) {
                    $userEst = User::create([
                        'nombre' => $hijo['nombre'],
                        'apellidos' => $hijo['apellidos'],
                        'ci' => $hijo['ci'],
                        'rol' => 'estudiante',
                        'email' => $hijo['ci'] . '@estudiante.com',
                        'telefono' => $request->padre_telefono,
                        'password' => Hash::make('password'),
                        'estado' => 'activo',
                        'foto' => null,
                    ]);

                    $estudiante = Estudiante::create([
                        'user_id' => $userEst->id,
                        'nombre' => $hijo['nombre'],
                        'apellido' => $hijo['apellidos'],
                        'ci' => $hijo['ci'],
                        'fecha_nacimiento' => $hijo['fecha_nacimiento'] ?? null,
                        'curso_id' => $hijo['curso'] ?? null,
                        'padre_id' => $padre->id,
                        'numero_lista' => $hijo['numero_lista'] ?? null,
                        'direccion' => $request->direccion ?? null,
                    ]);

                    if ($request->hasFile('foto')) {
                        $nombreArchivo = uniqid() . '_' . time() . '.' . $request->foto->extension();
                        $request->foto->move(public_path('images/storage/fotos'), $nombreArchivo);
                        FotoEstudiante::create([
                            'estudiante_id' => $estudiante->id,
                            'nombre_archivo' => $nombreArchivo,
                            'ruta' => 'images/storage/fotos/' . $nombreArchivo
                        ]);
                    }
                }
            }

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Padre y estudiante(s) creados correctamente.');
        }

        // ==========================================
        // 2. ROL: DOCENTE
        // ==========================================
        if ($request->rol === 'docente') {
            $user = User::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'rol' => 'docente',
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password ?? 'password123'),
                'estado' => 'activo',
                'foto' => $request->file('foto') ? $request->file('foto')->store('fotos', 'public') : null,
            ]);

            Docente::create([
                'user_id' => $user->id,
                'especialidad' => $request->materia ?? null,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'es_encargado' => $request->has('es_encargado') ? true : false,
                'curso_encargado_id' => $request->curso_encargado ?? null,
            ]);

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Docente creado correctamente.');
        }

        // ==========================================
        // 3. ROL: PADRE
        // ==========================================
        if ($request->rol === 'padre') {
            $user = User::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'rol' => 'padre',
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'foto' => $request->file('foto') ? $request->file('foto')->store('fotos', 'public') : null,
                'estado' => 'activo',
            ]);

            Padre::create([
                'user_id' => $user->id,
                'nombre' => $request->nombre,
                'apellido' => $request->apellidos,
                'telefono' => $request->telefono,
                'email_secundario' => $request->email,
            ]);

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Padre creado correctamente.');
        }

        // ==========================================
        // 4. ROL: ADMIN
        // ==========================================
        if ($request->rol === 'admin') {
            $user = User::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'rol' => 'admin',
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'foto' => $request->file('foto') ? $request->file('foto')->store('fotos', 'public') : null,
                'estado' => 'activo',
            ]);

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Administrador creado correctamente.');
        }

        // ==========================================
        // 5. ROL: REGENTE (NUEVO)
        // ==========================================
        if ($request->rol === 'regente') {
            $user = User::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'rol' => 'regente',
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'foto' => $request->file('foto') ? $request->file('foto')->store('fotos', 'public') : null,
                'estado' => 'activo',
            ]);

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Regente creado correctamente.');
        }

        return redirect()->route('admin.usuarios.index')
            ->with('error', '❌ Rol no válido.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $cursos = Curso::all();

        $estudiante = null;
        $docente = null;
        $padre = null;
        $regente = null;

        if ($usuario->rol === 'estudiante') {
            $estudiante = Estudiante::where('user_id', $usuario->id)->first();
        } elseif ($usuario->rol === 'docente') {
            $docente = Docente::where('user_id', $usuario->id)->first();
        } elseif ($usuario->rol === 'padre') {
            $padre = Padre::where('user_id', $usuario->id)->first();
        } elseif ($usuario->rol === 'regente') {
            $regente = $usuario;
        }

        return view('admin.usuarios.edit', compact('usuario', 'cursos', 'estudiante', 'docente', 'padre', 'regente'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        // ⭐⭐⭐ VALIDACIÓN CON REGENTE ⭐⭐⭐
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:users,ci,' . $usuario->id,
            'rol' => 'required|in:admin,docente,padre,estudiante,regente',
            'fecha_nacimiento' => 'nullable|date',
            'numero_lista' => 'nullable|integer|min:1',
            'direccion' => 'nullable|string|max:255',
            'curso' => 'nullable|exists:cursos,id',
            'materia' => 'nullable|string|max:100',
            'es_encargado' => 'nullable|boolean',
            'curso_encargado' => 'nullable|exists:cursos,id',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:8',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,webp,svg|max:5120',
            'estado' => 'nullable|in:activo,inactivo,suspendido',
            'padre_nombre' => 'nullable|string|max:255',
            'padre_telefono' => 'nullable|string|max:20',
            'padre_email' => 'nullable|email|max:255',
        ]);

        $data = $validated;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto')) {
            $nombreArchivo = uniqid() . '_' . time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images/storage/fotos'), $nombreArchivo);
            
            if ($usuario->rol === 'estudiante' && $usuario->estudiante) {
                FotoEstudiante::create([
                    'estudiante_id' => $usuario->estudiante->id,
                    'nombre_archivo' => $nombreArchivo,
                    'ruta' => 'images/storage/fotos/' . $nombreArchivo
                ]);
            }
            
            $data['foto'] = 'images/storage/fotos/' . $nombreArchivo;
        }

        $usuario->update($data);

        if ($validated['rol'] === 'estudiante') {
            $estudiante = Estudiante::where('user_id', $usuario->id)->first();
            if ($estudiante) {
                $estudiante->update([
                    'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                    'numero_lista' => $validated['numero_lista'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'curso_id' => $validated['curso'] ?? null,
                ]);

                if ($request->filled('padre_nombre')) {
                    if ($estudiante->padre) {
                        $padre = $estudiante->padre;
                        $padre->update([
                            'nombre' => $request->padre_nombre,
                            'telefono' => $request->padre_telefono,
                            'email_secundario' => $request->padre_email,
                        ]);
                    }
                }
            }
        }

        if ($validated['rol'] === 'docente') {
            $docente = Docente::where('user_id', $usuario->id)->first();
            if ($docente) {
                $docente->update([
                    'especialidad' => $validated['materia'] ?? null,
                    'telefono' => $validated['telefono'],
                    'email' => $validated['email'],
                    'es_encargado' => $request->has('es_encargado') ? true : false,
                    'curso_encargado_id' => $validated['curso_encargado'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', '✅ Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->rol === 'estudiante') {
            $estudiante = Estudiante::where('user_id', $usuario->id)->first();
            if ($estudiante) {
                $fotos = FotoEstudiante::where('estudiante_id', $estudiante->id)->get();
                foreach ($fotos as $foto) {
                    if (file_exists(public_path('images/storage/fotos/' . $foto->nombre_archivo))) {
                        unlink(public_path('images/storage/fotos/' . $foto->nombre_archivo));
                    }
                    $foto->delete();
                }
                Citacion::where('estudiante_id', $estudiante->id)->delete();
                Incidencia::where('estudiante_id', $estudiante->id)->delete();
                $estudiante->delete();
            }
        }

        if ($usuario->rol === 'padre') {
            $padre = Padre::where('user_id', $usuario->id)->first();
            if ($padre) {
                $estudiantes = Estudiante::where('padre_id', $padre->id)->get();
                foreach ($estudiantes as $est) {
                    $fotos = FotoEstudiante::where('estudiante_id', $est->id)->get();
                    foreach ($fotos as $foto) {
                        if (file_exists(public_path('images/storage/fotos/' . $foto->nombre_archivo))) {
                            unlink(public_path('images/storage/fotos/' . $foto->nombre_archivo));
                        }
                        $foto->delete();
                    }
                    Citacion::where('estudiante_id', $est->id)->delete();
                    Incidencia::where('estudiante_id', $est->id)->delete();
                    $est->delete();
                }
                $padre->delete();
            }
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', '✅ Usuario eliminado correctamente.');
    }
}