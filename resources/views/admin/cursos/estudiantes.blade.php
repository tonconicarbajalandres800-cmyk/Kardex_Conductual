@extends('layouts.admin')

@section('title', 'Estudiantes de ' . $curso->nombre . ' ' . $curso->paralelo)

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-graduation-cap"></i>
                    ESTUDIANTES DEL CURSO
                </h1>
                <p class="text-blue-200 text-sm mt-1">
                    {{ $curso->nombre }} {{ $curso->paralelo }} - Gestión {{ $curso->gestion }}
                </p>
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
                        <i class="fi fi-sr-users"></i>
                        {{ $curso->estudiantes->count() }} estudiantes
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.cursos.index') }}" 
               class="bg-white/10 border border-white/30 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-semibold text-sm shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver a Cursos
            </a>
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

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $curso->estudiantes->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-users text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Estudiantes registrados</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Con Faltas</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ $curso->estudiantes->filter(fn($e) => $e->incidencias->count() > 0)->count() }}
                    </p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-triangle-warning text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tienen al menos 1 falta</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Alerta (3+)</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $curso->estudiantes->filter(fn($e) => $e->incidencias->count() >= 3)->count() }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-bell text-red-600 dark:text-red-300 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Requieren citación</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Sin Faltas</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $curso->estudiantes->filter(fn($e) => $e->incidencias->count() == 0)->count() }}
                    </p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-check-circle text-emerald-600 dark:text-emerald-300 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Estudiantes sin faltas</p>
        </div>
    </div>

    <!-- FILTRO DE BÚSQUEDA -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fi fi-sr-search"></i>
                    </span>
                    <input type="text" id="buscar-estudiante" placeholder="Buscar estudiante por nombre..." 
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>
            <div>
                <select id="filtro-faltas" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="todos">Todos los estudiantes</option>
                    <option value="con-faltas">Con faltas</option>
                    <option value="sin-faltas">Sin faltas</option>
                    <option value="alerta">Alerta (3+ faltas)</option>
                </select>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Mostrando: <span id="total-mostrados" class="font-bold text-blue-600 dark:text-blue-400">{{ $curso->estudiantes->count() }}</span> estudiantes
                </span>
            </div>
        </div>
    </div>

    @if($curso->estudiantes->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-12 text-center">
            <i class="fi fi-sr-inbox text-6xl block mb-4 text-gray-400"></i>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No hay estudiantes registrados</h3>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Este curso aún no tiene estudiantes asignados.</p>
        </div>
    @else
        <!-- GRID DE ESTUDIANTES -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="grid-estudiantes">
            @foreach($curso->estudiantes as $estudiante)
            @php
                $totalFaltas = $estudiante->incidencias->count();
                $faltasGraves = $estudiante->incidencias->whereIn('tipo', ['Indisciplina', 'Falto a clases', 'Sin uniforme'])->count();
                $colorBorder = $totalFaltas >= 3 ? 'border-red-500' : ($totalFaltas > 0 ? 'border-yellow-500' : 'border-emerald-500');
                $badgeColor = $totalFaltas >= 3 ? 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800' : ($totalFaltas > 0 ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800' : 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800');
            @endphp
            <div class="tarjeta-estudiante bg-white dark:bg-gray-800 rounded-2xl shadow-md border-l-4 {{ $colorBorder }} hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group"
                 data-faltas="{{ $totalFaltas }}">
                <div class="p-5">
                    <div class="flex items-start space-x-4">
                        <!-- Foto -->
                        <div class="flex-shrink-0">
                            @php
                                $fotoUrl = null;
                                if (isset($estudiante->fotos) && $estudiante->fotos->count() > 0) {
                                    $ultimaFoto = $estudiante->fotos->last();
                                    $fotoUrl = asset('storage/' . $ultimaFoto->ruta);
                                }
                            @endphp

                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" 
                                     alt="Foto de {{ $estudiante->nombre }}" 
                                     class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 shadow-md">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold shadow-md border-2 border-blue-300">
                                    {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Datos -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg truncate">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</h4>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-gray-500 dark:text-gray-400">
                                <span class="text-xs flex items-center gap-1">
                                    <i class="fi fi-sr-id-badge"></i>
                                    CI: {{ $estudiante->ci }}
                                </span>
                                <span class="text-xs">•</span>
                                <span class="text-xs flex items-center gap-1">
                                    <i class="fi fi-sr-marker"></i>
                                    N° {{ $estudiante->numero_lista ?? '-' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeColor }}">
                                    @if($totalFaltas >= 3)
                                        <i class="fi fi-sr-bell"></i> Alerta
                                    @elseif($totalFaltas > 0)
                                        <i class="fi fi-sr-triangle-warning"></i> Con faltas
                                    @else
                                        <i class="fi fi-sr-check-circle"></i> Sin faltas
                                    @endif
                                </span>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300">
                                    {{ $totalFaltas }} falta{{ $totalFaltas != 1 ? 's' : '' }}
                                </span>
                                @if($faltasGraves > 0)
                                    <span class="text-xs text-red-600 dark:text-red-400 font-semibold">
                                        ({{ $faltasGraves }} grave{{ $faltasGraves != 1 ? 's' : '' }})
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Barra de progreso de faltas -->
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                            <span>Faltas</span>
                            <span>{{ $totalFaltas }} / 3 (alerta)</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full transition-all duration-500 
                                {{ $totalFaltas >= 3 ? 'bg-gradient-to-r from-red-500 to-red-600' : ($totalFaltas > 0 ? 'bg-gradient-to-r from-yellow-400 to-yellow-500' : 'bg-gradient-to-r from-emerald-400 to-emerald-500') }}" 
                                style="width: {{ min(($totalFaltas / 3) * 100, 100) }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                        <a href="{{ route('admin.estudiantes.kardex', $estudiante->id) }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm hover:shadow-md">
                            <i class="fi fi-sr-chart-histogram"></i>
                            Ver Kardex
                        </a>
                        @if($totalFaltas >= 3)
                            <a href="{{ route('admin.citaciones.create', ['estudiante' => $estudiante->id]) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition shadow-sm hover:shadow-md">
                                <i class="fi fi-sr-megaphone"></i>
                                Citación
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Mensaje cuando el filtro no encuentra resultados -->
        <div id="sin-resultados" class="hidden mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-12 text-center">
            <i class="fi fi-sr-search text-6xl block mb-4 text-gray-400"></i>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No se encontraron estudiantes</h3>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Prueba con otro criterio de búsqueda o filtro.</p>
        </div>
    @endif

    <!-- LEYENDA -->
    <div class="mt-6 bg-gradient-to-r from-blue-900 to-blue-800 p-4 rounded-xl shadow-lg">
        <div class="flex flex-wrap items-center gap-6 text-sm text-white">
            <span class="font-black tracking-wide flex items-center gap-2">
                <i class="fi fi-sr-info"></i>
                LEYENDA:
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-emerald-400 rounded-full shadow"></span>
                <span class="font-semibold">Sin faltas</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full shadow"></span>
                <span class="font-semibold">1-2 faltas</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-red-400 rounded-full shadow"></span>
                <span class="font-semibold">3+ faltas → Citación</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full shadow"></span>
                <span class="font-semibold">Ver Kardex</span>
            </span>
        </div>
    </div>

</div>

<!-- JAVASCRIPT PARA FILTROS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buscarInput = document.getElementById('buscar-estudiante');
        const filtroFaltas = document.getElementById('filtro-faltas');
        const grid = document.getElementById('grid-estudiantes');
        const totalMostrados = document.getElementById('total-mostrados');
        const sinResultados = document.getElementById('sin-resultados');

        if (!grid) return; // Si no hay estudiantes, no ejecutar

        const tarjetas = grid.querySelectorAll('.tarjeta-estudiante');

        function filtrarEstudiantes() {
            const busqueda = buscarInput.value.toLowerCase().trim();
            const filtro = filtroFaltas.value;
            let total = 0;

            tarjetas.forEach(tarjeta => {
                const nombre = tarjeta.querySelector('h4')?.textContent?.toLowerCase() || '';
                const totalFaltas = parseInt(tarjeta.dataset.faltas || '0');
                
                let mostrar = true;

                // Filtro por nombre
                if (busqueda && !nombre.includes(busqueda)) {
                    mostrar = false;
                }

                // Filtro por faltas
                if (mostrar && filtro !== 'todos') {
                    if (filtro === 'con-faltas' && totalFaltas === 0) {
                        mostrar = false;
                    } else if (filtro === 'sin-faltas' && totalFaltas > 0) {
                        mostrar = false;
                    } else if (filtro === 'alerta' && totalFaltas < 3) {
                        mostrar = false;
                    }
                }

                tarjeta.style.display = mostrar ? '' : 'none';
                if (mostrar) total++;
            });

            totalMostrados.textContent = total;

            // Mostrar mensaje si no hay resultados
            if (sinResultados) {
                sinResultados.classList.toggle('hidden', total > 0);
            }
        }

        buscarInput.addEventListener('input', filtrarEstudiantes);
        filtroFaltas.addEventListener('change', filtrarEstudiantes);

        // Inicializar
        filtrarEstudiantes();
    });
</script>

@endsection