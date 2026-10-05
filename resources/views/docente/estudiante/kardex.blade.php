@extends('layouts.docente')

@section('title', 'Kardex de ' . $estudiante->nombre . ' ' . $estudiante->apellido)

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Botones superiores -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">📋 KARDEX CONDUCTUAL</h1>
        <div class="flex space-x-3">
            <a href="{{ route('docente.reporte.pdf', $estudiante->id) }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                📄 Descargar PDF
            </a>
            <a href="{{ url()->previous() }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                ← Volver
            </a>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ENCABEZADO DEL COLEGIO CON FOTO CORREGIDA  -->
    <!-- ========================================== -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl shadow-lg mb-6 border-2 border-blue-200">
        <div class="flex justify-between items-center">
            <!-- Escudo -->
            <div class="flex-shrink-0">
                <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo" class="h-20 w-20 object-contain">
            </div>

            <!-- Títulos -->
            <div class="text-center flex-1">
                <h2 class="text-2xl font-black text-blue-900 tracking-wide">UNIDAD EDUCATIVA "GRAL JOSE DE SAN MARTIN A"</h2>
                <p class="text-sm font-bold text-blue-700">EDUCACIÓN SECUNDARIA COMUNITARIA PRODUCTIVA</p>
                <p class="text-lg font-black text-indigo-700 mt-1 bg-indigo-100 inline-block px-6 py-1 rounded-full">
                    📋 REGISTRO DE SEGUIMIENTO ACADÉMICO - {{ date('Y') }}
                </p>
            </div>

            <!-- ========================================== -->
            <!-- FOTO DEL ESTUDIANTE (CORREGIDA)            -->
            <!-- ========================================== -->
            <div class="flex-shrink-0">
                @php
                    $fotoUrl = null;
                    $idEstudiante = $estudiante->id ?? 0;
                    $nombreEstudiante = $estudiante->nombre ?? '';

                    // Buscar la foto en la carpeta correcta
                    $rutasPosibles = [
                        'images/storage/fotos/' . $idEstudiante . '.jpg',
                        'images/storage/fotos/' . $idEstudiante . '.png',
                        'storage/fotos/' . $idEstudiante . '.jpg',
                        'storage/fotos/' . $idEstudiante . '.png',
                        'fotos/' . $idEstudiante . '.jpg',
                    ];

                    foreach ($rutasPosibles as $ruta) {
                        if (file_exists(public_path($ruta))) {
                            $fotoUrl = asset($ruta);
                            break;
                        }
                    }

                    // Si no se encuentra por ID, buscar en la relación fotos
                    if (!$fotoUrl && isset($estudiante->fotos) && $estudiante->fotos->count() > 0) {
                        $ultimaFoto = $estudiante->fotos->last();
                        $nombreArchivo = basename($ultimaFoto->ruta);
                        $rutasRelacion = [
                            'images/storage/fotos/' . $nombreArchivo,
                            'storage/fotos/' . $nombreArchivo,
                            'fotos/' . $nombreArchivo,
                        ];
                        foreach ($rutasRelacion as $ruta) {
                            if (file_exists(public_path($ruta))) {
                                $fotoUrl = asset($ruta);
                                break;
                            }
                        }
                    }
                @endphp

                @if($fotoUrl)
                    <img src="{{ $fotoUrl }}" 
                         alt="Foto de {{ $nombreEstudiante }}" 
                         class="h-24 w-24 rounded-full object-cover border-4 border-blue-400 shadow-lg">
                @else
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-black text-4xl border-4 border-blue-400 shadow-lg">
                        {{ strtoupper(substr($nombreEstudiante, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- DATOS DEL ESTUDIANTE (MEJORADO)            -->
    <!-- ========================================== -->
    <div class="bg-white p-6 rounded-xl shadow-lg mb-6 border-l-4 border-indigo-500">
        <h3 class="text-lg font-black text-indigo-700 mb-4 flex items-center gap-2">
            👤 DATOS DEL ESTUDIANTE
            <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold">
                ID: {{ $estudiante->id }}
            </span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Estudiante</p>
                <p class="text-lg font-bold text-gray-800">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Curso</p>
                <p class="text-lg font-bold text-gray-800">{{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Padre/Tutor</p>
                <p class="text-lg font-bold text-gray-800">{{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Teléfono</p>
                <p class="text-lg font-bold text-gray-800">{{ $estudiante->padre ? $estudiante->padre->telefono : 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Domicilio</p>
                <p class="text-lg font-bold text-gray-800">{{ $estudiante->direccion ?? 'No registrado' }}</p>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABLA DE INCIDENCIAS (MEJORADA)            -->
    <!-- ========================================== -->
    <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <h3 class="text-lg font-black text-red-600 mb-4 flex items-center gap-2">
            📋 HISTORIAL DE INCIDENCIAS
            <span class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-full font-bold">
                Total: {{ $estudiante->incidencias->count() }}
            </span>
        </h3>

        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-red-600 to-red-700 text-white">
                    <th class="border border-red-400 px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">📅 Fecha</th>
                    <th class="border border-red-400 px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">📚 Área / Maestra</th>
                    <th class="border border-red-400 px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">⚠️ Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estudiante->incidencias as $incidencia)
                <tr class="hover:bg-gray-50 transition {{ $loop->even ? 'bg-gray-50/50' : '' }}">
                    <td class="border border-gray-300 px-4 py-3 font-medium">{{ $incidencia->created_at->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 px-4 py-3">{{ $incidencia->materia ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-3">
                        @php
                            $codigo = $tiposFalta[$incidencia->tipo] ?? '?';
                            $color = match($incidencia->tipo) {
                                'Atraso' => 'text-yellow-600',
                                'Falto a clases' => 'text-red-600',
                                'Indisciplina' => 'text-orange-600',
                                default => 'text-gray-700'
                            };
                        @endphp
                        <span class="font-bold {{ $color }}">{{ $codigo }})</span>
                        <span class="font-medium">{{ $incidencia->tipo }}</span>
                        @if($incidencia->descripcion)
                            <br><span class="text-gray-500 text-xs italic">📝 {{ $incidencia->descripcion }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                        <span class="text-4xl block mb-2">📭</span>
                        No hay incidencias registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- LEYENDA DE CÓDIGOS (MEJORADA)              -->
    <!-- ========================================== -->
    <div class="mt-6 bg-white p-5 rounded-xl shadow-lg border-2 border-gray-200">
        <h4 class="font-black text-gray-700 mb-3 text-sm flex items-center gap-2">
            📌 CÓDIGOS DE FALTA
            <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-bold">Referencia</span>
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            @foreach($tiposFalta as $nombre => $codigo)
                <span class="bg-gray-50 hover:bg-gray-100 px-3 py-2 rounded-lg text-sm font-medium border border-gray-200 transition">
                    <span class="font-black text-indigo-600">{{ $codigo }})</span> {{ $nombre }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Pie de página -->
    <div class="mt-6 text-right text-xs text-gray-400 border-t pt-3">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema Kardex Conductual
    </div>

</div>
@endsection