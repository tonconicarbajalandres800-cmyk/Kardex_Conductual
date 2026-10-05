@extends('layouts.docente')

@section('title', 'Inicio Docente')

@section('content')
<div class="-m-4 p-4 min-h-screen sm:-m-6 sm:p-6">

    <!-- ENCABEZADO INSTITUCIONAL -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-3xl shadow-2xl p-7 text-white mb-7 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/10 rounded-full blur-3xl"></div>

        <div class="relative flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-black tracking-tight drop-shadow-lg flex items-center gap-3">
                    <i class="fi fi-sr-dashboard"></i>
                    INICIO DEL DOCENTE
                </h1>
                <p class="text-blue-200 text-base font-medium mt-1">Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="bg-white/10 backdrop-blur-sm border border-white/20 px-5 py-2.5 rounded-xl text-sm font-bold text-blue-100 flex items-center gap-2">
                    <i class="fi fi-sr-calendar"></i>
                    {{ now()->format('d/m/Y') }}
                </span>
                <span class="bg-yellow-400 text-blue-900 px-5 py-2.5 rounded-xl text-sm font-black shadow-lg flex items-center gap-2">
                    <i class="fi fi-sr-graduation-cap"></i>
                    {{ session('curso_seleccionado') ?? 'Sin curso' }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-600 text-white p-4 rounded-2xl mb-5 flex items-center justify-between shadow-xl font-bold">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-check-circle"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white text-2xl leading-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-700 text-white p-4 rounded-2xl mb-5 flex items-center justify-between shadow-xl font-bold">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-cross-circle"></i>
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white text-2xl leading-none">&times;</button>
        </div>
    @endif

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-7">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-blue-700 hover:shadow-2xl hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 dark:text-blue-400 text-xs font-black uppercase tracking-wider">Total Estudiantes</p>
                    <p class="text-3xl font-black text-blue-900 dark:text-blue-200 mt-2">{{ $totalEstudiantes ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-4 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-users text-blue-700 dark:text-blue-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-500 dark:text-blue-400 font-semibold">
                <span>En tu curso asignado</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-2xl hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 dark:text-yellow-400 text-xs font-black uppercase tracking-wider">Incidencias Hoy</p>
                    <p class="text-3xl font-black text-yellow-700 dark:text-yellow-300 mt-2">{{ $incidenciasHoy ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-4 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-triangle-warning text-yellow-600 dark:text-yellow-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-yellow-500 dark:text-yellow-400 font-semibold">
                <span>Registradas en el día</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-red-600 hover:shadow-2xl hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-wider">Pendientes</p>
                    <p class="text-3xl font-black text-red-700 dark:text-red-300 mt-2">{{ $incidenciasPendientes ?? 0 }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/40 p-4 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-clock text-red-600 dark:text-red-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-red-500 dark:text-red-400 font-semibold">
                <span>Por atender</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-emerald-600 hover:shadow-2xl hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider">Citaciones</p>
                    <p class="text-3xl font-black text-emerald-700 dark:text-emerald-300 mt-2">{{ $citacionesPendientes ?? 0 }}</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-4 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-document text-emerald-600 dark:text-emerald-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-emerald-500 dark:text-emerald-400 font-semibold">
                <span>Pendientes de enviar</span>
            </div>
        </div>

    </div>

    <!-- ACCIONES RÁPIDAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">

        <a href="{{ route('docente.curso.estudiantes', session('curso_seleccionado_id', 3)) }}" 
           class="relative bg-gradient-to-br from-blue-800 to-blue-700 dark:from-blue-900 dark:to-blue-800 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1.5 flex items-center justify-between group overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-yellow-400/10 rounded-full blur-xl"></div>
            <div class="relative">
                <i class="fi fi-sr-chart-histogram text-3xl block group-hover:scale-110 transition"></i>
                <span class="font-black text-base block mt-2">Ver Kardex</span>
                <span class="text-xs block mt-0.5 text-blue-200">{{ session('curso_seleccionado', 'Selecciona un curso') }}</span>
            </div>
            <i class="fi fi-sr-arrow-right relative text-yellow-300 text-xl font-black"></i>
        </a>

        <a href="{{ route('docente.incidencia.create') }}" 
           class="relative bg-gradient-to-br from-blue-800 to-blue-700 dark:from-blue-900 dark:to-blue-800 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1.5 flex items-center justify-between group overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-yellow-400/10 rounded-full blur-xl"></div>
            <div class="relative">
                <i class="fi fi-sr-add text-3xl block group-hover:scale-110 transition"></i>
                <span class="font-black text-base block mt-2">Nueva Incidencia</span>
            </div>
            <i class="fi fi-sr-arrow-right relative text-yellow-300 text-xl font-black"></i>
        </a>

        <a href="{{ route('docente.cursos.seleccionar') }}" 
           class="relative bg-gradient-to-br from-blue-800 to-blue-700 dark:from-blue-900 dark:to-blue-800 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1.5 flex items-center justify-between group overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-yellow-400/10 rounded-full blur-xl"></div>
            <div class="relative">
                <i class="fi fi-sr-book text-3xl block group-hover:scale-110 transition"></i>
                <span class="font-black text-base block mt-2">Mis Cursos</span>
            </div>
            <i class="fi fi-sr-arrow-right relative text-yellow-300 text-xl font-black"></i>
        </a>

        <a href="{{ route('docente.perfil') }}" 
           class="relative bg-gradient-to-br from-blue-800 to-blue-700 dark:from-blue-900 dark:to-blue-800 text-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1.5 flex items-center justify-between group overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-yellow-400/10 rounded-full blur-xl"></div>
            <div class="relative">
                <i class="fi fi-sr-user text-3xl block group-hover:scale-110 transition"></i>
                <span class="font-black text-base block mt-2">Mi Perfil</span>
            </div>
            <i class="fi fi-sr-arrow-right relative text-yellow-300 text-xl font-black"></i>
        </a>

    </div>

    <!-- ÚLTIMAS INCIDENCIAS Y CITACIONES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-blue-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-800 to-blue-700 flex justify-between items-center">
                <h3 class="font-black text-white text-base flex items-center gap-2">
                    <i class="fi fi-sr-triangle-warning"></i>
                    Últimas Incidencias
                </h3>
                <a href="#" class="text-sm text-yellow-300 hover:text-yellow-200 font-bold hover:underline flex items-center gap-1">
                    Ver todas
                    <i class="fi fi-sr-arrow-right"></i>
                </a>
            </div>
            <div class="p-4">
                @forelse($ultimasIncidencias ?? [] as $incidencia)
                    <div class="flex items-center justify-between py-3.5 border-b border-blue-50 dark:border-gray-700 last:border-0 hover:bg-blue-50 dark:hover:bg-gray-700/50 px-3 rounded-xl transition">
                        <div class="flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-sm flex-shrink-0 shadow">
                                <i class="fi fi-sr-user"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-900 dark:text-blue-200">{{ $incidencia->estudiante->nombre ?? 'N/A' }} {{ $incidencia->estudiante->apellido ?? '' }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ $incidencia->descripcion ?? 'Sin descripción' }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-black shadow-sm
                            {{ $incidencia->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                            {{ $incidencia->estado === 'atendida' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : '' }}
                            {{ $incidencia->estado === 'derivada' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' : '' }}">
                            {{ ucfirst($incidencia->estado ?? 'Pendiente') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                        <p class="font-semibold">No hay incidencias recientes</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-emerald-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-emerald-700 to-emerald-600 flex justify-between items-center">
                <h3 class="font-black text-white text-base flex items-center gap-2">
                    <i class="fi fi-sr-document"></i>
                    Citaciones Recientes
                </h3>
                <a href="#" class="text-sm text-yellow-200 hover:text-yellow-100 font-bold hover:underline flex items-center gap-1">
                    Ver todas
                    <i class="fi fi-sr-arrow-right"></i>
                </a>
            </div>
            <div class="p-4">
                @forelse($ultimasCitaciones ?? [] as $citacion)
                    <div class="flex items-center justify-between py-3.5 border-b border-emerald-50 dark:border-gray-700 last:border-0 hover:bg-emerald-50 dark:hover:bg-gray-700/50 px-3 rounded-xl transition">
                        <div class="flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-emerald-600 to-emerald-500 flex items-center justify-center text-white text-sm flex-shrink-0 shadow">
                                <i class="fi fi-sr-document"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-900 dark:text-emerald-200">{{ $citacion->estudiante->nombre ?? 'N/A' }} {{ $citacion->estudiante->apellido ?? '' }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-black shadow-sm
                            {{ $citacion->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                            {{ $citacion->estado === 'confirmada' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : '' }}
                            {{ $citacion->estado === 'cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : '' }}">
                            {{ ucfirst($citacion->estado ?? 'Pendiente') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                        <p class="font-semibold">No hay citaciones recientes</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- LEYENDA -->
    <div class="mt-7 bg-gradient-to-r from-blue-900 to-blue-800 p-5 rounded-2xl shadow-xl">
        <div class="flex flex-wrap items-center gap-5 text-sm text-white">
            <span class="font-black tracking-wide flex items-center gap-2">
                <i class="fi fi-sr-info"></i>
                LEYENDA DE ESTADOS:
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-yellow-400 rounded-full shadow"></span>
                <span class="font-semibold">Pendiente</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-emerald-400 rounded-full shadow"></span>
                <span class="font-semibold">Atendida / Confirmada</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-blue-400 rounded-full shadow"></span>
                <span class="font-semibold">Derivada / Reprogramada</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-red-400 rounded-full shadow"></span>
                <span class="font-semibold">Cancelada</span>
            </span>
        </div>
    </div>

</div>
@endsection