@extends('layouts.docente')

@section('title', 'Seleccionar Curso')

@section('content')
<div>

    <!-- ENCABEZADO CON COLOR INSTITUCIONAL -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fi fi-sr-book"></i>
                    SELECCIONAR CURSO
                </h1>
                <p class="text-blue-200 text-sm mt-1">Haz clic en el curso que deseas gestionar</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-lg text-sm text-blue-100 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-check-circle"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 dark:text-emerald-300 hover:text-emerald-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-cross-circle"></i>
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-red-700 dark:text-red-300 hover:text-red-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    <!-- Curso seleccionado actualmente -->
    @if(session('curso_seleccionado'))
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fi fi-sr-marker text-2xl text-blue-700 dark:text-blue-300"></i>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Curso actualmente seleccionado:</p>
                    <p class="font-semibold text-blue-800 dark:text-blue-200">{{ session('curso_seleccionado') }}</p>
                </div>
            </div>
            <a href="{{ route('docente.cursos.seleccionar') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 hover:underline font-medium flex items-center gap-1">
                Cambiar curso
                <i class="fi fi-sr-arrow-right"></i>
            </a>
        </div>
    @endif

    <!-- Grid de cursos -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">

        @forelse($cursos as $curso)
            @php
                $totalEstudiantes = $curso->estudiantes()->count();
            @endphp
            <a href="{{ route('docente.cursos.seleccionar.curso', $curso->id) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all transform hover:-translate-y-2 overflow-hidden">
                
                <!-- Cabecera con color institucional -->
                <div class="bg-gradient-to-r from-blue-800 to-blue-700 p-4 text-white">
                    <div class="text-center">
                        <i class="fi fi-sr-book text-3xl mb-1 inline-block"></i>
                        <div class="text-lg font-bold mt-1">{{ $curso->nombre }}</div>
                        <div class="text-sm text-blue-200">Paralelo: {{ $curso->paralelo }}</div>
                    </div>
                </div>
                
                <!-- Cuerpo -->
                <div class="p-4 text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2 flex items-center justify-center gap-1">
                        <span class="font-semibold">Nivel:</span> {{ $curso->nivel ?? 'N/A' }}
                    </div>
                    <div class="text-xs text-gray-400 mb-3 flex items-center justify-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        {{ $curso->gestion }}
                    </div>
                    
                    <!-- Badge de estudiantes -->
                    <div class="inline-flex items-center gap-1 {{ $totalEstudiantes > 0 ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }} px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fi fi-sr-users"></i>
                        {{ $totalEstudiantes }} estudiantes
                    </div>

                    <!-- Indicador de selección -->
                    <div class="mt-3 text-sm font-medium text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-1">
                        Seleccionar curso
                        <i class="fi fi-sr-arrow-right"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <i class="fi fi-sr-inbox text-6xl block mb-4 text-gray-400"></i>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No tienes cursos asignados</h3>
                    <p class="text-gray-400 text-sm">Contacta al administrador para que te asigne cursos.</p>
                </div>
            </div>
        @endforelse

    </div>

    <!-- Botón volver -->
    <div class="mt-8 text-center">
        <a href="{{ route('docente.dashboard') }}" 
           class="inline-flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 hover:underline font-medium">
            <i class="fi fi-sr-arrow-left"></i>
            <span>Volver al Dashboard</span>
        </a>
    </div>

    <!-- Leyenda / Consejos -->
    <div class="mt-6 bg-white dark:bg-gray-800 p-3 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="text-gray-600 dark:text-gray-400 font-medium flex items-center gap-1">
                <i class="fi fi-sr-info"></i>
                Consejos:
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-blue-600 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Haz clic en un curso para seleccionarlo</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">El curso seleccionado se usa en todo el sistema</span>
            </span>
        </div>
    </div>

</div>
@endsection