@extends('layouts.docente')

@section('title', 'Lista de Estudiantes - ' . $curso->nombre)

@section('content')
<div>

    <!-- ENCABEZADO CON COLOR INSTITUCIONAL -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fi fi-sr-users"></i>
                    LISTA DE ESTUDIANTES
                </h1>
                <p class="text-blue-200 text-sm mt-1 flex items-center gap-2">
                    <i class="fi fi-sr-book"></i>
                    {{ $curso->nombre }} — Paralelo {{ $curso->paralelo }}
                    <span class="bg-white/20 px-2 py-0.5 rounded text-xs flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        Gestión {{ $curso->gestion }}
                    </span>
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-lg text-sm text-blue-100 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    {{ Auth::user()->nombre }}
                </span>
                <a href="{{ route('docente.cursos.seleccionar') }}" 
                   class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition text-sm font-medium flex items-center gap-2">
                    <i class="fi fi-sr-arrow-left"></i>
                    Cambiar Curso
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-check-circle"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-cross-circle"></i>
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $estudiantes->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-users text-blue-700 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Con Faltas</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ $estudiantes->filter(fn($e) => ($e['total_faltas'] ?? 0) > 0)->count() }}
                    </p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-triangle-warning text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Faltas Graves</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $estudiantes->filter(fn($e) => ($e['faltas_graves'] ?? 0) > 0)->count() }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-circle text-red-600 dark:text-red-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Sin Faltas</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $estudiantes->filter(fn($e) => ($e['total_faltas'] ?? 0) == 0)->count() }}
                    </p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-check-circle text-emerald-600 dark:text-emerald-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm text-gray-600 dark:text-gray-400 font-medium flex items-center gap-1">
                <i class="fi fi-sr-filter"></i>
                Filtros:
            </span>
            <a href="#" class="px-3 py-1 bg-blue-700 text-white rounded-full text-sm hover:bg-blue-800 transition flex items-center gap-1">
                <i class="fi fi-sr-list"></i>
                Todos
            </a>
            <a href="#" class="px-3 py-1 bg-yellow-500 text-white rounded-full text-sm hover:bg-yellow-600 transition flex items-center gap-1">
                <i class="fi fi-sr-triangle-warning"></i>
                Con faltas
            </a>
            <a href="#" class="px-3 py-1 bg-emerald-600 text-white rounded-full text-sm hover:bg-emerald-700 transition flex items-center gap-1">
                <i class="fi fi-sr-check-circle"></i>
                Sin faltas
            </a>
            <div class="flex-1"></div>
            <div class="relative">
                <input type="text" placeholder="Buscar estudiante..." 
                       class="px-4 py-1.5 pl-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-56">
                <i class="fi fi-sr-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Botón para subir foto -->
    <div class="mb-4 flex justify-end">
        <button onclick="document.getElementById('modalFoto').style.display='flex'" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
            <i class="fi fi-sr-camera"></i>
            Subir Foto de Estudiante
        </button>
    </div>

    <!-- Modal para subir foto -->
    <div id="modalFoto" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; padding:30px; border-radius:16px; max-width:500px; width:90%;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fi fi-sr-camera"></i>
                    Subir Foto de Estudiante
                </h3>
                <button onclick="document.getElementById('modalFoto').style.display='none'" 
                        class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            
            <form action="{{ route('fotos-estudiantes.upload', ['estudiante' => $estudiantes->first()['id'] ?? 0]) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="formFoto">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Estudiante</label>
                    <select name="estudiante_id" id="estudianteSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @foreach($estudiantes as $est)
                            <option value="{{ $est['id'] ?? 0 }}">{{ $est['nombre'] ?? 'Sin nombre' }} (ID: {{ $est['id'] ?? 0 }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Foto</label>
                    <input type="file" name="foto" accept="image/*" class="w-full p-2 border border-gray-300 rounded-lg" required>
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF. Máximo 2MB</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalFoto').style.display='none'" 
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">Cancelar</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition">Subir Foto</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('estudianteSelect');
        if (select) {
            select.addEventListener('change', function() {
                const form = document.getElementById('formFoto');
                const baseUrl = '{{ route("fotos-estudiantes.upload", ["estudiante" => 0]) }}';
                form.action = baseUrl.replace('/0', '/' + this.value);
            });
        }
    });
    </script>

    <!-- Tabla de estudiantes -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold">Nº</th>
                        <th class="text-left py-3 px-4 font-semibold flex items-center gap-1">
                            <i class="fi fi-sr-user"></i>
                            Estudiante
                        </th>
                        <th class="text-center py-3 px-4 font-semibold">
                            <i class="fi fi-sr-chart-histogram"></i>
                            Faltas
                        </th>
                        <th class="text-left py-3 px-4 font-semibold">
                            <i class="fi fi-sr-calendar"></i>
                            Última Falta
                        </th>
                        <th class="text-center py-3 px-4 font-semibold">
                            <i class="fi fi-sr-settings"></i>
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estudiantes as $index => $est)
                    <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700/40 transition {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/50' : '' }}">
                        <td class="py-3 px-4 font-bold text-blue-600 dark:text-blue-400">{{ $index + 1 }}</td>
                        
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                @php
                                    $idEstudiante = $est['id'] ?? 0;
                                    $nombreEstudiante = $est['nombre'] ?? 'Usuario';
                                    $fotoUrl = $est['foto_url'] ?? null;
                                @endphp

                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" 
                                         alt="Foto de {{ $nombreEstudiante }}" 
                                         class="h-10 w-10 rounded-full object-cover border-2 border-blue-300 shadow-sm"
                                         onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-foto').style.display='flex';">
                                    <div class="fallback-foto h-10 w-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-sm" style="display:none;">
                                        {{ strtoupper(substr($nombreEstudiante, 0, 1)) }}
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                        {{ strtoupper(substr($nombreEstudiante, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div>
                                    <div class="font-medium text-gray-800 dark:text-gray-200">{{ $nombreEstudiante }}</div>
                                    <div class="text-xs text-gray-400">ID: {{ $idEstudiante }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                {{ ($est['total_faltas'] ?? 0) >= 3 ? 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 border border-red-300 dark:border-red-700' : '' }}
                                {{ ($est['total_faltas'] ?? 0) > 0 && ($est['total_faltas'] ?? 0) < 3 ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 border border-yellow-300 dark:border-yellow-700' : '' }}
                                {{ ($est['total_faltas'] ?? 0) == 0 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700' : '' }}">
                                {{ $est['total_faltas'] ?? 0 }} faltas
                            </span>
                            @if(($est['faltas_graves'] ?? 0) > 0)
                                <br><span class="text-xs text-red-500 font-medium flex items-center gap-1 justify-center mt-1">
                                    <i class="fi fi-sr-triangle-warning"></i>
                                    {{ $est['faltas_graves'] }} grave
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if(($est['ultima_falta_fecha'] ?? 'Sin faltas') !== 'Sin faltas')
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $est['ultima_falta_fecha'] }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $est['ultima_falta_desc'] ?? '' }}</div>
                                <div class="text-xs text-gray-400">{{ $est['ultima_falta_materia'] ?? '' }}</div>
                            @else
                                <span class="text-gray-400 text-sm flex items-center gap-1">
                                    <i class="fi fi-sr-check-circle text-emerald-500"></i>
                                    Sin faltas
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap justify-center gap-2">
                                <!-- Botón Ver Kardex -->
                                <a href="{{ route('docente.estudiante.kardex', $est['id'] ?? 0) }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                    <i class="fi fi-sr-chart-histogram"></i>
                                    Ver Kardex
                                </a>
                                
                                <!-- Botón Generar Citación -->
                                <a href="{{ route('docente.citacion.create', $est['id'] ?? 0) }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-sm font-semibold rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                    <i class="fi fi-sr-document"></i>
                                    Generar Citación
                                </a>
                                
                                <!-- Botón Registrar Incidencia -->
                                <a href="{{ route('docente.incidencia.create', ['estudiante' => $est['id'] ?? 0]) }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-700 hover:bg-red-800 text-white text-sm font-semibold rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                    <i class="fi fi-sr-triangle-warning"></i>
                                    Registrar Incidencia
                                </a>
                                
                                <!-- Botón Enviar WhatsApp -->
                                <a href="{{ route('docente.reporte.whatsapp', $est['id'] ?? 0) }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                    <i class="fi fi-sr-comment-alt"></i>
                                    Enviar WhatsApp
                                </a>
                                
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fi fi-sr-inbox text-4xl mb-2 text-gray-400"></i>
                                <p>No hay estudiantes en este curso</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($estudiantes, 'links'))
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                {{ $estudiantes->links() }}
            </div>
        @endif
    </div>

    <!-- Botón volver -->
    <div class="mt-4">
        <a href="{{ route('docente.dashboard') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
            <i class="fi fi-sr-arrow-left"></i>
            Volver al Dashboard
        </a>
    </div>

    <!-- Leyenda de acciones -->
    <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="text-gray-600 dark:text-gray-400 font-medium flex items-center gap-1">
                <i class="fi fi-sr-info"></i>
                Acciones:
            </span>
            
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 bg-blue-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Ver Kardex</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 bg-purple-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Generar Citación</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 bg-red-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Registrar Incidencia</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 bg-emerald-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Enviar WhatsApp</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 bg-orange-600 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Registrar Falta</span>
            </div>
        </div>
    </div>

</div>

<!-- ============================================ -->
<!-- MODAL DE CURSO SELECCIONADO -->
<!-- ============================================ -->
@if(session('curso_seleccionado'))
    <div id="modalCursoConfirmacion" class="fixed inset-0 bg-blue-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 max-w-md w-full border-t-8 border-yellow-400 relative overflow-hidden animate-fadeIn">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-300/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-yellow-300/20 rounded-full blur-3xl"></div>
            
            <div class="relative text-center">
                <!-- Icono -->
                <div class="mx-auto h-24 w-24 rounded-full bg-gradient-to-br from-blue-700 to-blue-800 flex items-center justify-center shadow-xl mb-6 ring-4 ring-yellow-300">
                    <i class="fi fi-sr-check-circle text-5xl text-white"></i>
                </div>

                <h2 class="text-2xl font-black text-blue-900 dark:text-blue-200 mb-2">Curso Seleccionado</h2>
                
                <!-- Curso seleccionado -->
                <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 mb-4 border border-blue-200 dark:border-blue-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Curso seleccionado:</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ session('curso_seleccionado') }}</p>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Ahora puedes gestionar este curso en todas las opciones del sistema.
                </p>

                <button onclick="document.getElementById('modalCursoConfirmacion').style.display='none'" 
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-2xl transition-all">
                    <i class="fi fi-sr-check"></i>
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9) translateY(-20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalCursoConfirmacion');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endif

@endsection