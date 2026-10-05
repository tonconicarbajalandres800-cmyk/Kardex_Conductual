@extends('layouts.admin')

@section('title', 'Editar Citación')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-edit"></i>
                    EDITAR CITACIÓN
                </h1>
                <p class="text-blue-200 text-sm mt-1">Modifica los datos de la citación #{{ $citacion->id }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. GRAL. JOSÉ DE SAN MARTÍN
                    </span>
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        Gestión {{ date('Y') }}
                    </span>
                    <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-megaphone"></i>
                        Citación #{{ $citacion->id }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.citaciones.index') }}" 
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
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('admin.citaciones.update', $citacion->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Estudiante -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-user text-blue-600 dark:text-blue-400"></i>
                        Estudiante <span class="text-red-500">*</span>
                    </label>
                    <select name="estudiante_id" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('estudiante_id') border-red-500 @enderror" required>
                        <option value="">Seleccionar estudiante</option>
                        @foreach($estudiantes as $estudiante)
                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id', $citacion->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                {{ $estudiante->nombre }} {{ $estudiante->apellido }} - {{ $estudiante->curso->nombre ?? 'Sin curso' }} {{ $estudiante->curso->paralelo ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('estudiante_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Docente -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-chalkboard-user text-blue-600 dark:text-blue-400"></i>
                        Docente <span class="text-red-500">*</span>
                    </label>
                    <select name="docente_id" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('docente_id') border-red-500 @enderror" required>
                        <option value="">Seleccionar docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ old('docente_id', $citacion->docente_id) == $docente->id ? 'selected' : '' }}>
                                {{ $docente->user->nombre ?? '' }} {{ $docente->user->apellidos ?? '' }} - {{ $docente->especialidad ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('docente_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha Programada -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-calendar text-blue-600 dark:text-blue-400"></i>
                        Fecha Programada <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="fecha_programada" 
                           value="{{ old('fecha_programada', \Carbon\Carbon::parse($citacion->fecha_programada)->format('Y-m-d\TH:i')) }}"
                           class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('fecha_programada') border-red-500 @enderror" 
                           required>
                    @error('fecha_programada')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-chart-histogram text-blue-600 dark:text-blue-400"></i>
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="estado" class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('estado') border-red-500 @enderror" required>
                        <option value="pendiente" {{ old('estado', $citacion->estado) == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                        <option value="confirmada" {{ old('estado', $citacion->estado) == 'confirmada' ? 'selected' : '' }}>✅ Confirmada</option>
                        <option value="reprogramada" {{ old('estado', $citacion->estado) == 'reprogramada' ? 'selected' : '' }}>🔄 Reprogramada</option>
                        <option value="justificada" {{ old('estado', $citacion->estado) == 'justificada' ? 'selected' : '' }}>📝 Justificada</option>
                        <option value="cancelada" {{ old('estado', $citacion->estado) == 'cancelada' ? 'selected' : '' }}>❌ Cancelada</option>
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Motivo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fi fi-sr-document text-blue-600 dark:text-blue-400"></i>
                        Motivo <span class="text-red-500">*</span>
                    </label>
                    <textarea name="motivo" 
                              rows="4" 
                              class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('motivo') border-red-500 @enderror" 
                              placeholder="Describe el motivo de la citación..."
                              required>{{ old('motivo', $citacion->motivo) }}</textarea>
                    @error('motivo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl border border-blue-200 dark:border-blue-800">
                <div class="flex items-start space-x-3">
                    <i class="fi fi-sr-bulb text-2xl text-blue-600 dark:text-blue-400"></i>
                    <div>
                        <p class="font-semibold text-blue-800 dark:text-blue-300 text-sm">Información de la citación</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2 text-sm">
                            <p class="text-blue-700 dark:text-blue-400 flex items-center gap-2">
                                <i class="fi fi-sr-clock"></i>
                                Creada: <strong>{{ $citacion->created_at ? $citacion->created_at->format('d/m/Y H:i') : '-' }}</strong>
                            </p>
                            @if($citacion->updated_at)
                                <p class="text-blue-700 dark:text-blue-400 flex items-center gap-2">
                                    <i class="fi fi-sr-refresh"></i>
                                    Actualizada: <strong>{{ $citacion->updated_at->format('d/m/Y H:i') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.citaciones.index') }}" 
                   class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-semibold text-center shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <i class="fi fi-sr-undo"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-md hover:shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fi fi-sr-disk"></i>
                    Actualizar Citación
                </button>
            </div>
        </form>
    </div>

</div>
@endsection