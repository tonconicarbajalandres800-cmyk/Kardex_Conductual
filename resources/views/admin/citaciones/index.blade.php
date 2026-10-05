@extends('layouts.admin')

@section('title', 'Gestión de Citaciones')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-megaphone"></i>
                    GESTIÓN DE CITACIONES
                </h1>
                <p class="text-blue-200 text-sm mt-1">Administra todas las citaciones generadas por los docentes</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. GRAL. JOSÉ DE SAN MARTÍN
                    </span>
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        Gestión {{ date('Y') }}
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                    <i class="fi fi-sr-list"></i>
                    Total: <strong>{{ $citaciones->total() }}</strong>
                </span>
                <a href="{{ route('admin.citaciones.create') }}" 
                   class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm flex items-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="fi fi-sr-add"></i>
                    Nueva Citación
                </a>
                <a href="{{ route('admin.citaciones.historial') }}" 
                   class="bg-white/10 border border-white/30 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-semibold text-sm flex items-center gap-2">
                    <i class="fi fi-sr-chart-histogram"></i>
                    Historial
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
            <button onclick="this.parentElement.remove()" class="text-emerald-700 dark:text-emerald-300 hover:text-emerald-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-cross-circle"></i>
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-red-700 dark:text-red-300 hover:text-red-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- TARJETAS DE ESTADÍSTICAS                   -->
    <!-- ========================================== -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-blue-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $citaciones->total() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-list text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-yellow-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendientes ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-hourglass text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-emerald-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Confirmadas</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $confirmadas ?? 0 }}</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-check-circle text-emerald-600 dark:text-emerald-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-purple-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Reprogramadas</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $reprogramadas ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-refresh text-purple-600 dark:text-purple-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-red-500 hover:shadow-lg transition hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Canceladas</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $canceladas ?? 0 }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/40 p-3 rounded-full">
                    <i class="fi fi-sr-cross-circle text-red-600 dark:text-red-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- FILTROS                                    -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-book"></i>
                    Curso
                </label>
                <select name="curso" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ request('curso') == $curso->id ? 'selected' : '' }}>
                            {{ $curso->nombre }} {{ $curso->paralelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-chart-histogram"></i>
                    Estado
                </label>
                <select name="estado" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-search"></i>
                    Buscar
                </label>
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Estudiante o docente..." 
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-calendar"></i>
                    Fecha
                </label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" 
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 w-full transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fi fi-sr-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.citaciones.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center justify-center">
                    <i class="fi fi-sr-cross"></i>
                </a>
            </div>
        </form>
        
        <!-- Filtros activos -->
        @if(request('curso') || request('estado') || request('buscar') || request('fecha'))
            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
                <span class="text-gray-600 dark:text-gray-400 font-medium">Filtros activos:</span>
                @if(request('curso'))
                    @php
                        $cursoFiltro = \App\Models\Curso::find(request('curso'));
                    @endphp
                    <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                        <i class="fi fi-sr-book"></i>
                        {{ $cursoFiltro ? $cursoFiltro->nombre . ' ' . $cursoFiltro->paralelo : 'Curso' }}
                        <a href="{{ route('admin.citaciones.index', array_merge(request()->except('curso'), ['curso' => ''])) }}" class="ml-1 hover:opacity-70">
                            <i class="fi fi-sr-cross-small"></i>
                        </a>
                    </span>
                @endif
                @if(request('estado'))
                    <span class="bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                        <i class="fi fi-sr-marker"></i>
                        {{ ucfirst(request('estado')) }}
                        <a href="{{ route('admin.citaciones.index', array_merge(request()->except('estado'), ['estado' => ''])) }}" class="ml-1 hover:opacity-70">
                            <i class="fi fi-sr-cross-small"></i>
                        </a>
                    </span>
                @endif
                @if(request('buscar'))
                    <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                        <i class="fi fi-sr-search"></i>
                        "{{ request('buscar') }}"
                        <a href="{{ route('admin.citaciones.index', array_merge(request()->except('buscar'), ['buscar' => ''])) }}" class="ml-1 hover:opacity-70">
                            <i class="fi fi-sr-cross-small"></i>
                        </a>
                    </span>
                @endif
                @if(request('fecha'))
                    <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        {{ \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y') }}
                        <a href="{{ route('admin.citaciones.index', array_merge(request()->except('fecha'), ['fecha' => ''])) }}" class="ml-1 hover:opacity-70">
                            <i class="fi fi-sr-cross-small"></i>
                        </a>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- TABLA DE CITACIONES                        -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/40 dark:to-indigo-900/40 text-blue-800 dark:text-blue-300">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-xs uppercase tracking-wider">#</th>
                        <th class="text-left py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-user"></i> Estudiante
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-book"></i> Curso
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-chalkboard-user"></i> Docente
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-document"></i> Motivo
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-calendar"></i> Fecha Prog.
                        </th>
                        <th class="text-center py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-chart-histogram"></i> Estado
                        </th>
                        <th class="text-center py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                            <i class="fi fi-sr-settings"></i> Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citaciones as $citacion)
                    <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700/50 transition {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-700/20' : '' }}">
                        <td class="py-3 px-4 font-bold text-blue-600 dark:text-blue-400">{{ $citacion->id }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ substr($citacion->estudiante->nombre ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 dark:text-gray-200 text-sm">
                                        {{ $citacion->estudiante->nombre ?? 'N/A' }} {{ $citacion->estudiante->apellido ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-[120px]">
                                        {{ $citacion->estudiante->email ?? 'Sin email' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                {{ $citacion->estudiante->curso ? $citacion->estudiante->curso->nombre . ' ' . $citacion->estudiante->curso->paralelo : 'N/A' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $citacion->docente ? $citacion->docente->user->nombre . ' ' . $citacion->docente->user->apellidos : 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-[120px]">
                                {{ $citacion->docente ? $citacion->docente->user->email : '' }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate text-gray-700 dark:text-gray-300" title="{{ $citacion->motivo }}">
                                {{ Str::limit($citacion->motivo, 40) }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                <i class="fi fi-sr-calendar"></i>
                                {{ \Carbon\Carbon::parse($citacion->created_at)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($citacion->fecha_programada)->format('H:i') }}</div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border
                                {{ $citacion->estado === 'pendiente' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 border-yellow-300 dark:border-yellow-800' : '' }}
                                {{ $citacion->estado === 'confirmada' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800' : '' }}
                                {{ $citacion->estado === 'reprogramada' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border-blue-300 dark:border-blue-800' : '' }}
                                {{ $citacion->estado === 'justificada' ? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600' : '' }}
                                {{ $citacion->estado === 'cancelada' ? 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-300 dark:border-red-800' : '' }}">
                                @if($citacion->estado === 'pendiente') <i class="fi fi-sr-hourglass"></i>
                                @elseif($citacion->estado === 'confirmada') <i class="fi fi-sr-check-circle"></i>
                                @elseif($citacion->estado === 'reprogramada') <i class="fi fi-sr-refresh"></i>
                                @elseif($citacion->estado === 'justificada') <i class="fi fi-sr-document"></i>
                                @elseif($citacion->estado === 'cancelada') <i class="fi fi-sr-cross-circle"></i>
                                @endif
                                {{ ucfirst($citacion->estado) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap justify-center gap-1">
                                <a href="{{ route('admin.citaciones.show', $citacion->id) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-100 dark:bg-blue-900/40 hover:bg-blue-200 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-medium transition" title="Ver detalle">
                                    <i class="fi fi-sr-eye"></i>
                                    Ver
                                </a>
                                @if($citacion->estado === 'pendiente' || $citacion->estado === 'reprogramada')
                                    <a href="{{ route('admin.citaciones.edit', $citacion->id) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-100 dark:bg-emerald-900/40 hover:bg-emerald-200 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 rounded-lg text-xs font-medium transition" title="Editar">
                                        <i class="fi fi-sr-edit"></i>
                                        Editar
                                    </a>
                                @endif
                                @if($citacion->estado === 'pendiente')
                                    <a href="{{ route('admin.citaciones.enviar.whatsapp', $citacion->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-medium transition" title="Enviar por WhatsApp">
                                        <i class="fi fi-brands-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                @endif
                                <button type="button" 
                                        onclick="confirmarEliminacion({{ $citacion->id }}, '{{ $citacion->estudiante->nombre }} {{ $citacion->estudiante->apellido }}')" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-100 dark:bg-red-900/40 hover:bg-red-200 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 rounded-lg text-xs font-medium transition" title="Eliminar">
                                    <i class="fi fi-sr-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fi fi-sr-inbox text-6xl mb-4 text-gray-400"></i>
                                <p class="text-lg font-semibold text-gray-600 dark:text-gray-300">No hay citaciones registradas</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Las citaciones generadas por los docentes aparecerán aquí</p>
                                <a href="{{ route('admin.citaciones.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                                    <i class="fi fi-sr-add"></i>
                                    Crear primera citación
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando <strong>{{ $citaciones->firstItem() ?? 0 }}</strong> a <strong>{{ $citaciones->lastItem() ?? 0 }}</strong> de <strong>{{ $citaciones->total() ?? 0 }}</strong> citaciones
            </div>
            <div>
                {{ $citaciones->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- LEYENDA DE ESTADOS                         -->
    <!-- ========================================== -->
    <div class="mt-6 bg-gradient-to-r from-blue-900 to-blue-800 p-4 rounded-xl shadow-lg">
        <div class="flex flex-wrap items-center gap-6 text-sm text-white">
            <span class="font-black tracking-wide flex items-center gap-2">
                <i class="fi fi-sr-info"></i>
                ESTADOS:
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full shadow"></span>
                <span class="font-semibold">Pendiente</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-emerald-400 rounded-full shadow"></span>
                <span class="font-semibold">Confirmada</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-blue-400 rounded-full shadow"></span>
                <span class="font-semibold">Reprogramada</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-gray-400 rounded-full shadow"></span>
                <span class="font-semibold">Justificada</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-red-400 rounded-full shadow"></span>
                <span class="font-semibold">Cancelada</span>
            </span>
            <span class="border-l border-white/20 pl-4 text-blue-200 text-xs flex items-center gap-1">
                <i class="fi fi-sr-clock"></i>
                Actualizado: {{ now()->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- SWEETALERT2                                -->
<!-- ========================================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEliminacion(id, nombre) {
        Swal.fire({
            title: '¿Eliminar citación?',
            html: `
                <div style="text-align: left;">
                    <p><strong>Estudiante:</strong> ${nombre}</p>
                    <hr style="margin: 10px 0;">
                    <p style="color: red;">⚠️ Esta acción no se puede deshacer.</p>
                    <p style="color: orange; font-size: 12px; margin-top: 5px;">Se eliminarán también los registros relacionados</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/citaciones/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmarEnvio(id) {
        Swal.fire({
            title: 'Enviar citación',
            text: "La citación será enviada al estudiante y/o padre de familia",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/citaciones/${id}/enviar`;
                form.innerHTML = `
                    @csrf
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection