@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
<div>

    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-users"></i>
                    GESTIÓN DE USUARIOS
                </h1>
                <p class="text-blue-200 text-sm mt-1">Lista de todos los usuarios registrados en el sistema</p>
            </div>
            <a href="{{ route('admin.usuarios.create') }}" class="bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition font-semibold text-sm flex items-center gap-2">
                <i class="fi fi-sr-add"></i>
                Nuevo Usuario
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-4 rounded-lg mb-4 flex items-center gap-2 shadow-sm">
            <i class="fi fi-sr-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md mb-6 border border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                <select name="rol" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol }}" {{ request('rol') == $rol ? 'selected' : '' }}>
                            {{ ucfirst($rol) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
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
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre, email, CI..." 
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 w-full transition flex items-center justify-center gap-2">
                    <i class="fi fi-sr-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fi fi-sr-cross"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- CARPETAS DE USUARIOS POR ROL              -->
    <!-- ========================================== -->
    <div class="space-y-6">

        <!-- CARPETA: ADMINISTRADORES -->
        @php
            $admins = $usuarios->where('rol', 'admin');
        @endphp
        @if($admins->count() > 0 || !request('rol'))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-red-700 to-red-800 px-6 py-3 flex items-center justify-between cursor-pointer" onclick="toggleCarpeta('carpeta-admin')">
                <div class="flex items-center space-x-3">
                    <i class="fi fi-sr-folder text-white text-2xl"></i>
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fi fi-sr-user-shield"></i>
                        Administradores
                    </h3>
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-bold">{{ $admins->count() }}</span>
                </div>
                <span id="icon-admin" class="text-white text-xl">▼</span>
            </div>
            <div id="carpeta-admin" class="p-4">
                @if($admins->count() > 0)
                    @include('admin.usuarios.partials.tabla-usuarios', ['usuarios' => $admins])
                @else
                    <p class="text-gray-400 text-sm italic text-center py-4">No hay administradores registrados.</p>
                @endif
            </div>
        </div>
        @endif

        <!-- CARPETA: DOCENTES -->
        @php
            $docentes = $usuarios->where('rol', 'docente');
        @endphp
        @if($docentes->count() > 0 || !request('rol'))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 px-6 py-3 flex items-center justify-between cursor-pointer" onclick="toggleCarpeta('carpeta-docente')">
                <div class="flex items-center space-x-3">
                    <i class="fi fi-sr-folder text-white text-2xl"></i>
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fi fi-sr-chalkboard-user"></i>
                        Docentes
                    </h3>
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-bold">{{ $docentes->count() }}</span>
                </div>
                <span id="icon-docente" class="text-white text-xl">▼</span>
            </div>
            <div id="carpeta-docente" class="p-4">
                <!-- FILTRO DE CURSOS PARA DOCENTES -->
                <div class="mb-3 flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                        <i class="fi fi-sr-book"></i>
                        Filtrar por curso:
                    </span>
                    <select id="filtro-curso-docente" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los cursos</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }} {{ $curso->paralelo }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Mostrando: <span id="total-docentes">{{ $docentes->count() }}</span></span>
                </div>
                @if($docentes->count() > 0)
                    @include('admin.usuarios.partials.tabla-usuarios', ['usuarios' => $docentes])
                @else
                    <p class="text-gray-400 text-sm italic text-center py-4">No hay docentes registrados.</p>
                @endif
            </div>
        </div>
        @endif

        <!-- CARPETA: PADRES DE FAMILIA -->
        @php
            $padres = $usuarios->where('rol', 'padre');
        @endphp
        @if($padres->count() > 0 || !request('rol'))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-700 to-emerald-800 px-6 py-3 flex items-center justify-between cursor-pointer" onclick="toggleCarpeta('carpeta-padre')">
                <div class="flex items-center space-x-3">
                    <i class="fi fi-sr-folder text-white text-2xl"></i>
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fi fi-sr-family"></i>
                        Padres de Familia
                    </h3>
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-bold">{{ $padres->count() }}</span>
                </div>
                <span id="icon-padre" class="text-white text-xl">▼</span>
            </div>
            <div id="carpeta-padre" class="p-4">
                <!-- FILTRO DE CURSOS PARA PADRES -->
                <div class="mb-3 flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                        <i class="fi fi-sr-book"></i>
                        Filtrar por curso:
                    </span>
                    <select id="filtro-curso-padre" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Todos los cursos</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }} {{ $curso->paralelo }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Mostrando: <span id="total-padres">{{ $padres->count() }}</span></span>
                </div>
                @if($padres->count() > 0)
                    @include('admin.usuarios.partials.tabla-usuarios', ['usuarios' => $padres])
                @else
                    <p class="text-gray-400 text-sm italic text-center py-4">No hay padres registrados.</p>
                @endif
            </div>
        </div>
        @endif

        <!-- CARPETA: ESTUDIANTES -->
        @php
            $estudiantes = $usuarios->where('rol', 'estudiante');
        @endphp
        @if($estudiantes->count() > 0 || !request('rol'))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-3 flex items-center justify-between cursor-pointer" onclick="toggleCarpeta('carpeta-estudiante')">
                <div class="flex items-center space-x-3">
                    <i class="fi fi-sr-folder text-white text-2xl"></i>
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fi fi-sr-graduation-cap"></i>
                        Estudiantes
                    </h3>
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-bold">{{ $estudiantes->count() }}</span>
                </div>
                <span id="icon-estudiante" class="text-white text-xl">▼</span>
            </div>
            <div id="carpeta-estudiante" class="p-4">
                <!-- FILTRO DE CURSOS PARA ESTUDIANTES -->
                <div class="mb-3 flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                        <i class="fi fi-sr-book"></i>
                        Filtrar por curso:
                    </span>
                    <select id="filtro-curso-estudiante" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="">Todos los cursos</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }} {{ $curso->paralelo }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Mostrando: <span id="total-estudiantes">{{ $estudiantes->count() }}</span></span>
                </div>
                @if($estudiantes->count() > 0)
                    @include('admin.usuarios.partials.tabla-usuarios', ['usuarios' => $estudiantes])
                @else
                    <p class="text-gray-400 text-sm italic text-center py-4">No hay estudiantes registrados.</p>
                @endif
            </div>
        </div>
        @endif

        <!-- CARPETA: REGENTES -->
        @php
            $regentes = $usuarios->where('rol', 'regente');
        @endphp
        @if($regentes->count() > 0 || !request('rol'))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-700 to-purple-800 px-6 py-3 flex items-center justify-between cursor-pointer" onclick="toggleCarpeta('carpeta-regente')">
                <div class="flex items-center space-x-3">
                    <i class="fi fi-sr-folder text-white text-2xl"></i>
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fi fi-sr-user-check"></i>
                        Regentes
                    </h3>
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-bold">{{ $regentes->count() }}</span>
                </div>
                <span id="icon-regente" class="text-white text-xl">▼</span>
            </div>
            <div id="carpeta-regente" class="p-4">
                @if($regentes->count() > 0)
                    @include('admin.usuarios.partials.tabla-usuarios', ['usuarios' => $regentes])
                @else
                    <p class="text-gray-400 text-sm italic text-center py-4">No hay regentes registrados.</p>
                @endif
            </div>
        </div>
        @endif

    </div>

    <!-- Leyenda -->
    <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="text-gray-600 dark:text-gray-400 font-medium flex items-center gap-1">
                <i class="fi fi-sr-info"></i>
                Leyenda:
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-red-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Administradores</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-blue-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Docentes</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-emerald-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Padres</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-yellow-600 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Estudiantes</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-purple-700 rounded"></span>
                <span class="text-gray-600 dark:text-gray-400">Regentes</span>
            </span>
            <span class="flex items-center space-x-1 ml-4">
                <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Activo</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-gray-400 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Inactivo</span>
            </span>
            <span class="flex items-center space-x-1">
                <span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span>
                <span class="text-gray-600 dark:text-gray-400">Suspendido</span>
            </span>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- JAVASCRIPT                                -->
<!-- ========================================== -->
<script>
    function toggleCarpeta(id) {
        const carpeta = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.replace('carpeta-', ''));
        if (carpeta.style.display === 'none' || carpeta.style.display === '') {
            carpeta.style.display = 'block';
            icon.textContent = '▼';
        } else {
            carpeta.style.display = 'none';
            icon.textContent = '▶';
        }
    }

    function filtrarPorCurso(tablaId, cursoId, contadorId) {
        const tabla = document.getElementById(tablaId);
        if (!tabla) return;
        
        const filas = tabla.querySelectorAll('tbody tr');
        let total = 0;
        
        filas.forEach(fila => {
            const cursoFila = fila.dataset.curso || '';
            const cursosFila = cursoFila.split(',').map(id => id.trim());
            
            if (cursoId === '') {
                fila.style.display = '';
                total++;
            } else {
                if (cursosFila.includes(cursoId)) {
                    fila.style.display = '';
                    total++;
                } else {
                    fila.style.display = 'none';
                }
            }
        });
        
        const contador = document.getElementById(contadorId);
        if (contador) {
            contador.textContent = total;
        }
        
        if (total === 0) {
            let emptyMsg = document.getElementById('empty-msg-' + tablaId);
            if (!emptyMsg) {
                emptyMsg = document.createElement('tr');
                emptyMsg.id = 'empty-msg-' + tablaId;
                emptyMsg.innerHTML = `
                    <td colspan="7" class="py-4 text-center text-gray-400 text-sm">
                        No hay usuarios en este curso.
                    </td>
                `;
                const tbody = tabla.querySelector('tbody');
                if (tbody) {
                    tbody.appendChild(emptyMsg);
                }
            }
            emptyMsg.style.display = '';
        } else {
            const emptyMsg = document.getElementById('empty-msg-' + tablaId);
            if (emptyMsg) {
                emptyMsg.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const carpetas = document.querySelectorAll('[id^="carpeta-"]');
        carpetas.forEach(c => {
            c.style.display = 'block';
        });

        const filtroDocente = document.getElementById('filtro-curso-docente');
        if (filtroDocente) {
            filtroDocente.addEventListener('change', function() {
                filtrarPorCurso('tabla-docentes', this.value, 'total-docentes');
            });
            if (filtroDocente.value !== '') {
                filtrarPorCurso('tabla-docentes', filtroDocente.value, 'total-docentes');
            }
        }

        const filtroPadre = document.getElementById('filtro-curso-padre');
        if (filtroPadre) {
            filtroPadre.addEventListener('change', function() {
                filtrarPorCurso('tabla-padres', this.value, 'total-padres');
            });
            if (filtroPadre.value !== '') {
                filtrarPorCurso('tabla-padres', filtroPadre.value, 'total-padres');
            }
        }

        const filtroEstudiante = document.getElementById('filtro-curso-estudiante');
        if (filtroEstudiante) {
            filtroEstudiante.addEventListener('change', function() {
                filtrarPorCurso('tabla-estudiantes', this.value, 'total-estudiantes');
            });
            if (filtroEstudiante.value !== '') {
                filtrarPorCurso('tabla-estudiantes', filtroEstudiante.value, 'total-estudiantes');
            }
        }
    });
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEliminacion(id, nombre, email, rol) {
        Swal.fire({
            title: '¿Estás seguro?',
            html: `
                <div style="text-align: left;">
                    <p><strong>Usuario:</strong> ${nombre}</p>
                    <p><strong>Email:</strong> ${email}</p>
                    <p><strong>Rol:</strong> ${rol.charAt(0).toUpperCase() + rol.slice(1)}</p>
                    <hr style="margin: 10px 0;">
                    <p style="color: red;">Esta acción no se puede deshacer.</p>
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
                form.action = `/admin/usuarios/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection