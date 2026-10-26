@extends('layouts.admin')

@section('title', 'Registrar Nuevo Usuario')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-user-add"></i>
                    REGISTRAR NUEVO USUARIO
                </h1>
                <p class="text-blue-200 text-sm mt-1">Complete todos los campos para registrar un nuevo usuario</p>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MENSAJES DE ERROR                          -->
    <!-- ========================================== -->
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4 flex items-center gap-2 shadow-lg">
            <i class="fi fi-sr-cross-circle text-2xl"></i>
            <span class="font-semibold">{{ session('error') }}</span>
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

    <form method="POST" action="{{ route('admin.usuarios.store') }}" enctype="multipart/form-data" id="registroForm" onsubmit="alert('¡El formulario se está enviando al servidor!');">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">

            <!-- ROL DE USUARIO -->
            <div class="mb-6">
                <label class="block text-sm font-black text-blue-900 dark:text-blue-200 mb-2 tracking-wide flex items-center gap-2">
                    <i class="fi fi-sr-user-gear"></i>
                    ROL DE USUARIO
                </label>
                <select name="rol" id="rol" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    <option value="">Seleccione un rol</option>
                    <option value="estudiante" {{ old('rol') == 'estudiante' ? 'selected' : '' }}>ESTUDIANTE</option>
                    <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>DOCENTE</option>
                    <option value="padre" {{ old('rol') == 'padre' ? 'selected' : '' }}>PADRE DE FAMILIA</option>
                    <option value="regente" {{ old('rol') == 'regente' ? 'selected' : '' }}>REGENTE</option>
                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>ADMINISTRADOR</option>
                </select>
            </div>

            <!-- ============================================ -->
            <!-- DATOS DEL PADRE/TUTOR (Solo para estudiantes) -->
            <!-- ============================================ -->
            <div id="padre-container" class="mt-4 {{ old('rol') == 'estudiante' ? '' : 'hidden' }}">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-family"></i>
                    DATOS DEL PADRE/TUTOR
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Estos datos serán usados para la cuenta del padre.</p>
                
                <div id="padres-container">
                    <!-- Padre #1 -->
                    <div class="padre-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fi fi-sr-user"></i>
                                Padre/Madre #1
                            </h4>
                            <button type="button" onclick="this.closest('.padre-item').remove()" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                                <i class="fi fi-sr-trash"></i>
                                Eliminar
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                                <input type="text" name="padres[0][nombre]" value="{{ old('padres.0.nombre') }}" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                                <input type="text" name="padres[0][apellidos]" value="{{ old('padres.0.apellidos') }}" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="padres[0][email]" value="{{ old('padres.0.email') }}" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                                <input type="text" name="padres[0][telefono]" value="{{ old('padres.0.telefono') }}" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relación</label>
                                <select name="padres[0][relacion]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="padre">Padre</option>
                                    <option value="madre">Madre</option>
                                    <option value="tutor">Tutor</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                    <i class="fi fi-sr-lock"></i>
                                    Contraseña
                                </label>
                                <div class="relative">
                                    <input type="password" name="padres[0][password]" id="password_padre_0" 
                                           class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <button type="button" onclick="togglePassword('password_padre_0')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600">
                                        <i class="fi fi-sr-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN AGREGAR OTRO PADRE -->
                <button type="button" onclick="agregarPadre()" class="text-blue-700 dark:text-blue-400 hover:text-blue-900 text-sm font-semibold flex items-center gap-2">
                    <i class="fi fi-sr-add"></i>
                    Agregar otro padre/madre
                </button>
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i class="fi fi-sr-info"></i>
                    Útil cuando los padres no viven juntos
                </p>
            </div>

            <!-- ============================================ -->
            <!-- DATOS PERSONALES (Para Docente, Padre, Admin, Regente) -->
            <!-- ============================================ -->
            <div id="datos-personales-container" class="mt-4 {{ old('rol') == 'estudiante' ? 'hidden' : '' }}">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    DATOS PERSONALES
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">C.I.</label>
                        <input type="text" name="ci" value="{{ old('ci') }}" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                        <i class="fi fi-sr-phone"></i>
                        DATOS DE CONTACTO
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" 
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" 
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Dirección...">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                        <i class="fi fi-sr-lock"></i>
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password_docente" 
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <button type="button" onclick="togglePassword('password_docente')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600">
                            <i class="fi fi-sr-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- FOTO PRINCIPAL -->
            <!-- ============================================ -->
            <div class="mt-6">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-camera"></i>
                    FOTOGRAFÍA
                </h3>
                <div class="flex items-center space-x-4">
                    <div class="foto-preview w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden border-2 border-gray-300 dark:border-gray-600">
                        <i class="fi fi-sr-picture text-4xl text-gray-400"></i>
                    </div>
                    <div>
                        <input type="file" name="foto" accept="image/*" onchange="previewFoto(this)"
                               class="border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                            <i class="fi fi-sr-info"></i>
                            Subir foto (opcional)
                        </p>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- HERMANOS (ESTUDIANTES) -->
            <!-- ============================================ -->
            <div id="hermanos-container-section" class="mt-6 {{ old('rol') == 'estudiante' ? '' : 'hidden' }}">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-users"></i>
                    ESTUDIANTES (HERMANOS)
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Agrega todos los hijos de este padre.</p>
                <div id="hermanos-container">
                    <div class="hermano-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fi fi-sr-user"></i>
                                Estudiante #1
                            </h4>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                                <input type="text" name="hermanos[0][nombre]" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                                <input type="text" name="hermanos[0][apellidos]" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CI</label>
                                <input type="text" name="hermanos[0][ci]" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Nac.</label>
                                <input type="date" name="hermanos[0][fecha_nacimiento]" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                                <select name="hermanos[0][curso]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Seleccione</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }} {{ $curso->paralelo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">N° Lista</label>
                                <input type="number" name="hermanos[0][numero_lista]" 
                                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" min="1">
                            </div>
                            <!-- FOTO DEL ESTUDIANTE -->
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                                    <i class="fi fi-sr-camera"></i>
                                    Foto del Estudiante
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="foto-preview w-16 h-16 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center overflow-hidden border-2 border-gray-300 dark:border-gray-500">
                                        <i class="fi fi-sr-picture text-2xl text-gray-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="hermanos[0][foto]" accept="image/*" 
                                               onchange="previewFoto(this)"
                                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm">
                                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fi fi-sr-info"></i>
                                            Foto opcional (JPG, PNG)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="agregarHermano()" class="text-blue-700 dark:text-blue-400 hover:text-blue-900 text-sm font-semibold flex items-center gap-2">
                    <i class="fi fi-sr-add"></i>
                    Agregar otro hermano
                </button>
            </div>

            <!-- ============================================ -->
            <!-- DATOS PROFESIONALES (Solo docentes) -->
            <!-- ============================================ -->
            <div id="profesional-container" class="mt-6 {{ old('rol') == 'docente' ? '' : 'hidden' }}">
                <h3 class="font-black text-blue-900 dark:text-blue-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-chalkboard-user"></i>
                    DATOS PROFESIONALES
                </h3>
                
                <div id="materias-container">
                    <!-- Materia #1 -->
                    <div class="materia-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fi fi-sr-book"></i>
                                Materia #1
                            </h4>
                            <button type="button" onclick="this.closest('.materia-item').remove()" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                                <i class="fi fi-sr-trash"></i>
                                Eliminar
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Materia</label>
                            <select name="materias[0]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Seleccione una materia</option>
                                <option value="Matemáticas">Matemáticas</option>
                                <option value="Física">Física</option>
                                <option value="Química">Química</option>
                                <option value="Lenguaje">Lenguaje</option>
                                <option value="Historia">Historia</option>
                                <option value="Geografía">Geografía</option>
                                <option value="Biología">Biología</option>
                                <option value="Educación Física">Educación Física</option>
                                <option value="Inglés">Inglés</option>
                                <option value="Arte">Arte</option>
                                <option value="Música">Música</option>
                                <option value="Religión">Religión</option>
                                <option value="Informática">Informática</option>
                                <option value="Filosofía">Filosofía</option>
                                <option value="Psicología">Psicología</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN AGREGAR OTRA MATERIA -->
                <button type="button" onclick="agregarMateria()" class="text-blue-700 dark:text-blue-400 hover:text-blue-900 text-sm font-semibold flex items-center gap-2">
                    <i class="fi fi-sr-add"></i>
                    Agregar otra materia
                </button>
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i class="fi fi-sr-info"></i>
                    El docente puede impartir varias materias
                </p>

                <div class="mt-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="es_encargado" value="1" {{ old('es_encargado') ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-blue-700 focus:ring-blue-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Es encargado/asesor de curso</span>
                    </label>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso al que será encargado</label>
                    <select name="curso_encargado" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Ninguno (docente de materia)</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_encargado') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nombre }} {{ $curso->paralelo }} - {{ $curso->gestion }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <i class="fi fi-sr-info"></i>
                        Al seleccionar un curso, el docente será el encargado/asesor de ese curso.
                    </p>
                </div>
            </div>

                        <!-- ============================================ -->
            <!-- BOTONES -->
            <!-- ============================================ -->
            <div class="mt-6 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center gap-2 font-semibold">
                    <i class="fi fi-sr-cross"></i>
                    Cancelar
                </a>
                
                <!-- ⚠️ BOTÓN MODIFICADO PARA FORZAR ENVÍO -->
                <button type="button" onclick="enviarFormularioForzado()" 
                        class="px-6 py-2.5 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold shadow-md hover:shadow-lg flex items-center gap-2">
                    <i class="fi fi-sr-disk"></i>
                    Registrar Usuario
                </button>
            </div>
        </div>
    </form>
</div>

<script>



function enviarFormularioForzado() {
        const form = document.getElementById('registroForm');
        // Quitamos la validación del navegador
        form.setAttribute('novalidate', 'true');
        // Enviamos a la fuerza
        form.submit();
    }
    // ============================================
    // AGREGAR PADRE (MÚLTIPLES PADRES)
    // ============================================
    let padreCount = 1;

    function agregarPadre() {
        padreCount++;
        const container = document.getElementById('padres-container');
        if (!container) return;
        
        const div = document.createElement('div');
        div.className = 'padre-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-4';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    Padre/Madre #${padreCount}
                </h4>
                <button type="button" onclick="this.closest('.padre-item').remove()" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                    <i class="fi fi-sr-trash"></i>
                    Eliminar
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input type="text" name="padres[${padreCount-1}][nombre]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                    <input type="text" name="padres[${padreCount-1}][apellidos]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="padres[${padreCount-1}][email]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                    <input type="text" name="padres[${padreCount-1}][telefono]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relación</label>
                    <select name="padres[${padreCount-1}][relacion]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="padre">Padre</option>
                        <option value="madre">Madre</option>
                        <option value="tutor">Tutor</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                        <i class="fi fi-sr-lock"></i>
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" name="padres[${padreCount-1}][password]" id="password_padre_${padreCount-1}" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <button type="button" onclick="togglePassword('password_padre_${padreCount-1}')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600">
                            <i class="fi fi-sr-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // ============================================
    // AGREGAR MATERIA (MÚLTIPLES MATERIAS)
    // ============================================
    let materiaCount = 1;

    function agregarMateria() {
        materiaCount++;
        const container = document.getElementById('materias-container');
        if (!container) return;
        
        const div = document.createElement('div');
        div.className = 'materia-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-4';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fi fi-sr-book"></i>
                    Materia #${materiaCount}
                </h4>
                <button type="button" onclick="this.closest('.materia-item').remove()" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                    <i class="fi fi-sr-trash"></i>
                    Eliminar
                </button>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Materia</label>
                <select name="materias[${materiaCount-1}]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Seleccione una materia</option>
                    <option value="Matemáticas">Matemáticas</option>
                    <option value="Física">Física</option>
                    <option value="Química">Química</option>
                    <option value="Lenguaje">Lenguaje</option>
                    <option value="Historia">Historia</option>
                    <option value="Geografía">Geografía</option>
                    <option value="Biología">Biología</option>
                    <option value="Educación Física">Educación Física</option>
                    <option value="Inglés">Inglés</option>
                    <option value="Arte">Arte</option>
                    <option value="Música">Música</option>
                    <option value="Religión">Religión</option>
                    <option value="Informática">Informática</option>
                    <option value="Filosofía">Filosofía</option>
                    <option value="Psicología">Psicología</option>
                </select>
            </div>
        `;
        container.appendChild(div);
    }

    // ============================================
    // MOSTRAR/OCULTAR CAMPOS SEGÚN ROL
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const rol = document.getElementById('rol');
        if (!rol) return;

        rol.addEventListener('change', function() {
            const padre = document.getElementById('padre-container');
            const datos = document.getElementById('datos-personales-container');
            const profesional = document.getElementById('profesional-container');
            const hermanos = document.getElementById('hermanos-container-section');

            if (padre) padre.classList.add('hidden');
            if (datos) datos.classList.add('hidden');
            if (profesional) profesional.classList.add('hidden');
            if (hermanos) hermanos.classList.add('hidden');

            if (this.value === 'estudiante') {
                if (padre) padre.classList.remove('hidden');
                if (hermanos) hermanos.classList.remove('hidden');
            } else if (this.value === 'docente') {
                if (datos) datos.classList.remove('hidden');
                if (profesional) profesional.classList.remove('hidden');
            } else if (this.value === 'padre' || this.value === 'admin' || this.value === 'regente') {
                if (datos) datos.classList.remove('hidden');
            }
        });

        rol.dispatchEvent(new Event('change'));
    });

    // ============================================
    // TOGGLE CONTRASEÑA
    // ============================================
    function togglePassword(id) {
        const input = document.getElementById(id);
        if (!input) return;
        const btn = input.parentElement.querySelector('button');
        if (!btn) return;
        const icon = btn.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.className = 'fi fi-sr-eye-crossed';
        } else {
            input.type = 'password';
            if (icon) icon.className = 'fi fi-sr-eye';
        }
    }

    // ============================================
    // AGREGAR HERMANOS
    // ============================================
    let hermanoCount = 1;

    function agregarHermano() {
        hermanoCount++;
        const container = document.getElementById('hermanos-container');
        if (!container) return;
        
        const div = document.createElement('div');
        div.className = 'hermano-item bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-4';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    Estudiante #${hermanoCount}
                </h4>
                <button type="button" onclick="this.closest('.hermano-item').remove()" class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                    <i class="fi fi-sr-trash"></i>
                    Eliminar
                </button>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input type="text" name="hermanos[${hermanoCount-1}][nombre]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                    <input type="text" name="hermanos[${hermanoCount-1}][apellidos]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CI</label>
                    <input type="text" name="hermanos[${hermanoCount-1}][ci]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Nac.</label>
                    <input type="date" name="hermanos[${hermanoCount-1}][fecha_nacimiento]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                    <select name="hermanos[${hermanoCount-1}][curso]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Seleccione</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }} {{ $curso->paralelo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">N° Lista</label>
                    <input type="number" name="hermanos[${hermanoCount-1}][numero_lista]" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" min="1">
                </div>
                <!-- FOTO DEL ESTUDIANTE -->
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                        <i class="fi fi-sr-camera"></i>
                        Foto del Estudiante
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="foto-preview w-16 h-16 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center overflow-hidden border-2 border-gray-300 dark:border-gray-500">
                            <i class="fi fi-sr-picture text-2xl text-gray-400"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="hermanos[${hermanoCount-1}][foto]" accept="image/*" 
                                   onchange="previewFoto(this)"
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm">
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <i class="fi fi-sr-info"></i>
                                Foto opcional (JPG, PNG)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // ============================================
    // PREVIEW DE FOTO
    // ============================================
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = input.closest('.flex').querySelector('.foto-preview');
            
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection