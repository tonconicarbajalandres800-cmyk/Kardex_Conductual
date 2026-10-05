@extends('layouts.padre')

@section('title', 'Inicio')

@section('header', 'Panel de Padre de Familia')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    <!-- Botón modo oscuro -->
    <div class="flex justify-end mb-4">
        <button id="theme-toggle" type="button"
            class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl shadow-sm border transition-colors
                   bg-white border-gray-200 text-gray-700 hover:bg-gray-100
                   dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100 dark:hover:bg-gray-700">
            <i id="theme-toggle-icon" class="fi fi-sr-moon text-base"></i>
            <span id="theme-toggle-label">Modo oscuro</span>
        </button>
    </div>

    <!-- Selector de hijo -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-5 rounded-2xl shadow-md mb-6 border border-indigo-100
                dark:from-gray-800 dark:to-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-4">
            <label class="font-semibold text-indigo-800 text-lg dark:text-indigo-300 flex items-center gap-2">
                <i class="fi fi-sr-child"></i>
                Seleccionar Hijo:
            </label>
            <select id="hijo-select" class="border-2 border-indigo-200 rounded-xl px-4 py-2.5 w-64 bg-white/80 backdrop-blur-sm focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all
                       dark:bg-gray-900/80 dark:border-gray-600 dark:text-gray-100" onchange="window.location.href='{{ route('padre.dashboard') }}?hijo='+this.value">
                @foreach($hijos as $h)
                    <option value="{{ $h->id }}" {{ $hijoSeleccionado && $hijoSeleccionado->id == $h->id ? 'selected' : '' }}>
                        {{ $h->nombre }} {{ $h->apellido }} - {{ $h->curso ? $h->curso->nombre . ' ' . $h->curso->paralelo : 'Sin curso' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if($hijos->isEmpty())
        <div class="bg-amber-50 border-l-4 border-amber-400 text-amber-700 p-5 rounded-xl text-center shadow-sm
                    dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-600">
            <i class="fi fi-sr-info text-lg inline-block mr-2"></i>
            Aún no tienes hijos registrados en el sistema. Contacta con la secretaría del colegio.
        </div>
    @else
        <!-- ========================================== -->
        <!-- INFORMACIÓN DEL ESTUDIANTE CON FOTO        -->
        <!-- ========================================== -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-2xl shadow-lg mb-6 text-white">
            <div class="flex items-center space-x-5">
                @php
                    $fotoUrl = null;
                    if ($hijoSeleccionado) {
                        if (isset($hijoSeleccionado->fotos) && $hijoSeleccionado->fotos->count() > 0) {
                            $ultimaFoto = $hijoSeleccionado->fotos->last();
                            $nombreArchivo = basename($ultimaFoto->ruta);
                            $fotoUrl = asset('storage/fotos/' . $nombreArchivo);
                        }
                    }
                @endphp

                @if($fotoUrl)
                    <img src="{{ $fotoUrl }}" 
                         alt="Foto de {{ $hijoSeleccionado->nombre }}" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-indigo-300 shadow-lg">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-3xl shadow-lg">
                        {{ $hijoSeleccionado ? strtoupper(substr($hijoSeleccionado->nombre, 0, 1)) : '?' }}
                    </div>
                @endif
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">
                        {{ $hijoSeleccionado ? $hijoSeleccionado->nombre . ' ' . $hijoSeleccionado->apellido : 'Selecciona un hijo' }}
                    </h2>
                    <p class="text-indigo-200 text-sm font-medium flex items-center gap-2 mt-1">
                        <i class="fi fi-sr-graduation-cap"></i>
                        {{ $hijoSeleccionado && $hijoSeleccionado->curso ? $hijoSeleccionado->curso->nombre . ' ' . $hijoSeleccionado->curso->paralelo : 'Sin curso' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- RESUMEN DEL ESTUDIANTE -->
        <div class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-md border border-gray-200 mb-6
                    dark:bg-gray-800/90 dark:border-gray-700">
            <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center gap-2 dark:text-gray-200">
                <i class="fi fi-sr-chart-histogram"></i>
                RESUMEN DEL ESTUDIANTE
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl text-center shadow-sm border border-blue-200
                            dark:from-blue-900/40 dark:to-blue-900/20 dark:border-blue-800">
                    <span class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $totalFaltas }}</span>
                    <p class="text-sm text-gray-600 font-medium dark:text-gray-300">Total Faltas</p>
                </div>
                <div class="bg-gradient-to-br from-rose-50 to-rose-100 p-4 rounded-xl text-center shadow-sm border border-rose-200
                            dark:from-rose-900/40 dark:to-rose-900/20 dark:border-rose-800">
                    <span class="text-3xl font-bold text-rose-700 dark:text-rose-300">{{ $faltasGraves }}</span>
                    <p class="text-sm text-gray-600 font-medium dark:text-gray-300">Faltas Graves</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 rounded-xl text-center shadow-sm border border-emerald-200
                            dark:from-emerald-900/40 dark:to-emerald-900/20 dark:border-emerald-800">
                    <span class="text-3xl font-bold text-emerald-700 dark:text-emerald-300">{{ $faltasLeves }}</span>
                    <p class="text-sm text-gray-600 font-medium dark:text-gray-300">Faltas Leves</p>
                </div>
            </div>

            <!-- Estado actual -->
            <div class="p-5 rounded-xl shadow-sm border
                {{ $estado === 'Alerta' ? 'bg-gradient-to-r from-rose-50 to-rose-100 border-rose-300 dark:from-rose-900/40 dark:to-rose-900/20 dark:border-rose-700' : ($estado === 'Observación' ? 'bg-gradient-to-r from-amber-50 to-amber-100 border-amber-300 dark:from-amber-900/40 dark:to-amber-900/20 dark:border-amber-700' : 'bg-gradient-to-r from-emerald-50 to-emerald-100 border-emerald-300 dark:from-emerald-900/40 dark:to-emerald-900/20 dark:border-emerald-700') }}">
                <p class="font-bold text-lg flex items-center gap-2 {{ $estado === 'Alerta' ? 'text-rose-700 dark:text-rose-300' : ($estado === 'Observación' ? 'text-amber-700 dark:text-amber-300' : 'text-emerald-700 dark:text-emerald-300') }}">
                    <i class="{{ $estado === 'Alerta' ? 'fi fi-sr-triangle-warning' : ($estado === 'Observación' ? 'fi fi-sr-info' : 'fi fi-sr-check-circle') }}"></i>
                    ESTADO: {{ strtoupper($estado) }}
                </p>
                <p class="text-sm text-gray-700 mt-1 dark:text-gray-300">{{ $mensajeEstado }}</p>
            </div>
        </div>

        <!-- Citaciones pendientes y próximas reuniones -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Citaciones pendientes -->
            <div class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-md border border-gray-200
                        dark:bg-gray-800/90 dark:border-gray-700">
                <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center gap-2 dark:text-gray-200">
                    <i class="fi fi-sr-calendar-clock"></i>
                    Citaciones Pendientes
                </h3>
                @if($citacionesPendientes->isEmpty())
                    <p class="text-gray-500 text-sm italic dark:text-gray-400">No hay citaciones pendientes.</p>
                @else
                    @foreach($citacionesPendientes as $citacion)
                    <div class="border-b border-gray-200 pb-3 mb-3 last:border-0 dark:border-gray-700">
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $citacion->estudiante->nombre }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <i class="fi fi-sr-calendar"></i>
                            {{ \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <i class="fi fi-sr-marker"></i>
                            {{ $citacion->motivo }}
                        </p>
                        <form method="POST" action="{{ route('padre.citacion.confirmar', $citacion->id) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg transition-colors shadow-sm flex items-center gap-2">
                                <i class="fi fi-sr-check"></i>
                                Confirmar asistencia
                            </button>
                        </form>
                    </div>
                    @endforeach
                    <a href="{{ route('padre.historial.citaciones') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-1">
                        Ver todas las citaciones
                        <i class="fi fi-sr-arrow-right"></i>
                    </a>
                @endif
            </div>

            <!-- Próximas reuniones -->
            <div class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-md border border-gray-200
                        dark:bg-gray-800/90 dark:border-gray-700">
                <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center gap-2 dark:text-gray-200">
                    <i class="fi fi-sr-marker"></i>
                    Próximas Reuniones
                </h3>
                @if($proximasReuniones->isEmpty())
                    <p class="text-gray-500 text-sm italic dark:text-gray-400">No hay reuniones programadas.</p>
                @else
                    @foreach($proximasReuniones as $reunion)
                    <div class="border-b border-gray-200 pb-3 mb-3 last:border-0 dark:border-gray-700">
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $reunion->estudiante->nombre }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <i class="fi fi-sr-calendar"></i>
                            {{ \Carbon\Carbon::parse($reunion->fecha_programada)->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <i class="fi fi-sr-marker"></i>
                            {{ $reunion->motivo }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1 dark:text-gray-500 flex items-center gap-1">
                            <i class="fi fi-sr-marker"></i>
                            Dirección del colegio
                        </p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Historial de incidencias -->
        <div class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-md border border-gray-200
                    dark:bg-gray-800/90 dark:border-gray-700">
            <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center gap-2 dark:text-gray-200">
                <i class="fi fi-sr-document"></i>
                Últimas Incidencias
            </h3>
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold">N°</th>
                            <th class="text-left py-3 px-4 font-semibold">FECHA</th>
                            <th class="text-left py-3 px-4 font-semibold">TIPO DE FALTA</th>
                            <th class="text-left py-3 px-4 font-semibold">MATERIA</th>
                            <th class="text-left py-3 px-4 font-semibold">DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800/90">
                        @forelse($historialIncidencias as $index => $inc)
                        <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-gray-700/60">
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $inc->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ in_array($inc->tipo, ['Indisciplina', 'Falto a clases', 'Sin uniforme']) 
                                        ? 'bg-rose-100 text-rose-800 border border-rose-200 dark:bg-rose-900/40 dark:text-rose-300 dark:border-rose-700' 
                                        : 'bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/40 dark:text-amber-300 dark:border-amber-700' }}">
                                    {{ $inc->tipo }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $inc->materia ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300 max-w-xs">
                                @if($inc->descripcion)
                                    <span class="truncate block cursor-help" title="{{ $inc->descripcion }}">
                                        {{ Str::limit($inc->descripcion, 60) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500 italic dark:text-gray-400">No hay incidencias registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('padre.kardex', $hijoSeleccionado->id) }}" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg inline-flex items-center gap-2 transition-colors shadow-sm">
                    <i class="fi fi-sr-document"></i>
                    Ver historial completo
                    <i class="fi fi-sr-arrow-right"></i>
                </a>
            </div>
        </div>
    @endif
</div>

<script>
(function () {
    const root = document.documentElement;
    const toggleBtn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    const label = document.getElementById('theme-toggle-label');

    function applyTheme(theme) {
        if (theme === 'dark') {
            root.classList.add('dark');
            icon.className = 'fi fi-sr-sun text-base';
            label.textContent = 'Modo claro';
        } else {
            root.classList.remove('dark');
            icon.className = 'fi fi-sr-moon text-base';
            label.textContent = 'Modo oscuro';
        }
    }

    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(saved || (prefersDark ? 'dark' : 'light'));

    toggleBtn.addEventListener('click', function () {
        const isDark = root.classList.contains('dark');
        const next = isDark ? 'light' : 'dark';
        applyTheme(next);
        localStorage.setItem('theme', next);
    });
})();
</script>
@endsection