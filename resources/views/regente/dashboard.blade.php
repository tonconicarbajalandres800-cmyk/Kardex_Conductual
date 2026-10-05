@extends('layouts.regente')

@section('title', 'Dashboard')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-dashboard"></i>
                    PANEL DE CONTROL
                </h1>
                <p class="text-blue-200 text-sm mt-1">
                    Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}
                </p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. GRAL. JOSÉ DE SAN MARTÍN
                    </span>
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-clock"></i>
                        {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 flex items-center gap-3">
                <i class="fi fi-sr-user-shield text-3xl text-yellow-200"></i>
                <div>
                    <p class="text-xs text-blue-200 uppercase tracking-wider font-bold">Rol</p>
                    <p class="text-sm font-semibold">Regente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        
        <!-- Presentes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border-l-4 border-emerald-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Presentes Hoy</p>
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $presentesHoy ?? 0 }}</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-check-circle text-emerald-600 dark:text-emerald-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Ausentes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border-l-4 border-rose-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Ausentes Hoy</p>
                    <p class="text-3xl font-bold text-rose-600 dark:text-rose-400">{{ $ausentesHoy ?? 0 }}</p>
                </div>
                <div class="bg-rose-100 dark:bg-rose-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-cross-circle text-rose-600 dark:text-rose-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Permisos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border-l-4 border-amber-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Permisos Hoy</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $permisosHoy ?? 0 }}</p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-document-signed text-amber-600 dark:text-amber-300 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- LISTA DE CURSOS -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                <i class="fi fi-sr-book text-blue-600 dark:text-blue-400"></i>
                Cursos
            </h3>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full flex items-center gap-1">
                <i class="fi fi-sr-list"></i>
                {{ $cursos->count() }} cursos disponibles
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($cursos->sortBy(function($curso) {
                $ordenNivel = [
                    '1ro' => 1, '2do' => 2, '3ro' => 3,
                    '4to' => 4, '5to' => 5, '6to' => 6
                ];
                $nivel = explode(' ', $curso->nombre)[0] ?? '0';
                $nivelNum = $ordenNivel[$nivel] ?? 99;
                return $nivelNum . '-' . $curso->paralelo;
            }) as $curso)
                <a href="{{ route('regente.asistencia', ['curso' => $curso->id]) }}" 
                   class="group bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl p-4 text-center hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    
                    <div class="bg-blue-100 dark:bg-blue-900/40 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 transition-colors">
                        <i class="fi fi-sr-clipboard-list text-blue-600 dark:text-blue-300 group-hover:text-white text-2xl transition-colors"></i>
                    </div>
                    
                    <h4 class="font-bold text-gray-800 dark:text-gray-200">{{ $curso->nombre }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1 mt-1">
                        <i class="fi fi-sr-marker"></i>
                        Paralelo {{ $curso->paralelo }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 bg-white dark:bg-gray-800 inline-flex items-center gap-1 px-2 py-1 rounded-full">
                        <i class="fi fi-sr-users"></i>
                        {{ $curso->estudiantes->count() }} estudiantes
                    </p>
                </a>
            @empty
                <div class="col-span-full text-center py-8">
                    <i class="fi fi-sr-inbox text-5xl text-gray-400 block mb-3"></i>
                    <p class="text-gray-400 dark:text-gray-500">No hay cursos disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection