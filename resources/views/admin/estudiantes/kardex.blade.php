@extends('layouts.admin')

@section('title', 'Kardex de ' . $estudiante->nombre . ' ' . $estudiante->apellido)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">📋 REGISTRO DE SEGUIMIENTO ACADÉMICO - {{ date('Y') }}</h1>
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">← Volver</a>
    </div>

    <!-- Encabezado del colegio -->
    <div class="bg-white p-6 rounded-lg shadow mb-6 border">
        <div class="flex justify-between items-center">
            <div>
                <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo" class="h-16 w-16">
            </div>
            <div class="text-center">
                <h2 class="text-xl font-bold">UNIDAD EDUCATIVA "GRAL JOSE DE SAN MARTIN A"</h2>
                <p class="text-sm">EDUCACIÓN SECUNDARIA COMUNITARIA PRODUCTIVA</p>
                <p class="text-sm font-semibold">REGISTRO DE SEGUIMIENTO ACADÉMICO - {{ date('Y') }}</p>
            </div>
            <div>
                @if($estudiante->foto)
                    <img src="{{ asset('storage/' . $estudiante->foto) }}" alt="Foto" class="h-20 w-20 rounded-full object-cover border">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-3xl">👤</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Datos del estudiante -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><span class="font-semibold">ESTUDIANTE:</span> {{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
                <p><span class="font-semibold">CURSO:</span> {{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}</p>
            </div>
            <div>
                <p><span class="font-semibold">PADRE/TUTOR:</span> {{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}</p>
                <p><span class="font-semibold">TELÉFONO:</span> {{ $estudiante->padre ? $estudiante->padre->telefono : 'N/A' }}</p>
            </div>
        </div>
        <div class="mt-2">
            <p><span class="font-semibold">DOMICILIO:</span> {{ $estudiante->direccion ?? 'No registrado' }}</p>
        </div>
    </div>

    <!-- Tabla de incidencias -->
    <div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
        <h3 class="font-semibold text-gray-700 mb-3">📋 HISTORIAL DE INCIDENCIAS</h3>
        <table class="w-full text-sm border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-3 py-2 text-left">FECHA</th>
                    <th class="border border-gray-300 px-3 py-2 text-left">ÁREA / MAESTRA</th>
                    <th class="border border-gray-300 px-3 py-2 text-left">OBSERVACIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estudiante->incidencias as $incidencia)
                <tr>
                    <td class="border border-gray-300 px-3 py-2">{{ $incidencia->created_at->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 px-3 py-2">{{ $incidencia->materia ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-3 py-2">
                        @php
                            $codigo = $tiposFalta[$incidencia->tipo] ?? '?';
                        @endphp
                        <span class="font-bold">{{ $codigo }})</span> {{ $incidencia->tipo }}
                        @if($incidencia->descripcion)
                            <br><span class="text-gray-500 text-xs">{{ $incidencia->descripcion }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="border border-gray-300 px-3 py-4 text-center text-gray-500">No hay incidencias registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Leyenda de códigos (opcional) -->
    <div class="mt-4 bg-white p-4 rounded-lg shadow text-xs">
        <h4 class="font-semibold text-gray-700 mb-2">CÓDIGOS DE FALTA:</h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-1">
            @foreach($tiposFalta as $nombre => $codigo)
                <span><span class="font-bold">{{ $codigo }})</span> {{ $nombre }}</span>
            @endforeach
        </div>
    </div>

    <div class="mt-4 text-right text-xs text-gray-400">
        Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</div>
@endsection