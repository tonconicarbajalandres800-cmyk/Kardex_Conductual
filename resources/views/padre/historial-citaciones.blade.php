@extends('layouts.padre')

@section('title', 'Historial de Citaciones')

@section('header', '📅 Historial de Citaciones')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Estadísticas con estilos estilo registro académico -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
        <div class="border-2 border-[#1a3c6e] bg-[#f8faff] p-3 rounded-lg shadow text-center
                    dark:border-slate-600 dark:bg-gray-800">
            <span class="text-2xl font-bold text-[#1a3c6e] dark:text-slate-200">{{ $estadisticas['total'] }}</span>
            <p class="text-xs text-[#1a3c6e] font-semibold dark:text-slate-300">Total</p>
        </div>
        <div class="border-2 border-[#1a3c6e] bg-[#f8faff] p-3 rounded-lg shadow text-center
                    dark:border-slate-600 dark:bg-gray-800">
            <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $estadisticas['pendientes'] }}</span>
            <p class="text-xs text-yellow-600 font-semibold dark:text-yellow-400">Pendientes</p>
        </div>
        <div class="border-2 border-[#1a3c6e] bg-[#f8faff] p-3 rounded-lg shadow text-center
                    dark:border-slate-600 dark:bg-gray-800">
            <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $estadisticas['confirmadas'] }}</span>
            <p class="text-xs text-green-600 font-semibold dark:text-green-400">Confirmadas</p>
        </div>
        <div class="border-2 border-[#1a3c6e] bg-[#f8faff] p-3 rounded-lg shadow text-center
                    dark:border-slate-600 dark:bg-gray-800">
            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $estadisticas['reprogramadas'] }}</span>
            <p class="text-xs text-blue-600 font-semibold dark:text-blue-400">Reprogramadas</p>
        </div>
        <div class="border-2 border-[#1a3c6e] bg-[#f8faff] p-3 rounded-lg shadow text-center
                    dark:border-slate-600 dark:bg-gray-800">
            <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $estadisticas['justificadas'] }}</span>
            <p class="text-xs text-gray-600 font-semibold dark:text-gray-300">Justificadas</p>
        </div>
        <div class="border-2 border-[#1a3c6e] bg-[#f8faff] p-3 rounded-lg shadow text-center
                    dark:border-slate-600 dark:bg-gray-800">
            <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $estadisticas['canceladas'] }}</span>
            <p class="text-xs text-red-600 font-semibold dark:text-red-400">Canceladas</p>
        </div>
    </div>

    <!-- Tabla de citaciones con estilos estilo registro académico -->
    <div class="border-2 border-[#1a3c6e] rounded-lg overflow-hidden bg-white
                dark:border-slate-600 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#1a3c6e] text-white dark:bg-gray-900">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold">#</th>
                        <th class="text-left py-3 px-4 font-semibold">Estudiante</th>
                        <th class="text-left py-3 px-4 font-semibold">Curso</th>
                        <th class="text-left py-3 px-4 font-semibold">Fecha Programada</th>
                        <th class="text-left py-3 px-4 font-semibold">Motivo</th>
                        <th class="text-center py-3 px-4 font-semibold">Estado</th>
                        <th class="text-center py-3 px-4 font-semibold">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citaciones as $index => $citacion)
                    <tr class="border-b border-[#dce1ec] hover:bg-[#f8faff]
                               dark:border-gray-700 dark:hover:bg-gray-700/60">
                        <td class="py-3 px-4 text-[#1e2a3a] dark:text-gray-200">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-medium text-[#1e2a3a] dark:text-gray-100">{{ $citacion->estudiante->nombre }} {{ $citacion->estudiante->apellido }}</td>
                        <td class="py-3 px-4 text-[#1e2a3a] dark:text-gray-200">{{ $citacion->estudiante->curso ? $citacion->estudiante->curso->nombre . ' ' . $citacion->estudiante->curso->paralelo : 'N/A' }}</td>
                        <td class="py-3 px-4 text-[#1e2a3a] dark:text-gray-200">{{ \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-4 text-[#1e2a3a] dark:text-gray-200">{{ $citacion->motivo }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border-2
                                {{ $citacion->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800 border-yellow-400 dark:bg-yellow-900/40 dark:text-yellow-300 dark:border-yellow-700' : '' }}
                                {{ $citacion->estado === 'confirmada' ? 'bg-green-100 text-green-800 border-green-400 dark:bg-green-900/40 dark:text-green-300 dark:border-green-700' : '' }}
                                {{ $citacion->estado === 'reprogramada' ? 'bg-blue-100 text-blue-800 border-blue-400 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-700' : '' }}
                                {{ $citacion->estado === 'justificada' ? 'bg-gray-100 text-gray-800 border-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500' : '' }}
                                {{ $citacion->estado === 'cancelada' ? 'bg-red-100 text-red-800 border-red-400 dark:bg-red-900/40 dark:text-red-300 dark:border-red-700' : '' }}">
                                {{ ucfirst($citacion->estado) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($citacion->estado === 'pendiente')
                                <form method="POST" action="{{ route('padre.citacion.confirmar', $citacion->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-[#1a3c6e] text-white px-4 py-1 rounded text-xs font-semibold hover:bg-[#2a4c7e] transition-colors">
                                        ✅ Confirmar
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs dark:text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-[#7f8c8d] italic dark:text-gray-400">No hay citaciones registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('padre.dashboard') }}" class="text-[#1a3c6e] hover:underline font-semibold dark:text-slate-300">← Volver al Inicio</a>
    </div>
</div>
@endsection