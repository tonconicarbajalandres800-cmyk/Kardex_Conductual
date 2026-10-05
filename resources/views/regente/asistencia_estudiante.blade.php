@extends('layouts.regente')

@section('title', 'Asistencia de ' . $estudiante->nombre)

@section('content')
<div>

    <!-- ENCABEZADO -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">📊 ASISTENCIA DE ESTUDIANTE</h1>
                <p class="text-blue-200 text-sm mt-1">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
            </div>
            <a href="{{ route('regente.asistencia') }}" class="bg-white/10 border border-white/30 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition">
                ← Volver
            </a>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Presentes</p>
            <p class="text-2xl font-bold text-green-600">{{ $estadisticas['presente'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Ausentes</p>
            <p class="text-2xl font-bold text-red-600">{{ $estadisticas['ausente'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Permisos</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $estadisticas['permiso'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-orange-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Tardanzas</p>
            <p class="text-2xl font-bold text-orange-600">{{ $estadisticas['tardanza'] }}</p>
        </div>
    </div>

    <!-- TABLA -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-800">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold">#</th>
                        <th class="text-left py-3 px-4 font-semibold">📅 Fecha</th>
                        <th class="text-left py-3 px-4 font-semibold">📊 Estado</th>
                        <th class="text-left py-3 px-4 font-semibold">📝 Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asistencias as $index => $a)
                    <tr class="border-t hover:bg-blue-50 transition {{ $loop->even ? 'bg-gray-50/50' : '' }}">
                        <td class="py-3 px-4 font-bold text-blue-600">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $a->fecha->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border
                                {{ $a->estado == 'presente' ? 'bg-green-100 text-green-700 border-green-300' : '' }}
                                {{ $a->estado == 'ausente' ? 'bg-red-100 text-red-700 border-red-300' : '' }}
                                {{ $a->estado == 'permiso' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : '' }}
                                {{ $a->estado == 'tardanza' ? 'bg-orange-100 text-orange-700 border-orange-300' : '' }}">
                                {{ $a->estado }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $a->motivo ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">No hay registros de asistencia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t bg-gray-50">
            {{ $asistencias->links() }}
        </div>
    </div>

</div>
@endsection