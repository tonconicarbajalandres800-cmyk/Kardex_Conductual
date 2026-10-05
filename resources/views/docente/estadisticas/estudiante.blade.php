@extends('layouts.docente')

@section('title', 'Estadísticas de ' . $estudiante->nombre)

@section('content')
<div class="max-w-[1700px] mx-auto px-4">

    <!-- ENCABEZADO -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-3xl shadow-2xl p-8 mb-8 text-white overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-5">
                <div class="h-20 w-20 rounded-full overflow-hidden border-4 border-yellow-400 bg-white shadow-2xl ring-4 ring-white/20 flex-shrink-0">
                    @php
                        $fotoUrl = null;
                        if ($estudiante->fotos && $estudiante->fotos->count() > 0) {
                            $ultimaFoto = $estudiante->fotos->last();
                            $fotoUrl = asset('storage/' . $ultimaFoto->ruta);
                        } elseif ($estudiante->foto) {
                            $fotoUrl = asset('storage/' . $estudiante->foto);
                        }
                    @endphp

                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="{{ $estudiante->nombre }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-blue-700 text-white font-black text-2xl">
                            {{ strtoupper(substr($estudiante->nombre ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight drop-shadow-lg">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</h1>
                    <p class="text-blue-100 text-sm font-semibold mt-1 flex items-center gap-2">
                        <i class="fi fi-sr-graduation-cap"></i>
                        {{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}
                    </p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                            <i class="fi fi-sr-user"></i>
                            Padre: {{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}
                        </span>
                        @if($totalFaltas >= 3)
                            <span class="bg-red-600 px-3 py-1 rounded-full text-white text-xs font-bold flex items-center gap-1">
                                <i class="fi fi-sr-bell"></i>
                                CITACIÓN URGENTE
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('docente.estadisticas.index', ['curso' => $estudiante->curso_id]) }}" 
               class="bg-white/10 border border-white/30 text-white px-6 py-3 rounded-xl hover:bg-white/20 transition font-bold shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-7">
        <!-- Total Faltas -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 text-center border-t-4 border-blue-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="mx-auto mb-3 bg-blue-700 w-16 h-16 rounded-2xl shadow-lg flex items-center justify-center">
                <i class="fi fi-sr-triangle-warning text-white text-3xl"></i>
            </div>
            <span class="text-4xl font-black text-blue-900 dark:text-blue-200">{{ $totalFaltas }}</span>
            <p class="text-xs text-blue-500 dark:text-blue-400 font-black uppercase tracking-wider mt-1">Total Faltas</p>
        </div>

        <!-- Faltas Graves -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 text-center border-t-4 border-red-600 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="mx-auto mb-3 bg-red-600 w-16 h-16 rounded-2xl shadow-lg flex items-center justify-center">
                <i class="fi fi-sr-circle text-white text-3xl"></i>
            </div>
            <span class="text-4xl font-black text-red-700 dark:text-red-300">{{ $faltasGraves }}</span>
            <p class="text-xs text-red-500 dark:text-red-400 font-black uppercase tracking-wider mt-1">Faltas Graves</p>
        </div>

        <!-- Faltas Leves -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 text-center border-t-4 border-yellow-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="mx-auto mb-3 bg-yellow-500 w-16 h-16 rounded-2xl shadow-lg flex items-center justify-center">
                <i class="fi fi-sr-circle text-white text-3xl"></i>
            </div>
            <span class="text-4xl font-black text-yellow-700 dark:text-yellow-300">{{ $faltasLeves }}</span>
            <p class="text-xs text-yellow-600 dark:text-yellow-400 font-black uppercase tracking-wider mt-1">Faltas Leves</p>
        </div>
    </div>

    <!-- BOTÓN DE CITACIÓN -->
    @if($totalFaltas >= 3)
    <div class="mb-7">
        <a href="{{ route('docente.incidencia.citacion', $estudiante->id) }}" 
           class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-black text-lg shadow-xl hover:shadow-2xl transition-all">
            <i class="fi fi-sr-comment-alt text-3xl"></i>
            Enviar Citación por WhatsApp
        </a>
    </div>
    @endif

    <!-- GRÁFICO DE DONA: INCIDENCIAS POR MATERIA -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-7">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-blue-800 flex justify-between items-center">
                <h3 class="font-black text-white text-base flex items-center gap-2">
                    <i class="fi fi-sr-chart-pie"></i>
                    Incidencias por Materia
                </h3>
                <span class="text-xs text-blue-200 font-semibold">{{ $totalFaltas }} total</span>
            </div>
            <div class="p-6">
                @if($totalFaltas > 0 && $incidenciasPorMateria->count() > 0)
                    <div class="relative" style="height: 320px;">
                        <canvas id="chartMateria"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        @foreach($incidenciasPorMateria as $materia => $total)
                            @php
                                $porcentaje = $totalFaltas > 0 ? round(($total / $totalFaltas) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between text-xs px-2 py-1.5 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                                <span class="font-semibold text-gray-700 dark:text-gray-300 truncate">{{ $materia ?? 'Sin materia' }}</span>
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

        <!-- RESUMEN POR TIPO -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-yellow-600 flex justify-between items-center">
                <h3 class="font-black text-white text-base flex items-center gap-2">
                    <i class="fi fi-sr-chart-histogram"></i>
                    Resumen por Tipo
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @php
                    $tiposContados = $estudiante->incidencias->groupBy('tipo')->map->count();
                @endphp
                @forelse($tiposContados as $tipo => $cantidad)
                    @php
                        $porcentaje = $totalFaltas > 0 ? round(($cantidad / $totalFaltas) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $tipo }}</span>
                            <span class="font-black text-yellow-700 dark:text-yellow-300">{{ $cantidad }} <span class="text-xs text-yellow-500 font-semibold">({{ $porcentaje }}%)</span></span>
                        </div>
                        <div class="w-full bg-yellow-100 dark:bg-yellow-900/40 rounded-full h-3.5 shadow-inner overflow-hidden">
                            <div class="h-3.5 rounded-full bg-yellow-500 shadow" style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                        <p class="font-semibold">No hay incidencias registradas.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- HISTORIAL DE INCIDENCIAS -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-blue-800">
            <h3 class="font-black text-white text-base flex items-center gap-2">
                <i class="fi fi-sr-document"></i>
                Historial de Incidencias
            </h3>
        </div>
        <div class="overflow-x-auto p-2">
            <table class="w-full text-sm">
                <thead class="text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-black uppercase tracking-wider">#</th>
                        <th class="text-left px-4 py-3 text-xs font-black uppercase tracking-wider">Fecha</th>
                        <th class="text-left px-4 py-3 text-xs font-black uppercase tracking-wider">Materia</th>
                        <th class="text-left px-4 py-3 text-xs font-black uppercase tracking-wider">Tipo</th>
                        <th class="text-left px-4 py-3 text-xs font-black uppercase tracking-wider">Descripción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($estudiante->incidencias->sortByDesc('created_at') as $index => $incidencia)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3 font-black text-blue-700 dark:text-blue-300">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">{{ $incidencia->materia ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-black shadow-sm
                                @switch($incidencia->tipo)
                                    @case('Atraso')
                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                                        @break
                                    @case('Falto a clases')
                                        bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300
                                        @break
                                    @case('Falta con licencia')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                        @break
                                    @case('Sin uniforme')
                                        bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300
                                        @break
                                    @case('Indisciplina')
                                        bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @endswitch">
                                {{ $incidencia->tipo }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                            {{ $incidencia->descripcion ?: 'Sin descripción' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400 font-semibold">
                            <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                            No hay incidencias registradas.
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
        <a href="{{ route('docente.estadisticas.index', ['curso' => $estudiante->curso_id]) }}" 
           class="inline-flex items-center gap-2 text-blue-700 dark:text-blue-400 font-bold hover:text-blue-900 hover:underline transition">
            <i class="fi fi-sr-arrow-left"></i>
            Volver a Estadísticas
        </a>
    </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Paleta institucional
    const paletaInstitucional = [
        '#1e3a8a', '#1d4ed8', '#2563eb', '#3b82f6',
        '#0ea5e9', '#06b6d4', '#facc15', '#eab308',
        '#f59e0b', '#dc2626', '#16a34a', '#7c3aed',
    ];

    @if($totalFaltas > 0 && $incidenciasPorMateria->count() > 0)
    const ctxMateria = document.getElementById('chartMateria');
    if (ctxMateria) {
        new Chart(ctxMateria, {
            type: 'doughnut',
            data: {
                labels: @json($incidenciasPorMateria->keys()),
                datasets: [{
                    data: @json($incidenciasPorMateria->values()),
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
                    legend: {
                        display: false
                    },
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
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }
    @endif
});
</script>

@endsection