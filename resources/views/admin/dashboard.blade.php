@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Botón modo oscuro -->
    <div class="flex justify-end">
        <button id="theme-toggle" type="button"
            class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl shadow-sm border transition-colors
                   bg-white border-gray-200 text-gray-700 hover:bg-gray-100
                   dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100 dark:hover:bg-gray-700">
            <i id="theme-toggle-icon" class="fi fi-sr-moon text-base"></i>
            <span id="theme-toggle-label">Modo oscuro</span>
        </button>
    </div>

    <!-- Encabezado con color institucional -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-dashboard"></i>
                    Panel de Control
                </h1>
                <p class="text-blue-200 text-sm mt-1">Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-blue-200 flex items-center gap-2 justify-end">
                    <i class="fi fi-sr-calendar"></i>
                    {{ now()->format('d \d\e F \d\e Y') }}
                </p>
                <span class="inline-flex items-center gap-1 bg-yellow-400 text-blue-900 text-xs font-bold px-3 py-1 rounded-full mt-1">
                    <i class="fi fi-sr-calendar-check"></i>
                    Gestión {{ date('Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Usuarios -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border-l-4 border-blue-700 hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Usuarios</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $totalUsuarios }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-users text-blue-700 dark:text-blue-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-blue-700 h-1.5 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <!-- Estudiantes -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border-l-4 border-blue-700 hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Estudiantes</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $totalEstudiantes }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-graduation-cap text-blue-700 dark:text-blue-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-blue-700 h-1.5 rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <!-- Docentes -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border-l-4 border-blue-700 hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Docentes</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $totalDocentes ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-chalkboard-user text-blue-700 dark:text-blue-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-blue-700 h-1.5 rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <!-- Incidencias -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Incidencias Totales</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalIncidencias }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-triangle-warning text-yellow-600 dark:text-yellow-300 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 45%"></div>
            </div>
        </div>

    </div>

    <!-- Segunda fila: Cursos, citaciones, incidencias hoy -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Cursos -->
        <div class="bg-blue-800 text-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-200 flex items-center gap-2">
                        <i class="fi fi-sr-book"></i>
                        Cursos Activos
                    </p>
                    <p class="text-3xl font-bold mt-1">{{ $totalCursos }}</p>
                </div>
                <i class="fi fi-sr-book text-4xl opacity-40"></i>
            </div>
            <div class="mt-2 flex items-center text-xs text-blue-200">
                <span>Total cursos registrados</span>
            </div>
        </div>

        <!-- Citaciones Pendientes -->
        <div class="bg-yellow-500 text-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-100 flex items-center gap-2">
                        <i class="fi fi-sr-calendar-clock"></i>
                        Citaciones Pendientes
                    </p>
                    <p class="text-3xl font-bold mt-1">{{ $citacionesPendientes ?? 0 }}</p>
                </div>
                <i class="fi fi-sr-calendar-clock text-4xl opacity-40"></i>
            </div>
            <div class="mt-2 flex items-center text-xs text-yellow-100">
                <span>Requieren atención</span>
            </div>
        </div>

        <!-- Incidencias Hoy -->
        <div class="bg-red-700 text-white p-5 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-200 flex items-center gap-2">
                        <i class="fi fi-sr-bell"></i>
                        Incidencias Hoy
                    </p>
                    <p class="text-3xl font-bold mt-1">{{ $incidenciasHoy }}</p>
                </div>
                <i class="fi fi-sr-bell text-4xl opacity-40"></i>
            </div>
            <div class="mt-2 flex items-center text-xs text-red-200">
                <span>Registradas hoy</span>
            </div>
        </div>

    </div>

    <!-- Actividades Recientes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <i class="fi fi-sr-list"></i>
                Actividades Recientes
            </h3>
            <span class="text-xs text-gray-400">Últimas incidencias</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                    <tr>
                        <th class="text-left py-2 px-3 rounded-l-lg">#</th>
                        <th class="text-left py-2 px-3">Actividad</th>
                        <th class="text-left py-2 px-3">Estudiante</th>
                        <th class="text-left py-2 px-3">Docente</th>
                        <th class="text-left py-2 px-3 rounded-r-lg">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividades ?? [] as $index => $act)
                    <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-2 px-3 font-bold text-blue-600 dark:text-blue-400">{{ $index + 1 }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-xs font-semibold">
                                {{ $act['actividad'] }}
                            </span>
                        </td>
                        <td class="py-2 px-3 font-medium text-gray-800 dark:text-gray-200">{{ $act['estudiante'] }}</td>
                        <td class="py-2 px-3 text-gray-600 dark:text-gray-400">{{ $act['docente'] }}</td>
                        <td class="py-2 px-3 text-gray-500 dark:text-gray-400 text-xs">{{ $act['fecha'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-400">
                            <i class="fi fi-sr-inbox text-3xl block mb-2"></i>
                            No hay actividades recientes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.usuarios.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-blue-700 hover:bg-blue-50 dark:hover:bg-gray-700 group">
            <i class="fi fi-sr-users text-blue-700 dark:text-blue-300 text-3xl block mb-2 group-hover:scale-110 transition"></i>
            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Usuarios</h4>
            <p class="text-xs text-gray-400">Gestionar</p>
        </a>
        <a href="{{ route('admin.cursos.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-blue-700 hover:bg-blue-50 dark:hover:bg-gray-700 group">
            <i class="fi fi-sr-book text-blue-700 dark:text-blue-300 text-3xl block mb-2 group-hover:scale-110 transition"></i>
            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Cursos</h4>
            <p class="text-xs text-gray-400">Administrar</p>
        </a>
        <a href="{{ route('admin.citaciones.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-yellow-500 hover:bg-yellow-50 dark:hover:bg-gray-700 group">
            <i class="fi fi-sr-document text-yellow-600 dark:text-yellow-400 text-3xl block mb-2 group-hover:scale-110 transition"></i>
            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Citaciones</h4>
            <p class="text-xs text-gray-400">Ver todas</p>
        </a>
        <a href="{{ route('admin.configuracion.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-purple-600 hover:bg-purple-50 dark:hover:bg-gray-700 group">
            <i class="fi fi-sr-settings text-purple-600 dark:text-purple-400 text-3xl block mb-2 group-hover:scale-110 transition"></i>
            <h4 class="font-semibold text-gray-700 dark:text-gray-200">Configuración</h4>
            <p class="text-xs text-gray-400">Personalizar</p>
        </a>
    </div>

</div>

<!-- Script modo oscuro -->
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
            localStorage.setItem('theme', 'dark');
        } else {
            root.classList.remove('dark');
            icon.className = 'fi fi-sr-moon text-base';
            label.textContent = 'Modo oscuro';
            localStorage.setItem('theme', 'light');
        }
    }

    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(saved || (prefersDark ? 'dark' : 'light'));

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isDark = root.classList.contains('dark');
            applyTheme(isDark ? 'light' : 'dark');
        });
    }
})();
</script>

@endsection