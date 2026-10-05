@extends('layouts.admin')

@section('title', 'Gestión de Cursos')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-book"></i>
                    GESTIÓN DE CURSOS
                </h1>
                <p class="text-blue-200 text-sm mt-1">Lista de todos los cursos registrados en el sistema</p>
            </div>
            <a href="{{ route('admin.cursos.create') }}" 
               class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm flex items-center gap-2 shadow-lg hover:shadow-xl">
                <i class="fi fi-sr-add"></i>
                Nuevo Curso
            </a>
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

    <!-- ============================================
         ESTADÍSTICAS DESTACADAS
         ============================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        <!-- CURSO MÁS INDISCIPLINADO -->
        <div class="relative bg-gradient-to-br from-red-700 to-red-800 rounded-2xl shadow-xl p-6 text-white overflow-hidden h-full flex flex-col">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative flex flex-col h-full">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-black uppercase tracking-widest text-red-200">Curso Más Indisciplinado</span>
                    <i class="fi fi-sr-triangle-warning text-3xl opacity-60"></i>
                </div>
                @if($cursoMasIndisciplinado)
                    <h3 class="text-2xl font-black mb-1">{{ $cursoMasIndisciplinado['nombre'] }}</h3>
                    <p class="text-red-100 text-sm mb-3">{{ $cursoMasIndisciplinado['paralelo'] }}</p>
                    <div class="mt-auto">
                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-lg inline-flex">
                            <i class="fi fi-sr-bell"></i>
                            <span class="font-black text-lg">{{ $cursoMasIndisciplinado['total_incidencias'] }}</span>
                            <span class="text-xs font-semibold">incidencias</span>
                        </div>
                    </div>
                @else
                    <p class="text-red-100 italic text-sm mt-auto">No hay datos disponibles</p>
                @endif
            </div>
        </div>

        <!-- MATERIA CON MÁS FALTAS -->
        <div class="relative bg-gradient-to-br from-yellow-600 to-orange-600 rounded-2xl shadow-xl p-6 text-white overflow-hidden h-full flex flex-col">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative flex flex-col h-full">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-black uppercase tracking-widest text-yellow-100">Materia con Más Faltas</span>
                    <i class="fi fi-sr-book text-3xl opacity-60"></i>
                </div>
                @if($materiaMasFaltasData)
                    <h3 class="text-2xl font-black mb-1">{{ $materiaMasFaltasData['nombre'] }}</h3>
                    <p class="text-yellow-100 text-sm mb-3">Materia problemática</p>
                    <div class="mt-auto">
                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-lg inline-flex">
                            <i class="fi fi-sr-chart-histogram"></i>
                            <span class="font-black text-lg">{{ $materiaMasFaltasData['total'] }}</span>
                            <span class="text-xs font-semibold">faltas</span>
                        </div>
                    </div>
                @else
                    <p class="text-yellow-100 italic text-sm mt-auto">No hay datos disponibles</p>
                @endif
            </div>
        </div>

        <!-- DOCENTE DE ESA MATERIA -->
        <div class="relative bg-gradient-to-br from-blue-800 to-blue-900 rounded-2xl shadow-xl p-6 text-white overflow-hidden h-full flex flex-col">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative flex flex-col h-full">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-black uppercase tracking-widest text-blue-200">Docente de esa Materia</span>
                    <i class="fi fi-sr-chalkboard-user text-3xl opacity-60"></i>
                </div>
                @if($docenteMateria)
                    <h3 class="text-2xl font-black mb-1">{{ $docenteMateria['nombre'] }}</h3>
                    <p class="text-blue-100 text-sm mb-3">Docente responsable</p>
                    <div class="mt-auto">
                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-lg inline-flex max-w-full">
                            <i class="fi fi-sr-envelope"></i>
                            <span class="text-xs font-semibold truncate">{{ $docenteMateria['email'] }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-blue-100 italic text-sm mt-auto">No hay datos disponibles</p>
                @endif
            </div>
        </div>

    </div>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Total Cursos</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $cursos->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-book text-blue-700 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Total Estudiantes</p>
                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ $cursos->sum(fn($c) => $c->estudiantes ? $c->estudiantes->count() : 0) }}</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-graduation-cap text-emerald-700 dark:text-emerald-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Gestiones</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $gestiones->count() }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-calendar text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider">Promedio x Curso</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $cursos->count() > 0 ? round($cursos->sum(fn($c) => $c->estudiantes ? $c->estudiantes->count() : 0) / $cursos->count(), 1) : 0 }}
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-chart-histogram text-purple-600 dark:text-purple-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-calendar"></i>
                    Gestión
                </label>
                <select name="gestion" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    @foreach($gestiones as $g)
                        <option value="{{ $g }}" {{ request('gestion') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-search"></i>
                    Buscar
                </label>
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre, paralelo..." 
                       class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition shadow-sm flex items-center gap-2">
                    <i class="fi fi-sr-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.cursos.index') }}" class="ml-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 hover:underline text-sm font-medium inline-flex items-center gap-1">
                    <i class="fi fi-sr-cross"></i>
                    Limpiar
                </a>
            </div>
            <div class="flex-1"></div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Mostrando <span class="font-bold text-blue-600 dark:text-blue-400">{{ $cursos->count() }}</span> de <span class="font-bold text-blue-600 dark:text-blue-400">{{ App\Models\Curso::count() }}</span> cursos
            </div>
        </form>
    </div>

    <!-- GRID DE CURSOS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($cursos as $curso)
        @php
            $totalEstudiantes = $curso->estudiantes ? $curso->estudiantes->count() : 0;
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
            <!-- Cabecera con color -->
            <div class="h-2 bg-gradient-to-r from-blue-700 to-blue-800"></div>
            
            <div class="p-5">
                <!-- Título y paralelo -->
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i class="fi fi-sr-book text-blue-700 dark:text-blue-300 text-2xl"></i>
                            {{ $curso->nombre }}
                        </h3>
                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">Paralelo: {{ $curso->paralelo }}</p>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                        {{ $curso->gestion }}
                    </span>
                </div>

                <!-- Detalles del curso -->
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2 text-center border border-gray-100 dark:border-gray-600">
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $totalEstudiantes }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center justify-center gap-1">
                            <i class="fi fi-sr-users"></i>
                            Estudiantes
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2 text-center border border-gray-100 dark:border-gray-600">
                        <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $curso->nivel ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center justify-center gap-1">
                            <i class="fi fi-sr-chart-histogram"></i>
                            Nivel
                        </p>
                    </div>
                </div>

                <!-- Badge de estado -->
                @if($totalEstudiantes > 0)
                    <div class="mt-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/40 px-2 py-1 rounded-full">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            {{ $totalEstudiantes }} estudiantes activos
                        </span>
                    </div>
                @else
                    <div class="mt-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            Sin estudiantes
                        </span>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.cursos.edit', $curso->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white text-xs font-medium rounded-lg transition shadow-sm hover:shadow-md">
                            <i class="fi fi-sr-edit"></i>
                            Editar
                        </a>
                        <a href="{{ route('admin.cursos.estudiantes', $curso->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium rounded-lg transition shadow-sm hover:shadow-md">
                            <i class="fi fi-sr-users"></i>
                            Ver estudiantes
                        </a>
                    </div>
                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" class="inline" onsubmit="return confirmarEliminacion(event, '{{ $curso->nombre }} {{ $curso->paralelo }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-700 hover:bg-red-800 text-white text-xs font-medium rounded-lg transition shadow-sm hover:shadow-md">
                            <i class="fi fi-sr-trash"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-12 text-center">
                <i class="fi fi-sr-inbox text-6xl block mb-4 text-gray-400"></i>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No hay cursos registrados</h3>
                <p class="text-gray-400 text-sm">Haz clic en "Nuevo Curso" para agregar el primero.</p>
                <a href="{{ route('admin.cursos.create') }}" class="inline-flex items-center gap-2 mt-4 bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition">
                    <i class="fi fi-sr-add"></i>
                    Crear Curso
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- PAGINACIÓN -->
    @if(method_exists($cursos, 'links') && $cursos->hasPages())
        <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            {{ $cursos->appends(request()->query())->links() }}
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
                <span class="font-semibold">Con estudiantes</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-gray-400 rounded-full shadow"></span>
                <span class="font-semibold">Sin estudiantes</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full shadow"></span>
                <span class="font-semibold">Editar curso</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full shadow"></span>
                <span class="font-semibold">Ver estudiantes</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-red-500 rounded-full shadow"></span>
                <span class="font-semibold">Eliminar curso</span>
            </span>
        </div>
    </div>

</div>

<!-- SWEETALERT2 PARA ELIMINAR -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEliminacion(event, nombreCurso) {
        event.preventDefault();
        const form = event.target.closest('form');
        
        Swal.fire({
            title: '¿Estás seguro?',
            html: `
                <div style="text-align: left;">
                    <p><strong>Curso:</strong> ${nombreCurso}</p>
                    <hr style="margin: 10px 0;">
                    <p style="color: red; font-weight: bold;">⚠️ Esta acción no se puede deshacer.</p>
                    <p style="color: orange; font-size: 14px;">Se eliminarán todos los estudiantes asociados a este curso.</p>
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
                form.submit();
            }
        });
    }
</script>

@endsection