@extends('layouts.admin')

@section('title', 'Editar Curso')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-edit"></i>
                    EDITAR CURSO
                </h1>
                <p class="text-blue-200 text-sm mt-1">Actualiza la información del curso</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. GRAL. JOSÉ DE SAN MARTÍN
                    </span>
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        Gestión {{ $curso->gestion }}
                    </span>
                    <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-book"></i>
                        {{ $curso->nombre }} {{ $curso->paralelo }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.cursos.index') }}" 
               class="bg-white/10 border border-white/30 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-semibold text-sm shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4">
            <div class="flex items-start space-x-3">
                <i class="fi fi-sr-cross-circle text-xl"></i>
                <div>
                    <p class="font-semibold">Hay errores en el formulario:</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- FORMULARIO -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.cursos.update', $curso->id) }}">
            @csrf @method('PUT')

            <!-- Información del curso -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl border border-blue-200 dark:border-blue-800">
                <div class="flex items-center space-x-2 text-blue-700 dark:text-blue-300">
                    <i class="fi fi-sr-info text-xl"></i>
                    <span class="font-semibold">Curso: {{ $curso->nombre }} {{ $curso->paralelo }}</span>
                </div>
            </div>

            <!-- Nombre del Curso -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="fi fi-sr-book text-blue-600 dark:text-blue-400"></i>
                    Nombre del Curso <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nombre" 
                       value="{{ old('nombre', $curso->nombre) }}" 
                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nombre') border-red-500 @enderror"
                       placeholder="Ej: 1ro de Secundaria" 
                       required>
                @error('nombre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Paralelo y Nivel -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-font-case text-blue-600 dark:text-blue-400"></i>
                        Paralelo <span class="text-red-500">*</span>
                    </label>
                    <select name="paralelo" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('paralelo') border-red-500 @enderror" required>
                        <option value="A" {{ old('paralelo', $curso->paralelo) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('paralelo', $curso->paralelo) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('paralelo', $curso->paralelo) == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('paralelo', $curso->paralelo) == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ old('paralelo', $curso->paralelo) == 'E' ? 'selected' : '' }}>E</option>
                        <option value="F" {{ old('paralelo', $curso->paralelo) == 'F' ? 'selected' : '' }}>F</option>
                    </select>
                    @error('paralelo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-chart-histogram text-blue-600 dark:text-blue-400"></i>
                        Nivel <span class="text-red-500">*</span>
                    </label>
                    <select name="nivel" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nivel') border-red-500 @enderror" required>
                        <option value="1" {{ old('nivel', $curso->nivel) == 1 ? 'selected' : '' }}>1ro de Secundaria</option>
                        <option value="2" {{ old('nivel', $curso->nivel) == 2 ? 'selected' : '' }}>2do de Secundaria</option>
                        <option value="3" {{ old('nivel', $curso->nivel) == 3 ? 'selected' : '' }}>3ro de Secundaria</option>
                        <option value="4" {{ old('nivel', $curso->nivel) == 4 ? 'selected' : '' }}>4to de Secundaria</option>
                        <option value="5" {{ old('nivel', $curso->nivel) == 5 ? 'selected' : '' }}>5to de Secundaria</option>
                        <option value="6" {{ old('nivel', $curso->nivel) == 6 ? 'selected' : '' }}>6to de Secundaria</option>
                    </select>
                    @error('nivel')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Gestión -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="fi fi-sr-calendar text-blue-600 dark:text-blue-400"></i>
                    Gestión <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="gestion" 
                       value="{{ old('gestion', $curso->gestion) }}" 
                       class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('gestion') border-red-500 @enderror"
                       placeholder="2026" 
                       required>
                @error('gestion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 flex items-center gap-1">
                    <i class="fi fi-sr-bulb"></i>
                    Año lectivo actual
                </p>
            </div>

            <!-- Estadísticas del curso -->
            <div class="mt-6 grid grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Estudiantes</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $curso->estudiantes ? $curso->estudiantes->count() : 0 }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Creado</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $curso->created_at ? $curso->created_at->format('d/m/Y') : '-' }}</p>
                </div>
            </div>

            <!-- Advertencia -->
            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 rounded-xl">
                <div class="flex items-start space-x-3">
                    <i class="fi fi-sr-triangle-warning text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    <div>
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300 text-sm">¡Importante!</p>
                        <p class="text-yellow-700 dark:text-yellow-400 text-sm">Al modificar el curso, los estudiantes asignados a este curso se actualizarán automáticamente.</p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.cursos.index') }}" 
                   class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-semibold text-center shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <i class="fi fi-sr-undo"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-md hover:shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fi fi-sr-disk"></i>
                    Actualizar Curso
                </button>
            </div>
        </form>
    </div>

    <!-- LEYENDA / CONSEJOS -->
    <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="text-gray-600 dark:text-gray-300 font-medium flex items-center gap-1">
                <i class="fi fi-sr-info"></i>
                Consejos:
            </span>
            <span class="flex items-center space-x-2">
                <span class="inline-block w-3 h-3 bg-blue-600 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Completa todos los campos obligatorios (*)</span>
            </span>
            <span class="flex items-center space-x-2">
                <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Los cambios afectan a los estudiantes del curso</span>
            </span>
            <span class="flex items-center space-x-2">
                <span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Puedes gestionar estudiantes después</span>
            </span>
        </div>
    </div>

</div>
@endsection