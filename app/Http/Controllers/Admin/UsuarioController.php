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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $roles = ['admin', 'docente', 'padre', 'estudiante', 'regente'];
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
        // ==========================================
        // VALIDACIÓN SEGÚN EL ROL
        // ==========================================
        if ($request->rol === 'estudiante') {
            $validated = $request->validate([
                'rol' => 'required|in:estudiante',
                'padres' => 'required|array|min:1',
                'padres.*.nombre' => 'required|string|max:255',
                'padres.*.apellidos' => 'nullable|string|max:255',
                'padres.*.email' => 'required|email|max:255',
                'padres.*.telefono' => 'required|string|max:20',
                'padres.*.relacion' => 'nullable|in:padre,madre,tutor,otro',
                'padres.*.password' => 'nullable|min:8',
                'hermanos' => 'required|array|min:1',
                'hermanos.*.nombre' => 'required|string|max:255',
                'hermanos.*.apellidos' => 'required|string|max:255',
                'hermanos.*.ci' => 'required|string|max:20',
                'hermanos.*.fecha_nacimiento' => 'nullable|date',
                'hermanos.*.curso' => 'nullable|exists:cursos,id',
                'hermanos.*.numero_lista' => 'nullable|integer|min:1',
                'hermanos.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'padres.required' => 'Debes registrar al menos un padre/madre.',
                'padres.*.nombre.required' => 'El nombre del padre es obligatorio.',
                'padres.*.email.required' => 'El email del padre es obligatorio.',
                'padres.*.email.email' => 'El email del padre no es válido.',
                'padres.*.telefono.required' => 'El teléfono del padre es obligatorio.',
                'hermanos.required' => 'Debes registrar al menos un estudiante.',
                'hermanos.*.nombre.required' => 'El nombre del estudiante es obligatorio.',
                'hermanos.*.apellidos.required' => 'Los apellidos del estudiante son obligatorios.',
                'hermanos.*.ci.required' => 'El CI del estudiante es obligatorio.',
                'hermanos.*.foto.image' => 'La foto del estudiante debe ser una imagen.',
                'hermanos.*.foto.max' => 'La foto del estudiante no debe superar 5MB.',
            ]);

            // Validar emails de padres únicos
            foreach ($request->padres ?? [] as $p) {
                if (User::where('email', $p['email'])->exists()) {
                    return back()->with('error', '❌ El email ' . $p['email'] . ' ya está registrado.')->withInput();
                }
            }

            // Validar CI de estudiantes únicos
            foreach ($request->hermanos ?? [] as $h) {
                if (User::where('ci', $h['ci'])->exists()) {
                    return back()->with('error', '❌ El CI ' . $h['ci'] . ' ya está registrado.')->withInput();
                }
            }

            // Validar CI duplicados
            $cis = collect($request->hermanos ?? [])->pluck('ci')->filter()->toArray();
            if (count($cis) !== count(array_unique($cis))) {
                return back()->with('error', '❌ Hay CI repetidos entre los estudiantes.')->withInput();
            }
        } else {
            $validated = $request->validate([
                'rol' => 'required|in:admin,docente,padre,regente',
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'ci' => 'required|string|max:20|unique:users',
                'email' => 'required|email|unique:users',
                'telefono' => 'required|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'password' => 'nullable|min:8',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'materias' => 'nullable|array', // ✅ VALIDACIÓN DE ARRAY
                'materias.*' => 'nullable|string|max:100',
                'es_encargado' => 'nullable|boolean',
                'curso_encargado' => 'nullable|exists:cursos,id',
            ]);
        }

        // ==========================================
        // 1. ROL: ESTUDIANTE
        // ==========================================
        if ($request->rol === 'estudiante') {
            DB::beginTransaction();
            try {
                $padresCreados = [];

                foreach ($request->padres ?? [] as $index => $p) {
                    $ciPadre = 'P' . substr(uniqid(), -10) . rand(10, 99);
                    $ciPadre = substr($ciPadre, 0, 20);

                    $userPadre = User::create([
                        'nombre' => $p['nombre'],
                        'apellidos' => $p['apellidos'] ?? '',
                        'ci' => $ciPadre,
                        'rol' => 'padre',
                        'email' => $p['email'],
                        'telefono' => $p['telefono'],
                        'password' => Hash::make($p['password'] ?? 'password123'),
                        'estado' => 'activo',
                        'foto' => null,
                    ]);

                    $padre = Padre::create([
                        'user_id' => $userPadre->id,
                        'nombre' => $p['nombre'],
                        'apellido' => $p['apellidos'] ?? '',
                        'telefono' => $p['telefono'],
                        'email_secundario' => $p['email'],
                    ]);

                    $padresCreados[] = $padre;
                }

                if (empty($padresCreados)) {
                    DB::rollBack();
                    return back()->with('error', '❌ Debes registrar al menos un padre/madre.')->withInput();
                }

                $padrePrincipal = $padresCreados[0];

                foreach ($request->hermanos ?? [] as $index => $hijo) {
                    $emailEstudiante = 'est.' . $hijo['ci'] . '.' . uniqid() . '@estudiante.com';
                    $emailEstudiante = substr($emailEstudiante, 0, 100);

                    $userEst = User::create([
                        'nombre' => $hijo['nombre'],
                        'apellidos' => $hijo['apellidos'],
                        'ci' => $hijo['ci'],
                        'rol' => 'estudiante',
                        'email' => $emailEstudiante,
                        'telefono' => $padrePrincipal->telefono ?? null,
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
                        'padre_id' => $padrePrincipal->id,
                        'numero_lista' => $hijo['numero_lista'] ?? null,
                        'direccion' => $request->direccion ?? null,
                    ]);

                    $rutaFoto = null;

                    if ($request->hasFile("hermanos.{$index}.foto")) {
                        $archivo = $request->file("hermanos.{$index}.foto");
                        $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                        $destino = public_path('images/storage/fotos');
                        if (!file_exists($destino)) mkdir($destino, 0777, true);
                        $archivo->move($destino, $nombreArchivo);
                        $rutaFoto = 'images/storage/fotos/' . $nombreArchivo;

                        FotoEstudiante::create([
                            'estudiante_id' => $estudiante->id,
                            'nombre_archivo' => $nombreArchivo,
                            'ruta' => $rutaFoto,
                        ]);

                        $userEst->update(['foto' => $rutaFoto]);
                    } elseif ($index === 0 && $request->hasFile('foto')) {
                        $archivo = $request->file('foto');
                        $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                        $destino = public_path('images/storage/fotos');
                        if (!file_exists($destino)) mkdir($destino, 0777, true);
                        $archivo->move($destino, $nombreArchivo);
                        $rutaFoto = 'images/storage/fotos/' . $nombreArchivo;

                        FotoEstudiante::create([
                            'estudiante_id' => $estudiante->id,
                            'nombre_archivo' => $nombreArchivo,
                            'ruta' => $rutaFoto,
                        ]);

                        $userEst->update(['foto' => $rutaFoto]);
                    }
                }

                DB::commit();
                return redirect()->route('admin.usuarios.index')->with('success', '✅ Padre(s) y estudiante(s) creados correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al crear estudiante: ' . $e->getMessage());
                return back()->with('error', '❌ Error al crear: ' . $e->getMessage())->withInput();
            }
        }

        // ==========================================
        // 2. ROL: DOCENTE
        // ==========================================
        if ($request->rol === 'docente') {
            DB::beginTransaction();
            try {
                $rutaFoto = null;
                if ($request->hasFile('foto')) {
                    $archivo = $request->file('foto');
                    $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                    $destino = public_path('images/storage/fotos');
                    if (!file_exists($destino)) mkdir($destino, 0777, true);
                    $archivo->move($destino, $nombreArchivo);
                    $rutaFoto = 'images/storage/fotos/' . $nombreArchivo;
                }

                $user = User::create([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'ci' => $request->ci,
                    'rol' => 'docente',
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion ?? null,
                    'password' => Hash::make($request->password ?? 'password123'),
                    'estado' => 'activo',
                    'foto' => $rutaFoto,
                ]);

                // ✅ LIMPIAMOS EL ARRAY Y LO GUARDAMOS
                $materiasFiltradas = [];
                if ($request->has('materias') && is_array($request->materias)) {
                    $materiasFiltradas = array_values(array_filter($request->materias, function($m) {
                        return !empty($m);
                    }));
                }

                Docente::create([
                    'user_id' => $user->id,
                    'especialidad' => $materiasFiltradas[0] ?? null, 
                    'materias' => $materiasFiltradas, // El modelo lo castea a JSON solo
                    'telefono' => $request->telefono,
                    'email' => $request->email,
                    'es_encargado' => $request->has('es_encargado') ? true : false,
                    'curso_encargado_id' => $request->curso_encargado ?? null,
                ]);

                DB::commit();
                return redirect()->route('admin.usuarios.index')->with('success', '✅ Docente creado correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al crear docente: ' . $e->getMessage());
                return back()->with('error', '❌ Error al crear docente: ' . $e->getMessage())->withInput();
            }
        }

        // ==========================================
        // 3. ROL: PADRE
        // ==========================================
        if ($request->rol === 'padre') {
            DB::beginTransaction();
            try {
                $rutaFoto = null;
                if ($request->hasFile('foto')) {
                    $archivo = $request->file('foto');
                    $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                    $destino = public_path('images/storage/fotos');
                    if (!file_exists($destino)) mkdir($destino, 0777, true);
                    $archivo->move($destino, $nombreArchivo);
                    $rutaFoto = 'images/storage/fotos/' . $nombreArchivo;
                }

                $user = User::create([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'ci' => $request->ci,
                    'rol' => 'padre',
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion ?? null,
                    'password' => Hash::make($request->password ?? 'password123'),
                    'foto' => $rutaFoto,
                    'estado' => 'activo',
                ]);

                Padre::create([
                    'user_id' => $user->id,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellidos,
                    'telefono' => $request->telefono,
                    'email_secundario' => $request->email,
                ]);

                DB::commit();
                return redirect()->route('admin.usuarios.index')->with('success', '✅ Padre creado correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al crear padre: ' . $e->getMessage());
                return back()->with('error', '❌ Error al crear padre: ' . $e->getMessage())->withInput();
            }
        }

        // ==========================================
        // 4. ROL: ADMIN
        // ==========================================
        if ($request->rol === 'admin') {
            try {
                $rutaFoto = null;
                if ($request->hasFile('foto')) {
                    $archivo = $request->file('foto');
                    $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                    $destino = public_path('images/storage/fotos');
                    if (!file_exists($destino)) mkdir($destino, 0777, true);
                    $archivo->move($destino, $nombreArchivo);
                    $rutaFoto = 'images/storage/fotos/' . $nombreArchivo;
                }

                User::create([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'ci' => $request->ci,
                    'rol' => 'admin',
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion ?? null,
                    'password' => Hash::make($request->password ?? 'password123'),
                    'foto' => $rutaFoto,
                    'estado' => 'activo',
                ]);

                return redirect()->route('admin.usuarios.index')->with('success', '✅ Administrador creado correctamente.');
            } catch (\Exception $e) {
                Log::error('Error al crear admin: ' . $e->getMessage());
                return back()->with('error', '❌ Error al crear administrador: ' . $e->getMessage())->withInput();
            }
        }

        // ==========================================
        // 5. ROL: REGENTE
        // ==========================================
        if ($request->rol === 'regente') {
            try {
                $rutaFoto = null;
                if ($request->hasFile('foto')) {
                    $archivo = $request->file('foto');
                    $nombreArchivo = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                    $destino = public_path('images/storage/fotos');
                    if (!file_exists($destino)) mkdir($destino, 0777, true);
                    $archivo->move($destino, $nombreArchivo);
                    $rutaFoto = 'images/storage/fotos/' . $nombreArchivo;
                }

                User::create([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'ci' => $request->ci,
                    'rol' => 'regente',
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion ?? null,
                    'password' => Hash::make($request->password ?? 'password123'),
                    'foto' => $rutaFoto,
                    'estado' => 'activo',
                ]);

                return redirect()->route('admin.usuarios.index')->with('success', '✅ Regente creado correctamente.');
            } catch (\Exception $e) {
                Log::error('Error al crear regente: ' . $e->getMessage());
                return back()->with('error', '❌ Error al crear regente: ' . $e->getMessage())->withInput();
            }
        }

        return redirect()->route('admin.usuarios.index')->with('error', '❌ Rol no válido.');
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

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:users,ci,' . $usuario->id,
            'rol' => 'required|in:admin,docente,padre,estudiante,regente',
            'fecha_nacimiento' => 'nullable|date',
            'numero_lista' => 'nullable|integer|min:1',
            'direccion' => 'nullable|string|max:255',
            'curso' => 'nullable|exists:cursos,id',
            'materias' => 'nullable|array', // ✅ VALIDACIÓN DE ARRAY
            'materias.*' => 'nullable|string|max:100',
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
            $destino = public_path('images/storage/fotos');
            if (!file_exists($destino)) mkdir($destino, 0777, true);
            $request->foto->move($destino, $nombreArchivo);

            if ($usuario->rol === 'estudiante' && $usuario->estudiante) {
                FotoEstudiante::create([
                    'estudiante_id' => $usuario->estudiante->id,
                    'nombre_archivo' => $nombreArchivo,
                    'ruta' => 'images/storage/fotos/' . $nombreArchivo,
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

                if ($request->filled('padre_nombre') && $estudiante->padre) {
                    $estudiante->padre->update([
                        'nombre' => $request->padre_nombre,
                        'telefono' => $request->padre_telefono,
                        'email_secundario' => $request->padre_email,
                    ]);
                }
            }
        }

        if ($validated['rol'] === 'docente') {
            $docente = Docente::where('user_id', $usuario->id)->first();
            if ($docente) {
                // ✅ ACTUALIZAMOS EL ARRAY DE MATERIAS
                $materiasFiltradas = [];
                if ($request->has('materias') && is_array($request->materias)) {
                    $materiasFiltradas = array_values(array_filter($request->materias, function($m) {
                        return !empty($m);
                    }));
                }

                $docente->update([
                    'especialidad' => $materiasFiltradas[0] ?? null,
                    'materias' => $materiasFiltradas, // El modelo lo guarda como JSON
                    'telefono' => $validated['telefono'],
                    'email' => $validated['email'],
                    'es_encargado' => $request->has('es_encargado') ? true : false,
                    'curso_encargado_id' => $validated['curso_encargado'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.usuarios.index')->with('success', '✅ Usuario actualizado correctamente.');
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

        return redirect()->route('admin.usuarios.index')->with('success', '✅ Usuario eliminado correctamente.');
    }
}