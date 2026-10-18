@extends('layouts.docente')

@section('title', 'Estadísticas - ' . ($curso->nombre ?? ''))

@section('content')
<div class="max-w-[1700px] mx-auto px-4">

    <!-- ENCABEZADO -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-3xl shadow-2xl p-8 mb-8 text-white overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-5">
                <div class="h-20 w-20 rounded-full overflow-hidden border-4 border-yellow-400 bg-white shadow-2xl ring-4 ring-white/20 flex-shrink-0">
                    <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo" class="h-full w-full object-cover">
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight drop-shadow-lg flex items-center gap-3">
                        <i class="fi fi-sr-stats"></i>
                        ESTADÍSTICAS DEL CURSO
                    </h1>
                    <p class="text-blue-100 text-base font-medium mt-1">{{ $curso->nombre ?? '' }} {{ $curso->paralelo ?? '' }}</p>
                </div>
            </div>
            <a href="{{ route('docente.dashboard') }}" class="bg-white/10 border border-white/30 text-white px-6 py-3 rounded-xl hover:bg-white/20 transition font-bold shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- SELECTOR DE CURSO (NUEVO DISEÑO CUADRADO) -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-7 border border-gray-200 dark:border-gray-700 relative z-20">
        <div class="flex items-center space-x-4 flex-wrap gap-3">
            <label class="font-black text-blue-900 dark:text-blue-200 text-sm tracking-wide flex items-center gap-2">
                <i class="fi fi-sr-book"></i>
                SELECCIONAR CURSO:
            </label>
            
            <!-- DROPDOWN PERSONALIZADO -->
            <div class="relative" id="custom-dropdown-container">
                <!-- Botón que abre el menú -->
                <button type="button" onclick="toggleCustomDropdown()" 
                        class="flex items-center justify-between w-72 border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-3.5 bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100 hover:bg-blue-100 dark:hover:bg-gray-600 transition shadow-inner">
                    <span class="flex items-center gap-2">
                        <i class="fi fi-sr-graduation-cap text-blue-700 dark:text-blue-400"></i>
                        {{ $curso->nombre ?? 'Seleccione' }} {{ $curso->paralelo ?? '' }}
                    </span>
                    <i class="fi fi-sr-angle-down text-gray-500 transition-transform duration-300" id="dropdown-icon"></i>
                </button>

                <!-- Menú Desplegable Cuadrado -->
                <div id="custom-dropdown-menu" class="hidden absolute left-0 mt-2 w-[500px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-2xl p-4 grid grid-cols-3 gap-3 max-h-[350px] overflow-y-auto">
                    @php
                        // Diccionario para ordenar correctamente los niveles
                        $ordenNivel = [
                            '1ro' => 1, '1er' => 1, 'Primero' => 1,
                            '2do' => 2, 'Segundo' => 2,
                            '3ro' => 3, 'Tercero' => 3,
                            '4to' => 4, 'Cuarto' => 4,
                            '5to' => 5, 'Quinto' => 5,
                            '6to' => 6, 'Sexto' => 6
                        ];
                        
                        // Ordenamos los cursos
                        $cursosOrdenados = $cursos->sortBy(function($c) use ($ordenNivel) {
                            $nivel = explode(' ', $c->nombre)[0] ?? '0';
                            return ($ordenNivel[$nivel] ?? 99) . '-' . $c->paralelo;
                        });
                    @endphp

                    @foreach($cursosOrdenados as $c)
                        <a href="{{ route('docente.estadisticas.index') }}?curso={{ $c->id }}"
                           class="flex flex-col items-center justify-center p-3 rounded-xl border-2 transition-all duration-200 
                           {{ $curso && $curso->id == $c->id 
                               ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/40 shadow-md transform scale-105' 
                               : 'border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            
                            <span class="text-xl font-black {{ $curso && $curso->id == $c->id ? 'text-blue-800 dark:text-blue-300' : 'text-gray-800 dark:text-gray-200' }}">
                                {{ $c->nombre }}
                            </span>
                                                       <span class="text-sm font-black mt-1.5 px-3 py-0.5 rounded-full shadow-sm transition-colors
                                {{ $curso && $curso->id == $c->id 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-blue-100 text-blue-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                {{ $c->paralelo }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <span class="text-sm text-blue-700 dark:text-blue-300 font-semibold bg-blue-100 dark:bg-blue-900/40 px-5 py-2.5 rounded-full shadow-sm flex items-center gap-2">
                <i class="fi fi-sr-users"></i>
                Total estudiantes: <strong class="text-blue-900 dark:text-blue-200">{{ $totalEstudiantes }}</strong>
            </span>
        </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-7 relative z-10">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border-t-4 border-blue-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-wider">Incidencias Totales</span>
                <div class="bg-blue-700 p-3 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-triangle-warning text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-4xl font-black text-blue-900 dark:text-blue-200">{{ $totalIncidencias }}</span>
                <span class="text-sm text-blue-400 ml-1 font-semibold">registradas</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border-t-4 border-red-600 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-wider">Faltas Graves</span>
                <div class="bg-red-600 p-3 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-circle text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-4xl font-black text-red-700 dark:text-red-300">{{ $totalFaltasGraves }}</span>
                <span class="text-sm text-red-400 ml-1 font-semibold">registradas</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border-t-4 border-yellow-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-yellow-600 dark:text-yellow-400 text-xs font-black uppercase tracking-wider">Faltas Leves</span>
                <div class="bg-yellow-500 p-3 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-circle text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-4xl font-black text-yellow-700 dark:text-yellow-300">{{ $faltasLeves }}</span>
                <span class="text-sm text-yellow-500 ml-1 font-semibold">registradas</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border-t-4 border-emerald-600 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider">Citaciones Pendientes</span>
                <div class="bg-emerald-600 p-3 rounded-2xl shadow-lg">
                    <i class="fi fi-sr-calendar-clock text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-4xl font-black text-emerald-700 dark:text-emerald-300">{{ $citacionesPendientes }}</span>
                <span class="text-sm text-emerald-500 ml-1 font-semibold">pendientes</span>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS DE DONA -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-7">
        
        <!-- Gráfico: Incidencias por Tipo -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-blue-800 flex justify-between items-center">
                <h3 class="font-black text-white text-base flex items-center gap-2">
                    <i class="fi fi-sr-chart-pie"></i>
                    Incidencias por Tipo
                </h3>
                <span class="text-xs text-blue-200 font-semibold">{{ $totalIncidencias }} total</span>
            </div>
            <div class="p-6">
                @if($totalIncidencias > 0)
                    <div class="relative" style="height: 320px;">
                        <canvas id="chartTipo"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        @foreach($incidenciasPorTipo as $item)
                            @php
                                $porcentaje = $totalIncidencias > 0 ? round(($item->total / $totalIncidencias) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between text-xs px-2 py-1.5 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 truncate">{{ $item->tipo }}</span>
                                <span class="font-bold text-blue-700 dark:text-blue-300 ml-2">{{ $porcentaje }}%</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400">
                        <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                        <p class="font-semibold">No hay incidencias registradas.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Gráfico: Incidencias por Materia -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-yellow-600 flex justify-between items-center">
                <h3 class="font-black text-white text-base flex items-center gap-2">
                    <i class="fi fi-sr-chart-pie"></i>
                    Incidencias por Materia
                </h3>
                <span class="text-xs text-yellow-100 font-semibold">{{ $totalIncidencias }} total</span>
            </div>
            <div class="p-6">
                @if($totalIncidencias > 0)
                    <div class="relative" style="height: 320px;">
                        <canvas id="chartMateria"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        @foreach($incidenciasPorMateria as $item)
                            @php
                                $porcentaje = $totalIncidencias > 0 ? round(($item->total / $totalIncidencias) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between text-xs px-2 py-1.5 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 truncate">{{ $item->materia }}</span>
                                <span class="font-bold text-yellow-700 dark:text-yellow-300 ml-2">{{ $porcentaje }}%</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400">
                        <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                        <p class="font-semibold">No hay incidencias registradas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- TOP 5 ESTUDIANTES -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-blue-800">
            <h3 class="font-black text-white text-base flex items-center gap-2">
                <i class="fi fi-sr-trophy"></i>
                Top 5 - Estudiantes con más faltas
                <span class="ml-3 text-xs bg-white/20 px-3 py-1 rounded-full font-bold">Citación a partir de 3 faltas</span>
            </h3>
        </div>
        <div class="overflow-x-auto p-2">
            <table class="w-full text-sm">
                <thead class="text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-black uppercase tracking-wider">#</th>
                        <th class="text-left py-3 px-4 text-xs font-black uppercase tracking-wider">Estudiante</th>
                        <th class="text-center py-3 px-4 text-xs font-black uppercase tracking-wider">Faltas</th>
                        <th class="text-center py-3 px-4 text-xs font-black uppercase tracking-wider">Graves</th>
                        <th class="text-center py-3 px-4 text-xs font-black uppercase tracking-wider">Leves</th>
                        <th class="text-center py-3 px-4 text-xs font-black uppercase tracking-wider">Estado</th>
                        <th class="text-center py-3 px-4 text-xs font-black uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($topEstudiantes as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 font-black text-gray-700 dark:text-gray-300">
                            @if($index === 0) <i class="fi fi-sr-medal text-yellow-500 text-lg"></i>
                            @elseif($index === 1) <i class="fi fi-sr-medal text-gray-400 text-lg"></i>
                            @elseif($index === 2) <i class="fi fi-sr-medal text-orange-500 text-lg"></i>
                            @else <span class="text-gray-500">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-800 dark:text-gray-200">
                            <div class="flex items-center space-x-3">
                                @if($item['foto'])
                                    <img src="{{ $item['foto'] }}" 
                                         alt="{{ $item['nombre'] }}" 
                                         class="w-10 h-10 rounded-full object-cover border-2 border-blue-300 shadow-md">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center text-white text-sm font-black shadow-md">
                                        {{ strtoupper(substr($item['nombre'] ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ $item['nombre'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-4 py-1.5 rounded-full text-sm font-black shadow-md
                                {{ $item['total'] >= 5 ? 'bg-red-600 text-white' : ($item['total'] >= 3 ? 'bg-yellow-500 text-white' : 'bg-emerald-600 text-white') }}">
                                {{ $item['total'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-black text-red-600 dark:text-red-400">{{ $item['graves'] }}</td>
                        <td class="py-3 px-4 text-center font-black text-emerald-600 dark:text-emerald-400">{{ $item['total'] - $item['graves'] }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($item['total'] >= 3)
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-black bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-300 dark:border-red-700">
                                    <i class="fi fi-sr-bell"></i>
                                    Citación
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                                    <i class="fi fi-sr-check-circle"></i>
                                    Regular
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('docente.estadisticas.estudiante', $item['id']) }}" 
                                   class="inline-flex items-center gap-1 text-blue-700 dark:text-blue-300 text-xs font-black bg-blue-50 dark:bg-blue-900/40 hover:bg-blue-100 px-3 py-1.5 rounded-full transition border border-blue-200 dark:border-blue-800">
                                    <i class="fi fi-sr-chart-histogram"></i>
                                    Ver
                                </a>
                                @if($item['total'] >= 3)
                                    <a href="{{ route('docente.incidencia.citacion', $item['id']) }}" 
                                       class="inline-flex items-center gap-1 text-white text-xs font-black bg-emerald-700 hover:bg-emerald-800 px-3 py-1.5 rounded-full transition">
                                        <i class="fi fi-sr-comment-alt"></i>
                                        Citación
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400 font-semibold">
                            <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                            No hay estudiantes con faltas registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <div class="mt-7 bg-gradient-to-r from-blue-900 to-blue-800 p-5 rounded-2xl shadow-xl">
        <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-white">
            <div class="flex items-center space-x-4">
                <span class="font-black tracking-wide flex items-center gap-2">
                    <i class="fi fi-sr-info"></i>
                    LEYENDA:
                </span>
                <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                    <span class="inline-block w-4 h-4 bg-emerald-400 rounded-full shadow"></span>
                    <span class="font-semibold">0-2 faltas</span>
                </span>
                <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                    <span class="inline-block w-4 h-4 bg-yellow-400 rounded-full shadow"></span>
                    <span class="font-semibold">3-4 faltas</span>
                </span>
                <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                    <span class="inline-block w-4 h-4 bg-red-400 rounded-full shadow"></span>
                    <span class="font-semibold">5+ faltas</span>
                </span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                    <i class="fi fi-sr-school"></i>
                    U.E. GRAL. JOSÉ DE SAN MARTÍN
                </span>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('docente.dashboard') }}" class="inline-flex items-center gap-2 text-blue-700 dark:text-blue-400 font-bold hover:text-blue-900 hover:underline transition">
            <i class="fi fi-sr-arrow-left"></i>
            Volver al Dashboard
        </a>
    </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    // ============================================
    // FUNCIONES PARA EL NUEVO DROPDOWN PERSONALIZADO
    // ============================================
    function toggleCustomDropdown() {
        const menu = document.getElementById('custom-dropdown-menu');
        const icon = document.getElementById('dropdown-icon');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            menu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Cerrar el menú si se hace clic fuera de él
    window.addEventListener('click', function(e) {
        const container = document.getElementById('custom-dropdown-container');
        const menu = document.getElementById('custom-dropdown-menu');
        const icon = document.getElementById('dropdown-icon');
        
        if (container && !container.contains(e.target)) {
            menu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // PALETA INSTITUCIONAL
        // ============================================
        const paletaInstitucional = [
            '#1e3a8a', '#1d4ed8', '#2563eb', '#3b82f6', '#0ea5e9', '#06b6d4',
            '#facc15', '#eab308', '#f59e0b', '#dc2626', '#16a34a', '#7c3aed',
        ];

        // ============================================
        // GRÁFICO 1: INCIDENCIAS POR TIPO
        // ============================================
        @if($totalIncidencias > 0)
        const ctxTipo = document.getElementById('chartTipo');
        if (ctxTipo) {
            new Chart(ctxTipo, {
                type: 'doughnut',
                data: {
                    labels: @json($incidenciasPorTipo->pluck('tipo')),
                    datasets: [{
                        data: @json($incidenciasPorTipo->pluck('total')),
                        backgroundColor: paletaInstitucional,
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e3a8a',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#facc15',
                            borderWidth: 2,
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const valor = context.parsed;
                                    const porcentaje = total > 0 ? ((valor / total) * 100).toFixed(1) : 0;
                                    return ` ${valor} incidencias (${porcentaje}%)`;
                                }
                            }
                        }
                    },
                    animation: { animateScale: true, animateRotate: true }
                }
            });
        }
        @endif

        // ============================================
        // GRÁFICO 2: INCIDENCIAS POR MATERIA
        // ============================================
        @if($totalIncidencias > 0)
        const ctxMateria = document.getElementById('chartMateria');
        if (ctxMateria) {
            new Chart(ctxMateria, {
                type: 'doughnut',
                data: {
                    labels: @json($incidenciasPorMateria->pluck('materia')),
                    datasets: [{
                        data: @json($incidenciasPorMateria->pluck('total')),
                        backgroundColor: paletaInstitucional,
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e3a8a',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#facc15',
                            borderWidth: 2,
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const valor = context.parsed;
                                    const porcentaje = total > 0 ? ((valor / total) * 100).toFixed(1) : 0;
                                    return ` ${valor} incidencias (${porcentaje}%)`;
                                }
                            }
                        }
                    },
                    animation: { animateScale: true, animateRotate: true }
                }
            });
        }
        @endif
    });
</script>

@endsection