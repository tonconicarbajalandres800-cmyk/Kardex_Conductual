@extends('layouts.docente')

@section('title', 'Generar Citación')

@section('header', 'Generar Citación')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- ENCABEZADO CON COLOR INSTITUCIONAL -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fi fi-sr-document"></i>
                    GENERAR CITACIÓN
                </h1>
                <p class="text-blue-200 text-sm mt-1">Complete los datos para generar una citación</p>
            </div>
            <a href="{{ route('docente.curso.estudiantes', $estudiante->curso_id) }}" 
               class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver al Curso
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-check-circle"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-cross-circle"></i>
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    <!-- ============================================ -->
    <!-- PANTALLA DE CONFIRMACIÓN -->
    <!-- ============================================ -->
    @if(session('citacion_creada'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-2 border-emerald-500 rounded-2xl p-8 mb-6 text-center shadow-xl">
            <div class="mb-4">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-600 shadow-xl">
                    <i class="fi fi-sr-check-circle text-5xl text-white"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mb-2">¡Citación Creada Exitosamente!</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">La citación ha sido generada y notificada al padre de familia.</p>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 mb-4 text-left max-w-md mx-auto shadow-sm border border-emerald-200 dark:border-emerald-800">
                <p class="flex items-center gap-2 mb-1">
                    <i class="fi fi-sr-document text-emerald-600"></i>
                    <span class="font-semibold">Motivo:</span> {{ session('citacion_motivo') }}
                </p>
                <p class="flex items-center gap-2 mb-1">
                    <i class="fi fi-sr-calendar text-emerald-600"></i>
                    <span class="font-semibold">Fecha:</span> {{ session('citacion_fecha') }}
                </p>
                <p class="flex items-center gap-2">
                    <i class="fi fi-sr-user text-emerald-600"></i>
                    <span class="font-semibold">Estudiante:</span> {{ session('citacion_estudiante') }}
                </p>
            </div>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('docente.citacion.create', $estudiante->id) }}" 
                   class="px-6 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition font-medium flex items-center gap-2">
                    <i class="fi fi-sr-add"></i>
                    Nueva Citación
                </a>
                <a href="{{ route('docente.curso.estudiantes', $estudiante->curso_id) }}" 
                   class="px-6 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg transition font-medium flex items-center gap-2">
                    <i class="fi fi-sr-users"></i>
                    Ver Estudiantes
                </a>
            </div>
        </div>
    @endif

    <!-- ============================================ -->
    <!-- FORMULARIO DE CITACIÓN -->
    <!-- ============================================ -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-xl font-bold text-blue-800 dark:text-blue-200 mb-4 flex items-center gap-2">
            <i class="fi fi-sr-document"></i>
            Datos de la Citación
        </h2>

        <!-- Datos del estudiante -->
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 mb-4 border border-blue-100 dark:border-blue-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <p class="flex items-center gap-2">
                    <i class="fi fi-sr-user text-blue-700 dark:text-blue-300"></i>
                    <span class="font-semibold text-blue-700 dark:text-blue-300">Estudiante:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fi fi-sr-graduation-cap text-blue-700 dark:text-blue-300"></i>
                    <span class="font-semibold text-blue-700 dark:text-blue-300">Curso:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}</span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fi fi-sr-user text-blue-700 dark:text-blue-300"></i>
                    <span class="font-semibold text-blue-700 dark:text-blue-300">Padre:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}</span>
                </p>
                <p class="flex items-center gap-2">
                    <i class="fi fi-sr-phone text-blue-700 dark:text-blue-300"></i>
                    <span class="font-semibold text-blue-700 dark:text-blue-300">Teléfono:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $estudiante->padre ? $estudiante->padre->telefono : 'No registrado' }}</span>
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('docente.citacion.store') }}">
            @csrf
            <input type="hidden" name="estudiante_id" value="{{ $estudiante->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="fi fi-sr-edit text-blue-700 dark:text-blue-300"></i>
                    Motivo de la Citación <span class="text-red-500">*</span>
                </label>
                <textarea name="motivo" rows="4" 
                          class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" 
                          required placeholder="Describe el motivo de la citación..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="fi fi-sr-calendar text-blue-700 dark:text-blue-300"></i>
                    Fecha y Hora de la Reunión <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="fecha_programada" 
                       class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" 
                       required min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}">
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i class="fi fi-sr-info"></i>
                    La reunión debe ser al menos 2 horas después de la hora actual.
                </p>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('docente.curso.estudiantes', $estudiante->curso_id) }}" 
                   class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium flex items-center gap-2">
                    <i class="fi fi-sr-cross"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition font-medium shadow-md hover:shadow-lg flex items-center gap-2">
                    <i class="fi fi-sr-paper-plane"></i>
                    Generar Citación y Enviar por WhatsApp
                </button>
            </div>
        </form>
    </div>

</div>
@endsection


