@extends('layouts.regente')

@section('title', 'Tomar Asistencia')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-clipboard-list"></i>
                    TOMAR ASISTENCIA
                </h1>
                <p class="text-blue-200 text-sm mt-1">Registra la asistencia diaria de los estudiantes</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                    </span>
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. GRAL. JOSÉ DE SAN MARTÍN
                    </span>
                    @if($cursoSeleccionado)
                        <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-xs font-bold flex items-center gap-1">
                            <i class="fi fi-sr-book"></i>
                            {{ $cursoSeleccionado->nombre }} {{ $cursoSeleccionado->paralelo }}
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('regente.dashboard') }}" 
               class="bg-white/10 border border-white/30 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-semibold text-sm shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
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

    <!-- FILTROS -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-book text-blue-600 dark:text-blue-400"></i>
                    Curso
                </label>
                <select name="curso" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los cursos</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ request('curso') == $curso->id ? 'selected' : '' }}>
                            {{ $curso->nombre }} {{ $curso->paralelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-1">
                    <i class="fi fi-sr-calendar text-blue-600 dark:text-blue-400"></i>
                    Fecha
                </label>
                <input type="date" 
                       name="fecha" 
                       value="{{ $fecha }}" 
                       class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition shadow-sm flex items-center gap-2">
                    <i class="fi fi-sr-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('regente.asistencia') }}" class="ml-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 hover:underline text-sm font-medium inline-flex items-center gap-1">
                    <i class="fi fi-sr-cross"></i>
                    Limpiar
                </a>
            </div>
            <div class="ml-auto text-sm text-gray-500 dark:text-gray-400">
                Total estudiantes: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $estudiantes->count() }}</span>
            </div>
        </form>
    </div>

    <!-- FORMULARIO GLOBAL -->
    <form method="POST" 
          action="{{ route('regente.asistencia.registrar') }}" 
          id="form-asistencia-global">

        @csrf
        <input type="hidden" name="fecha" value="{{ $fecha }}">
        <input type="hidden" name="curso_id" value="{{ $cursoSeleccionado->id ?? '' }}">
        <input type="hidden" name="curso_filtro" value="{{ $cursoSeleccionado->id ?? '' }}">

        <!-- TABLA DE ESTUDIANTES -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
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
                            <th class="text-center py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                                <i class="fi fi-sr-chart-histogram"></i> Estado
                            </th>
                            <th class="text-center py-3 px-4 font-semibold text-xs uppercase tracking-wider">
                                <i class="fi fi-sr-settings"></i> Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estudiantes as $index => $estudiante)
                        @php
                            $asistencia = $asistencias[$estudiante->id] ?? null;
                            $estado = $asistencia ? $asistencia->estado : 'pendiente';

                            $color = match($estado) {
                                'presente' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
                                'ausente'  => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 border-red-300 dark:border-red-800',
                                'permiso'  => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 border-yellow-300 dark:border-yellow-800',
                                'tardanza' => 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 border-orange-300 dark:border-orange-800',
                                default    => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600'
                            };

                            $icono = match($estado) {
                                'presente' => 'fi fi-sr-check-circle',
                                'ausente'  => 'fi fi-sr-cross-circle',
                                'permiso'  => 'fi fi-sr-document-signed',
                                'tardanza' => 'fi fi-sr-clock',
                                default    => 'fi fi-sr-hourglass'
                            };
                        @endphp
                        <tr class="fila-estudiante border-t border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700/50 transition {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-700/20' : '' }}"
                            data-estudiante-id="{{ $estudiante->id }}">

                            <td class="py-3 px-4 font-bold text-blue-600 dark:text-blue-400">{{ $index + 1 }}</td>

                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                        {{ strtoupper(substr($estudiante->nombre ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                            <i class="fi fi-sr-id-badge"></i>
                                            CI: {{ $estudiante->ci }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                    {{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'Sin curso' }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-center">
                                <span class="badge-estado inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border {{ $color }}">
                                    <i class="{{ $icono }}"></i>
                                    <span class="texto-estado">{{ ucfirst($estado) }}</span>
                                </span>
                                @if($asistencia && $asistencia->motivo)
                                    <div class="texto-motivo text-xs text-gray-400 dark:text-gray-500 mt-1 flex items-center justify-center gap-1">
                                        <i class="fi fi-sr-comment"></i>
                                        {{ $asistencia->motivo }}
                                    </div>
                                @else
                                    <div class="texto-motivo hidden text-xs text-gray-400 dark:text-gray-500 mt-1 flex items-center justify-center gap-1">
                                        <i class="fi fi-sr-comment"></i>
                                        <span></span>
                                    </div>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                <div class="flex flex-wrap items-center justify-center gap-2">

                                    <input type="hidden" name="curso_id[{{ $estudiante->id }}]" value="{{ $estudiante->curso_id }}">

                                    <!-- Radios de estado -->
                                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                                        <label class="cursor-pointer" title="Presente">
                                            <input type="radio" 
                                                   name="estado[{{ $estudiante->id }}]" 
                                                   value="presente" 
                                                   class="peer sr-only estado-radio"
                                                   data-estado="presente"
                                                   {{ $estado == 'presente' ? 'checked' : '' }}>
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center text-gray-400 dark:text-gray-500 peer-checked:bg-emerald-500 peer-checked:text-white hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition">
                                                <i class="fi fi-sr-check-circle"></i>
                                            </div>
                                        </label>

                                        <label class="cursor-pointer" title="Ausente">
                                            <input type="radio" 
                                                   name="estado[{{ $estudiante->id }}]" 
                                                   value="ausente" 
                                                   class="peer sr-only estado-radio"
                                                   data-estado="ausente"
                                                   {{ $estado == 'ausente' ? 'checked' : '' }}>
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center text-gray-400 dark:text-gray-500 peer-checked:bg-red-500 peer-checked:text-white hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                                                <i class="fi fi-sr-cross-circle"></i>
                                            </div>
                                        </label>

                                        <label class="cursor-pointer" title="Permiso">
                                            <input type="radio" 
                                                   name="estado[{{ $estudiante->id }}]" 
                                                   value="permiso" 
                                                   class="peer sr-only estado-radio"
                                                   data-estado="permiso"
                                                   {{ $estado == 'permiso' ? 'checked' : '' }}>
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center text-gray-400 dark:text-gray-500 peer-checked:bg-yellow-500 peer-checked:text-white hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition">
                                                <i class="fi fi-sr-document-signed"></i>
                                            </div>
                                        </label>

                                        <label class="cursor-pointer" title="Tardanza">
                                            <input type="radio" 
                                                   name="estado[{{ $estudiante->id }}]" 
                                                   value="tardanza" 
                                                   class="peer sr-only estado-radio"
                                                   data-estado="tardanza"
                                                   {{ $estado == 'tardanza' ? 'checked' : '' }}>
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center text-gray-400 dark:text-gray-500 peer-checked:bg-orange-500 peer-checked:text-white hover:bg-orange-100 dark:hover:bg-orange-900/40 transition">
                                                <i class="fi fi-sr-clock"></i>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Motivo -->
                                    <input type="text" 
                                           name="motivo[{{ $estudiante->id }}]" 
                                           placeholder="Motivo..." 
                                           class="input-motivo border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1 text-xs w-28 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                                           value="{{ $asistencia ? $asistencia->motivo : '' }}"
                                           {{ ($asistencia && $asistencia->estado == 'permiso') ? '' : 'disabled' }}>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fi fi-sr-inbox text-6xl mb-4 text-gray-400"></i>
                                    <p class="text-lg font-semibold text-gray-600 dark:text-gray-300">No hay estudiantes en este curso.</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Verifica que los estudiantes tengan un curso asignado.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- BOTÓN GLOBAL DE GUARDAR -->
            @if($estudiantes->count() > 0)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border-t-2 border-blue-200 dark:border-blue-800 px-6 py-4 flex flex-wrap justify-between items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-full">
                            <i class="fi fi-sr-info text-blue-600 dark:text-blue-300 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-blue-800 dark:text-blue-300">
                                ¿Listo para guardar toda la asistencia?
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400">
                                Se guardarán los estados de <strong>{{ $estudiantes->count() }}</strong> estudiantes.
                            </p>
                        </div>
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-700 to-blue-600 hover:from-blue-800 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition transform hover:scale-[1.02] flex items-center gap-2">
                        <i class="fi fi-sr-disk text-lg"></i>
                        Guardar Todo
                    </button>
                </div>
            @endif
        </div>
    </form>

    <!-- ESTADÍSTICAS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-emerald-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Presentes</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $estudiantes->filter(fn($e) => isset($asistencias[$e->id]) && $asistencias[$e->id]->estado == 'presente')->count() }}
                    </p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-check-circle text-emerald-600 dark:text-emerald-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-red-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Ausentes</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $estudiantes->filter(fn($e) => isset($asistencias[$e->id]) && $asistencias[$e->id]->estado == 'ausente')->count() }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-cross-circle text-red-600 dark:text-red-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Permisos</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ $estudiantes->filter(fn($e) => isset($asistencias[$e->id]) && $asistencias[$e->id]->estado == 'permiso')->count() }}
                    </p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-document-signed text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 border-l-4 border-orange-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Tardanzas</p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                        {{ $estudiantes->filter(fn($e) => isset($asistencias[$e->id]) && $asistencias[$e->id]->estado == 'tardanza')->count() }}
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900/40 p-2 rounded-full">
                    <i class="fi fi-sr-clock text-orange-600 dark:text-orange-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('resumen_asistencia'))
    @php $r = session('resumen_asistencia'); @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '¡Asistencia Registrada!',
                html: `
                    <div style="text-align: center; padding: 10px 0;">
                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">
                            Los datos se guardaron correctamente en el sistema
                        </p>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                            <div style="background: #d1fae5; padding: 12px; border-radius: 10px;">
                                <div style="font-size: 11px; color: #065f46; font-weight: bold; text-transform: uppercase;">Presentes</div>
                                <div style="font-size: 26px; font-weight: 900; color: #059669;">{{ $r['presente'] }}</div>
                            </div>
                            <div style="background: #fee2e2; padding: 12px; border-radius: 10px;">
                                <div style="font-size: 11px; color: #991b1b; font-weight: bold; text-transform: uppercase;">Ausentes</div>
                                <div style="font-size: 26px; font-weight: 900; color: #dc2626;">{{ $r['ausente'] }}</div>
                            </div>
                            <div style="background: #fef3c7; padding: 12px; border-radius: 10px;">
                                <div style="font-size: 11px; color: #92400e; font-weight: bold; text-transform: uppercase;">Permisos</div>
                                <div style="font-size: 26px; font-weight: 900; color: #d97706;">{{ $r['permiso'] }}</div>
                            </div>
                            <div style="background: #ffedd5; padding: 12px; border-radius: 10px;">
                                <div style="font-size: 11px; color: #9a3412; font-weight: bold; text-transform: uppercase;">Tardanzas</div>
                                <div style="font-size: 26px; font-weight: 900; color: #ea580c;">{{ $r['tardanza'] }}</div>
                            </div>
                        </div>

                        <div style="background: #1e3a8a; color: white; padding: 14px; border-radius: 10px;">
                            <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; opacity: 0.8;">Total Registrados</div>
                            <div style="font-size: 32px; font-weight: 900;">{{ $r['total'] }}</div>
                        </div>

                        @if($r['omitidos'] > 0)
                            <div style="margin-top: 12px; color: #d97706; font-size: 12px;">
                                <strong>{{ $r['omitidos'] }}</strong> registro(s) omitido(s)
                            </div>
                        @endif
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#1e3a8a',
                width: 480,
                customClass: {
                    popup: 'rounded-2xl',
                }
            });
        });
    </script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.fila-estudiante').forEach(function(fila) {
        const radios = fila.querySelectorAll('.estado-radio');
        const motivo = fila.querySelector('.input-motivo');
        const badge = fila.querySelector('.badge-estado');
        const textoEstado = fila.querySelector('.texto-estado');
        const textoMotivo = fila.querySelector('.texto-motivo');

        function actualizarFila() {
            const seleccionado = fila.querySelector('.estado-radio:checked');
            const valor = seleccionado ? seleccionado.value : null;

            if (valor === 'permiso') {
                motivo.disabled = false;
            } else {
                motivo.disabled = true;
                motivo.value = '';
            }

            if (valor) {
                const colores = {
                    'presente': 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
                    'ausente':  'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 border-red-300 dark:border-red-800',
                    'permiso':  'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 border-yellow-300 dark:border-yellow-800',
                    'tardanza': 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 border-orange-300 dark:border-orange-800',
                };
                const iconos = {
                    'presente': 'fi fi-sr-check-circle',
                    'ausente':  'fi fi-sr-cross-circle',
                    'permiso':  'fi fi-sr-document-signed',
                    'tardanza': 'fi fi-sr-clock',
                };

                badge.className = 'badge-estado inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border ' + colores[valor];
                badge.querySelector('i').className = iconos[valor];
                textoEstado.textContent = valor.charAt(0).toUpperCase() + valor.slice(1);
            }
        }

        radios.forEach(function(radio) {
            radio.addEventListener('change', actualizarFila);
        });

        motivo.addEventListener('input', function() {
            if (textoMotivo && motivo.value.trim()) {
                textoMotivo.classList.remove('hidden');
                textoMotivo.querySelector('span').textContent = motivo.value;
            } else if (textoMotivo) {
                textoMotivo.classList.add('hidden');
            }
        });

        actualizarFila();
    });
});
</script>

@endsection