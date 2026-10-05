@extends('layouts.padre')

@section('title', 'Kardex de ' . $estudiante->nombre)

@section('header', '📋 Kardex de ' . $estudiante->nombre)

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-lg shadow p-6">

    <!-- Botones -->
    <div class="flex justify-end mb-4 space-x-2">
        <a href="{{ route('padre.kardex.pdf', $estudiante->id) }}" 
           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition flex items-center gap-2">
            📄 Descargar PDF
        </a>
        <a href="{{ route('padre.dashboard') }}" 
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition flex items-center gap-2">
            ← Volver
        </a>
    </div>

    <!-- ========================================== -->
    <!-- ENCABEZADO CON ESCUDO                      -->
    <!-- ========================================== -->
    <div class="flex items-center justify-between border-b-4 border-blue-800 pb-4 mb-4">
        <div class="flex-shrink-0">
            <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo" class="h-20 w-20 object-contain">
        </div>
        <div class="text-center flex-1">
            <h2 class="text-xl font-bold text-blue-900">UNIDAD EDUCATIVA "GRAL JOSE DE SAN MARTIN A"</h2>
            <p class="text-sm font-semibold text-gray-700">EDUCACIÓN SECUNDARIA COMUNITARIA PRODUCTIVA</p>
            <p class="text-sm font-bold text-red-700 bg-red-50 inline-block px-4 py-1 rounded-full mt-1">
                📋 REGISTRO DE SEGUIMIENTO ACADÉMICO - {{ date('Y') }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <!-- Foto del estudiante -->
            @php
                $fotoUrl = null;
                $idEstudiante = $estudiante->id ?? 0;
                $nombreEstudiante = $estudiante->nombre ?? '';

                // Buscar la foto
                $rutas = [
                    public_path('images/storage/fotos/' . $idEstudiante . '.jpg'),
                    public_path('images/storage/fotos/' . $idEstudiante . '.png'),
                    public_path('images/storage/fotos/' . $idEstudiante . '.jpeg'),
                    public_path('storage/fotos/' . $idEstudiante . '.jpg'),
                    public_path('storage/fotos/' . $idEstudiante . '.png'),
                ];

                foreach ($rutas as $ruta) {
                    if (file_exists($ruta)) {
                        $fotoUrl = asset(str_replace(public_path(), '', $ruta));
                        break;
                    }
                }

                // Si no se encuentra, buscar en relación fotos
                if (!$fotoUrl && isset($estudiante->fotos) && $estudiante->fotos->count() > 0) {
                    $ultimaFoto = $estudiante->fotos->last();
                    $nombreArchivo = basename($ultimaFoto->ruta);
                    $fotoUrl = asset('storage/fotos/' . $nombreArchivo);
                }
            @endphp

            @if($fotoUrl)
                <img src="{{ $fotoUrl }}" 
                     alt="Foto de {{ $nombreEstudiante }}" 
                     class="h-20 w-20 rounded-full object-cover border-4 border-blue-400 shadow-lg">
            @else
                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-3xl border-4 border-blue-400 shadow-lg">
                    {{ strtoupper(substr($nombreEstudiante, 0, 1)) }}
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================== -->
    <!-- DATOS DEL ESTUDIANTE                       -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">👤 Estudiante</p>
            <p class="text-lg font-bold text-gray-800">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">📚 Curso</p>
            <p class="text-lg font-bold text-gray-800">{{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">👨‍👩‍👦 Padre/Tutor</p>
            <p class="text-lg font-bold text-gray-800">{{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">📞 Teléfono</p>
            <p class="text-lg font-bold text-gray-800">{{ $estudiante->padre ? $estudiante->padre->telefono : 'N/A' }}</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- HISTORIAL DE INCIDENCIAS                   -->
    <!-- ========================================== -->
    <h3 class="font-bold text-red-700 mb-3 text-lg flex items-center gap-2">
        📋 HISTORIAL DE INCIDENCIAS
        <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold">
            Total: {{ $estudiante->incidencias->count() }}
        </span>
    </h3>

    <div class="overflow-x-auto rounded-lg border border-gray-300">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-red-600 to-red-700 text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">📅 FECHA</th>
                    <th class="px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">📚 ÁREA / MAESTRA</th>
                    <th class="px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">⚠️ OBSERVACIONES</th>
                    <th class="px-4 py-3 text-left font-bold uppercase tracking-wider text-xs">📝 DESCRIPCIÓN</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($estudiante->incidencias as $incidencia)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium">{{ $incidencia->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $incidencia->materia ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
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
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        @if($incidencia->descripcion)
                            @php
                                $esGrave = in_array($incidencia->tipo, ['Indisciplina', 'Falto a clases', 'Sin uniforme']);
                            @endphp
                            <span class="text-sm {{ $esGrave ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                                {{ $incidencia->descripcion }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">Sin descripción</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <span class="text-4xl block mb-2">📭</span>
                        No hay incidencias registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- LEYENDA DE CÓDIGOS                         -->
    <!-- ========================================== -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <h4 class="font-bold text-gray-700 mb-2 text-sm flex items-center gap-2">
            📌 CÓDIGOS DE FALTA
            <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full font-bold">Referencia</span>
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            @foreach($tiposFalta as $nombre => $codigo)
                <span class="bg-white hover:bg-gray-100 px-3 py-1 rounded-lg text-sm font-medium border border-gray-200 transition">
                    <span class="font-bold text-red-600">{{ $codigo }})</span> {{ $nombre }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PIE DE PÁGINA                              -->
    <!-- ========================================== -->
    <div class="mt-6 text-right text-xs text-gray-400 border-t pt-3">
        Generado el {{ now()->format('d/m/Y H:i') }} | Sistema Kardex Conductual
    </div>

</div>
@endsection