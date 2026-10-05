@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-user-edit"></i>
                    EDITAR USUARIO
                </h1>
                <p class="text-blue-200 text-sm mt-1">Modifique los datos del usuario seleccionado</p>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 flex items-center gap-2">
            <i class="fi fi-sr-cross-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fi fi-sr-triangle-warning"></i>
                <span class="font-semibold">Hay errores en el formulario:</span>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- ========================================== -->
            <!-- DATOS DEL USUARIO -->
            <!-- ========================================== -->
            <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                <i class="fi fi-sr-user"></i>
                DATOS DEL USUARIO
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" 
                           class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" 
                           class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">C.I.</label>
                <input type="text" name="ci" value="{{ old('ci', $usuario->ci) }}" 
                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                <select name="rol" id="rol" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="docente" {{ old('rol', $usuario->rol) === 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="padre" {{ old('rol', $usuario->rol) === 'padre' ? 'selected' : '' }}>Padre de Familia</option>
                    <option value="estudiante" {{ old('rol', $usuario->rol) === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                    <option value="regente" {{ old('rol', $usuario->rol) === 'regente' ? 'selected' : '' }}>Regente</option>
                </select>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="estado" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="activo" {{ old('estado', $usuario->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $usuario->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="suspendido" {{ old('estado', $usuario->estado) === 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                </select>
            </div>

            <!-- ========================================== -->
            <!-- SECCIÓN: ESTUDIANTE -->
            <!-- ========================================== -->
            <div id="seccion-estudiante" class="mt-6 {{ $usuario->rol === 'estudiante' ? '' : 'hidden' }}">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-graduation-cap"></i>
                    DATOS DEL ESTUDIANTE
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento ?? '') }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">N° de Lista</label>
                        <input type="number" name="numero_lista" value="{{ old('numero_lista', $estudiante->numero_lista ?? '') }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" min="1">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $estudiante->direccion ?? '') }}" 
                           class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                    <select name="curso" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Seleccione un curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso', $estudiante->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nombre }} {{ $curso->paralelo }} - {{ $curso->gestion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DATOS DEL PADRE/TUTOR -->
                <div class="mt-5">
                    <h4 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                        <i class="fi fi-sr-family"></i>
                        DATOS DEL PADRE/TUTOR
                    </h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Padre</label>
                            <input type="text" name="padre_nombre" value="{{ old('padre_nombre', $estudiante->padre->nombre ?? '') }}" 
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono del Padre</label>
                            <input type="text" name="padre_telefono" value="{{ old('padre_telefono', $estudiante->padre->telefono ?? '') }}" 
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email del Padre</label>
                            <input type="email" name="padre_email" value="{{ old('padre_email', $estudiante->padre->email_secundario ?? '') }}" 
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SECCIÓN: DOCENTE -->
            <!-- ========================================== -->
            <div id="seccion-docente" class="mt-6 {{ $usuario->rol === 'docente' ? '' : 'hidden' }}">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-chalkboard-user"></i>
                    DATOS DEL DOCENTE
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                        <select name="materia" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Seleccione una materia</option>
                            @foreach(['Matemáticas', 'Física', 'Química', 'Lenguaje', 'Historia', 'Geografía', 'Biología', 'Educación Física', 'Inglés', 'Arte', 'Música', 'Religión'] as $mat)
                                <option value="{{ $mat }}" {{ old('materia', $docente->especialidad ?? '') == $mat ? 'selected' : '' }}>{{ $mat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="es_encargado" value="1" {{ old('es_encargado', $docente->es_encargado ?? false) ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-blue-700 focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Es encargado/asesor de curso</span>
                        </label>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso encargado</label>
                    <select name="curso_encargado" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Ninguno</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_encargado', $docente->curso_encargado_id ?? '') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nombre }} {{ $curso->paralelo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- DATOS DE CONTACTO -->
            <!-- ========================================== -->
            <div class="mt-6">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-phone"></i>
                    DATOS DE CONTACTO
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $usuario->email) }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- CONTRASEÑA -->
            <!-- ========================================== -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                    <i class="fi fi-sr-lock"></i>
                    Nueva Contraseña (dejar en blanco para no cambiar)
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" 
                           class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                           placeholder="Nueva contraseña...">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600">
                        <i class="fi fi-sr-eye"></i>
                    </button>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- FOTO -->
            <!-- ========================================== -->
            <div class="mt-6">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-camera"></i>
                    FOTOGRAFÍA
                </h3>
                @if($usuario->foto)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $usuario->foto) }}" alt="Foto" class="h-20 w-20 rounded-full object-cover border-4 border-blue-300 shadow-lg">
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*" 
                       class="border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i class="fi fi-sr-info"></i>
                    Subir nueva foto (opcional)
                </p>
            </div>

            <!-- ========================================== -->
            <!-- BOTONES -->
            <!-- ========================================== -->
            <div class="mt-6 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center gap-2 font-semibold">
                    <i class="fi fi-sr-cross"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold shadow-md hover:shadow-lg flex items-center gap-2">
                    <i class="fi fi-sr-disk"></i>
                    Actualizar Usuario
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // ============================================
    // MOSTRAR/OCULTAR CAMPOS SEGÚN ROL
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const rol = document.getElementById('rol');
        const seccionEstudiante = document.getElementById('seccion-estudiante');
        const seccionDocente = document.getElementById('seccion-docente');

        rol.addEventListener('change', function() {
            seccionEstudiante.classList.add('hidden');
            seccionDocente.classList.add('hidden');

            if (this.value === 'estudiante') {
                seccionEstudiante.classList.remove('hidden');
            } else if (this.value === 'docente') {
                seccionDocente.classList.remove('hidden');
            }
        });

        rol.dispatchEvent(new Event('change'));
    });

    // ============================================
    // TOGGLE CONTRASEÑA
    // ============================================
    function togglePassword() {
        const input = document.getElementById('password');
        const btn = event.currentTarget;
        const icon = btn.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fi fi-sr-eye-crossed';
        } else {
            input.type = 'password';
            icon.className = 'fi fi-sr-eye';
        }
    }
</script>
@endsection