@extends('layouts.padre')

@section('title', 'Asistencia del Estudiante')

@section('header', 'Asistencia del Estudiante')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- SELECTOR DE HIJO -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                <i class="fi fi-sr-child"></i>
                Seleccionar Hijo:
            </label>
            <select id="hijo-select" class="border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-1 focus:ring-slate-400 focus:border-slate-400 min-w-[300px] text-sm">
                @foreach($hijos as $h)
                    <option value="{{ $h->id }}" {{ $estudiante->id == $h->id ? 'selected' : '' }}>
                        {{ $h->nombre }} {{ $h->apellido }} — {{ $h->curso ? $h->curso->nombre . ' ' . $h->curso->paralelo : 'Sin curso' }}
                    </option>
                @endforeach
            </select>
            <button onclick="cambiarHijo()" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-md transition text-sm font-medium flex items-center gap-2">
                <i class="fi fi-sr-search"></i>
                Consultar
            </button>
        </div>
    </div>

    <!-- INFORMACIÓN DEL ESTUDIANTE -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                    <i class="fi fi-sr-graduation-cap"></i>
                    Curso: {{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'Sin curso' }}
                </p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <div class="text-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-md">
                    <p class="text-xs text-emerald-700 dark:text-emerald-400 uppercase tracking-wide font-medium flex items-center justify-center gap-1">
                        <i class="fi fi-sr-check-circle"></i>
                        Presentes
                    </p>
                    <p class="text-lg font-semibold text-emerald-900 dark:text-emerald-200">{{ $estadisticas['presente'] ?? 0 }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-md">
                    <p class="text-xs text-red-700 dark:text-red-400 uppercase tracking-wide font-medium flex items-center justify-center gap-1">
                        <i class="fi fi-sr-cross-circle"></i>
                        Ausentes
                    </p>
                    <p class="text-lg font-semibold text-red-900 dark:text-red-200">{{ $estadisticas['ausente'] ?? 0 }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-md">
                    <p class="text-xs text-amber-700 dark:text-amber-400 uppercase tracking-wide font-medium flex items-center justify-center gap-1">
                        <i class="fi fi-sr-document"></i>
                        Permisos
                    </p>
                    <p class="text-lg font-semibold text-amber-900 dark:text-amber-200">{{ $estadisticas['permiso'] ?? 0 }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-800 rounded-md">
                    <p class="text-xs text-orange-700 dark:text-orange-400 uppercase tracking-wide font-medium flex items-center justify-center gap-1">
                        <i class="fi fi-sr-clock"></i>
                        Atrasos
                    </p>
                    <p class="text-lg font-semibold text-orange-900 dark:text-orange-200">{{ $estadisticas['tardanza'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTROS DE FECHA -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="estudiante_id" value="{{ $estudiante->id }}">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">Desde</label>
                <input type="date" name="fecha_desde" value="{{ $fechaDesde }}" 
                       class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white text-sm focus:ring-1 focus:ring-slate-400 focus:border-slate-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}" 
                       class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white text-sm focus:ring-1 focus:ring-slate-400 focus:border-slate-400">
            </div>
            <div>
                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-md transition text-sm font-medium flex items-center gap-2">
                    <i class="fi fi-sr-filter"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- TABLA 1: AUSENCIAS -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-red-50 dark:bg-red-900/20 border-b-2 border-red-300 dark:border-red-800 flex items-center justify-between">
            <h3 class="text-base font-semibold text-red-900 dark:text-red-200 flex items-center gap-2">
                <i class="fi fi-sr-cross-circle"></i>
                Ausencias injustificadas
            </h3>
            <span class="text-xs text-red-700 dark:text-red-300 font-medium">
                {{ $ausentes->count() }} {{ $ausentes->count() == 1 ? 'registro' : 'registros' }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">#</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Fecha</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Motivo</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($ausentes as $index => $a)
                    <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition">
                        <td class="py-3 px-4 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 text-gray-800 dark:text-gray-200 font-medium">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $a->motivo ?? '—' }}</td>
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $a->registrado_por ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-sm text-gray-400">
                            <i class="fi fi-sr-inbox text-2xl block mb-2"></i>
                            No hay ausencias registradas en este periodo.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABLA 2: ATRASOS -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-orange-50 dark:bg-orange-900/20 border-b-2 border-orange-300 dark:border-orange-800 flex items-center justify-between">
            <h3 class="text-base font-semibold text-orange-900 dark:text-orange-200 flex items-center gap-2">
                <i class="fi fi-sr-clock"></i>
                Atrasos
            </h3>
            <span class="text-xs text-orange-700 dark:text-orange-300 font-medium">
                {{ $atrasos->count() }} {{ $atrasos->count() == 1 ? 'registro' : 'registros' }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">#</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Fecha</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Motivo</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($atrasos as $index => $a)
                    <tr class="hover:bg-orange-50/30 dark:hover:bg-orange-900/10 transition">
                        <td class="py-3 px-4 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 text-gray-800 dark:text-gray-200 font-medium">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $a->motivo ?? '—' }}</td>
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $a->registrado_por ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-sm text-gray-400">
                            <i class="fi fi-sr-inbox text-2xl block mb-2"></i>
                            No hay atrasos registrados en este periodo.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABLA 3: PERMISOS -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-amber-50 dark:bg-amber-900/20 border-b-2 border-amber-300 dark:border-amber-800 flex items-center justify-between">
            <h3 class="text-base font-semibold text-amber-900 dark:text-amber-200 flex items-center gap-2">
                <i class="fi fi-sr-document"></i>
                Permisos
            </h3>
            <span class="text-xs text-amber-700 dark:text-amber-300 font-medium">
                {{ $permisos->count() }} {{ $permisos->count() == 1 ? 'registro' : 'registros' }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">#</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Fecha</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Motivo</th>
                        <th class="text-left py-3 px-4 font-medium text-xs uppercase tracking-wide">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($permisos as $index => $a)
                    <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition">
                        <td class="py-3 px-4 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 text-gray-800 dark:text-gray-200 font-medium">{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $a->motivo ?? '—' }}</td>
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $a->registrado_por ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-sm text-gray-400">
                            <i class="fi fi-sr-inbox text-2xl block mb-2"></i>
                            No hay permisos registrados en este periodo.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function cambiarHijo() {
        var select = document.getElementById('hijo-select');
        var url = new URL(window.location.href);
        url.searchParams.set('estudiante_id', select.value);
        window.location.href = url.toString();
    }
</script>
@endsection