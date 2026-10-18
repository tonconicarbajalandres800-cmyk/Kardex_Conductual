@extends('layouts.docente')

@section('title', 'Registrar Incidencia')

@section('content')
<div class="max-w-[1700px] mx-auto px-4">

    <!-- ENCABEZADO -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-3xl shadow-2xl p-8 mb-8 text-white overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-5">
                <div class="h-20 w-20 rounded-full overflow-hidden border-4 border-yellow-400 bg-white shadow-2xl ring-4 ring-white/20 flex-shrink-0">
                    <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo" class="h-full w-full object-cover">
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight drop-shadow-lg flex items-center gap-3">
                        <i class="fi fi-sr-triangle-warning"></i>
                        REGISTRAR INCIDENCIA
                    </h1>
                    <p class="text-blue-100 text-base font-medium mt-1">Registro individual o masivo de incidencias</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                            <i class="fi fi-sr-school"></i>
                            U.E. GRAL. JOSÉ DE SAN MARTÍN
                        </span>
                        <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                            <i class="fi fi-sr-calendar"></i>
                            Gestión 2026
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('docente.dashboard') }}" class="bg-white/10 border border-white/30 text-white px-6 py-3 rounded-xl hover:bg-white/20 transition font-bold shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- SOLO MENSAJES DE ERROR -->
    @if(session('error'))
        <div class="bg-red-700 text-white p-5 rounded-2xl mb-5 shadow-xl flex items-center font-bold text-lg">
            <i class="fi fi-sr-cross-circle text-3xl mr-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- CONTENIDO PRINCIPAL -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-7">

            <!-- COLUMNA 1: CURSO + ESTUDIANTES (5/12) -->
            <div class="xl:col-span-5 space-y-6">

                <!-- SELECCIÓN DE CURSO (NUEVO DISEÑO) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border-t-4 border-blue-700 relative z-20">
                    <label class="block text-sm font-black text-blue-900 dark:text-blue-200 mb-3 tracking-wide flex items-center gap-2">
                        <i class="fi fi-sr-book"></i>
                        SELECCIONAR CURSO
                    </label>
                    
                    <div class="relative" id="custom-dropdown-container">
                        <!-- Botón que abre el menú -->
                        <button type="button" onclick="toggleCustomDropdown()" 
                                class="flex items-center justify-between w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-4 bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100 hover:bg-blue-100 dark:hover:bg-gray-600 transition shadow-inner">
                            <span class="flex items-center gap-2 text-base">
                                <i class="fi fi-sr-graduation-cap text-blue-700 dark:text-blue-400"></i>
                                {{ $cursoSeleccionado->nombre ?? 'Seleccione' }} {{ $cursoSeleccionado->paralelo ?? '' }} - {{ $cursoSeleccionado->gestion ?? '' }}
                            </span>
                            <i class="fi fi-sr-angle-down text-gray-500 transition-transform duration-300" id="dropdown-icon"></i>
                        </button>

                        <!-- Menú Desplegable Cuadrado -->
                        <div id="custom-dropdown-menu" class="hidden absolute left-0 mt-2 w-[110%] min-w-[400px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-2xl shadow-2xl p-4 grid grid-cols-3 gap-3 max-h-[350px] overflow-y-auto">
                            @php
                                // Diccionario para ordenar correctamente los niveles
                                $ordenNivel = [
                                    '1ro' => 1, '1er' => 1, 'Primero' => 1,
                                    '2do' => 2, 'Segundo' => 2,
                                    '3ro' => 3, 'Tercero' => 3,
                                    '4to' => 4, 'Cuarto' => 4,
                                    '5to' => 5, 'Quinto' => 5,
                                    '6to' => 6, 'Sexto' => 6
                                ];
                                
                                // Ordenamos los cursos
                                $cursosOrdenados = $cursos->sortBy(function($c) use ($ordenNivel) {
                                    $nivel = explode(' ', $c->nombre)[0] ?? '0';
                                    return ($ordenNivel[$nivel] ?? 99) . '-' . $c->paralelo;
                                });
                            @endphp

                            @foreach($cursosOrdenados as $c)
                                <a href="{{ route('docente.incidencia.create') }}?curso={{ $c->id }}"
                                   class="flex flex-col items-center justify-center p-3 rounded-xl border-2 transition-all duration-200 
                                   {{ $cursoSeleccionado && $cursoSeleccionado->id == $c->id 
                                       ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/40 shadow-md transform scale-105' 
                                       : 'border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    
                                    <span class="text-sm font-black text-center {{ $cursoSeleccionado && $cursoSeleccionado->id == $c->id ? 'text-blue-800 dark:text-blue-300' : 'text-gray-800 dark:text-gray-200' }}">
                                        {{ $c->nombre }}
                                    </span>
                                    
                                    <!-- Etiqueta del Paralelo mejorada -->
                                    <span class="text-sm font-black mt-1.5 px-3 py-0.5 rounded-full shadow-sm transition-colors
                                        {{ $cursoSeleccionado && $cursoSeleccionado->id == $c->id 
                                            ? 'bg-blue-600 text-white' 
                                            : 'bg-blue-100 text-blue-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                        {{ $c->paralelo }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Lista de estudiantes con checkboxes -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border-t-4 border-blue-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-black text-blue-900 dark:text-blue-200 flex items-center gap-2">
                            <i class="fi fi-sr-users"></i>
                            ESTUDIANTES
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-black text-white bg-blue-700 px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                <i class="fi fi-sr-users"></i>
                                {{ $estudiantes->count() }} total
                            </span>
                            <button type="button" onclick="seleccionarTodos()" 
                                    class="text-xs font-black text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/40 px-3 py-1.5 rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/60 transition flex items-center gap-1">
                                <i class="fi fi-sr-check"></i>
                                Todos
                            </button>
                            <button type="button" onclick="deseleccionarTodos()" 
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center gap-1">
                                <i class="fi fi-sr-cross"></i>
                                Ninguno
                            </button>
                        </div>
                    </div>

                    <div class="border-2 border-blue-100 dark:border-gray-700 rounded-2xl overflow-hidden shadow-inner">
                        <div class="overflow-y-auto max-h-[38rem]">
                            <table class="w-full">
                                <thead class="bg-blue-800 text-white sticky top-0 z-10">
                                    <tr>
                                        <th class="px-3 py-4 text-center text-xs font-black uppercase tracking-wider">
                                            <input type="checkbox" id="seleccionar-todos" onchange="toggleAllCheckboxes(this)" 
                                                   class="w-5 h-5 rounded border-white/50 bg-white/20 checked:bg-yellow-400 checked:border-yellow-400">
                                        </th>
                                        <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider">Estudiante</th>
                                        <th class="px-5 py-4 text-center text-xs font-black uppercase tracking-wider">Faltas</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-estudiantes" class="divide-y divide-blue-100 dark:divide-gray-700">
                                    @forelse($estudiantes as $estudiante)
                                    <tr class="hover:bg-blue-50 dark:hover:bg-gray-700/50 transition-all cursor-pointer" 
                                        onclick="toggleCheckbox(event, 'estudiante_{{ $estudiante->id }}')">
                                        <td class="px-3 py-4 text-center">
                                            <input type="checkbox" 
                                                   name="estudiantes_seleccionados[]" 
                                                   id="estudiante_{{ $estudiante->id }}" 
                                                   value="{{ $estudiante->id }}"
                                                   class="checkbox-estudiante w-5 h-5 rounded border-blue-300 text-yellow-500 focus:ring-yellow-400">
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="h-12 w-12 rounded-full overflow-hidden border-[3px] {{ ($estudiante && $estudiante->incidencias && $estudiante->incidencias->count() >= 3) ? 'border-red-500 ring-2 ring-red-200' : 'border-blue-300' }} flex-shrink-0 bg-white shadow-md">
                                                    @php
                                                        $fotoUrl = null;
                                                        if ($estudiante && $estudiante->fotos && $estudiante->fotos->count() > 0) {
                                                            $ultimaFoto = $estudiante->fotos->last();
                                                            $fotoUrl = asset($ultimaFoto->ruta);
                                                        } elseif ($estudiante && $estudiante->foto) {
                                                            $fotoUrl = asset($estudiante->foto);
                                                        }
                                                    @endphp

                                                    @if($fotoUrl)
                                                        <img src="{{ $fotoUrl }}" 
                                                             alt="{{ $estudiante->nombre ?? 'Estudiante' }}" 
                                                             class="h-full w-full object-cover">
                                                    @else
                                                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-700 text-white font-black text-lg">
                                                            {{ $estudiante ? strtoupper(substr($estudiante->nombre ?? 'U', 0, 1)) : '?' }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="font-bold text-gray-800 dark:text-gray-200 text-base">{{ $estudiante ? $estudiante->nombre . ' ' . $estudiante->apellido : 'Sin nombre' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="inline-block px-5 py-2 rounded-full text-base font-black shadow-md
                                                {{ ($estudiante && $estudiante->incidencias && $estudiante->incidencias->count() >= 3) ? 'bg-red-600 text-white' : '' }}
                                                {{ ($estudiante && $estudiante->incidencias && $estudiante->incidencias->count() > 0 && $estudiante->incidencias->count() < 3) ? 'bg-yellow-500 text-white' : '' }}
                                                {{ (!$estudiante || !$estudiante->incidencias || $estudiante->incidencias->count() == 0) ? 'bg-emerald-600 text-white' : '' }}">
                                                {{ $estudiante && $estudiante->incidencias ? $estudiante->incidencias->count() : 0 }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-10 text-center text-gray-400">
                                            <i class="fi fi-sr-inbox text-5xl block mb-3"></i>
                                            <span class="text-base font-semibold">No hay estudiantes en este curso</span>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Contador de seleccionados -->
                    <div class="mt-4 text-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex items-center justify-center gap-2">
                            <i class="fi fi-sr-check-circle text-blue-700 dark:text-blue-400"></i>
                            Estudiantes seleccionados: <span id="contador-seleccionados" class="text-blue-700 dark:text-blue-300 text-lg font-black">0</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- COLUMNA 2: FORMULARIO DE INCIDENCIA (7/12) -->
            <div class="xl:col-span-7 space-y-6">

                <!-- Formulario de incidencia masiva -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border-t-4 border-yellow-400">
                    <h3 class="text-xl font-black text-blue-900 dark:text-blue-200 mb-6 flex items-center gap-2">
                        <i class="fi fi-sr-document"></i>
                        DATOS DE LA INCIDENCIA
                        <span class="ml-3 text-sm font-normal text-gray-500">(Se aplicará a todos los seleccionados)</span>
                    </h3>

                    <form method="POST" action="{{ route('docente.incidencia.store.masiva') }}">
                        @csrf
                        <input type="hidden" name="estudiantes_ids" id="estudiantes-ids" value="">
                        <input type="hidden" name="curso_id" value="{{ $cursoSeleccionado->id ?? '' }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-black text-blue-900 dark:text-blue-200 mb-2 tracking-wide flex items-center gap-2">
                                    <i class="fi fi-sr-triangle-warning"></i>
                                    TIPO DE FALTA
                                </label>
                                <select name="tipo" id="tipo-falta" class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-3.5 text-base focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100">
                                    <option value="Atraso">Atraso</option>
                                    <option value="Falto a clases">Falto a clases</option>
                                    <option value="Falta con licencia">Falta con licencia</option>
                                    <option value="Sin uniforme">Sin uniforme</option>
                                    <option value="Indisciplina">Indisciplina</option>
                                    <option value="No hizo tarea">No hizo tarea</option>
                                    <option value="No dio examen">No dio examen</option>
                                    <option value="No trabaja en clases">No trabaja en clases</option>
                                    <option value="Tiene citación">Tiene citación</option>
                                    <option value="Tarea incompleta">Tarea incompleta</option>
                                    <option value="Otro">Otro (especificar)</option>
                                </select>
                            </div>

                            <!-- Campo para "Otro" tipo de falta -->
                            <div id="campo-otro-tipo" class="hidden">
                                <label class="block text-sm font-black text-blue-900 dark:text-blue-200 mb-2 tracking-wide flex items-center gap-2">
                                    <i class="fi fi-sr-edit"></i>
                                    ESPECIFICAR TIPO
                                </label>
                                <input type="text" 
                                       name="otro_tipo" 
                                       id="otro-tipo-input"
                                       class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-3.5 text-base focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-medium text-gray-800 dark:text-gray-100"
                                       placeholder="Escriba el tipo de falta...">
                            </div>
                        </div>

                        <!-- SECCIÓN DE MATERIA -->
                        <div class="mt-6">
                            <label class="block text-sm font-black text-blue-900 dark:text-blue-200 mb-2 tracking-wide flex items-center gap-2">
                                <i class="fi fi-sr-book"></i>
                                MATERIA
                            </label>
                            
                            @if(count($materiasDocente) == 1)
                                <div class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-3.5 text-base bg-blue-100 dark:bg-blue-900/40 text-gray-800 dark:text-gray-100 font-semibold shadow-inner">
                                    {{ $materiasDocente[0] }}
                                    <input type="hidden" name="materia" value="{{ $materiasDocente[0] }}">
                                </div>
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fi fi-sr-info"></i> Materia asignada a tu perfil
                                </p>
                            @elseif(count($materiasDocente) > 1)
                                <select name="materia" class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-3.5 text-base focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100">
                                    @foreach($materiasDocente as $materia)
                                        <option value="{{ $materia }}">{{ $materia }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fi fi-sr-info"></i> Selecciona una de tus materias asignadas
                                </p>
                            @else
                                <div class="w-full border-2 border-red-300 dark:border-red-700 rounded-xl px-5 py-4 text-base bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 font-bold flex items-center gap-3">
                                    <i class="fi fi-sr-triangle-warning text-2xl"></i>
                                    No tienes materias asignadas en tu perfil.
                                </div>
                                <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                    <i class="fi fi-sr-info"></i>
                                    Contacta al administrador para que te asigne una materia antes de registrar incidencias.
                                </p>
                            @endif
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-black text-blue-900 dark:text-blue-200 mb-2 tracking-wide flex items-center gap-2">
                                <i class="fi fi-sr-edit"></i>
                                DESCRIPCIÓN (Opcional)
                            </label>
                            <textarea name="descripcion" rows="3" 
                                      class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-5 py-3.5 text-base focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition resize-none bg-blue-50 dark:bg-gray-700 font-medium text-gray-800 dark:text-gray-100"
                                      placeholder="Describa detalladamente la incidencia..."></textarea>
                        </div>

                        <!-- Alerta de estudiantes seleccionados -->
                        <div id="alerta-seleccion" class="mt-6 p-5 bg-yellow-50 dark:bg-yellow-900/20 border-l-[6px] border-yellow-400 rounded-xl shadow-md hidden">
                            <div class="flex items-start space-x-4">
                                <i class="fi fi-sr-marker text-3xl text-yellow-600 dark:text-yellow-400"></i>
                                <div>
                                    <p class="text-yellow-800 dark:text-yellow-300 font-black text-lg">Estudiantes seleccionados: <span id="total-seleccionados">0</span></p>
                                    <p class="text-yellow-700 dark:text-yellow-400 text-sm font-semibold mt-1">La incidencia se registrará para todos los estudiantes seleccionados</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ $cursoSeleccionado ? route('docente.curso.estudiantes', $cursoSeleccionado->id) : route('docente.cursos.seleccionar') }}" 
                               class="px-7 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-black text-sm text-center shadow-md flex items-center justify-center gap-2">
                                <i class="fi fi-sr-arrow-left"></i>
                                Cancelar
                            </a>
                            <button type="submit" id="btn-registrar" 
                                    {{ count($materiasDocente) == 0 ? 'disabled' : '' }} 
                                    disabled
                                    class="px-7 py-3.5 bg-blue-800 hover:bg-blue-900 text-white rounded-xl hover:shadow-2xl hover:scale-[1.02] transition-all font-black text-sm shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fi fi-sr-disk"></i>
                                Registrar Incidencia Masiva
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Información adicional -->
                <div class="bg-blue-50 dark:bg-gray-700/50 rounded-2xl shadow-lg p-6 border border-blue-200 dark:border-gray-600">
                    <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <i class="fi fi-sr-info text-2xl text-blue-700 dark:text-blue-400"></i>
                        <div>
                            <p class="font-bold text-blue-900 dark:text-blue-200">Consejo:</p>
                            <p>Selecciona varios estudiantes con los checkboxes y registra la misma incidencia para todos a la vez.</p>
                            <p class="text-xs text-gray-400 mt-1">Los estudiantes con 3+ faltas recibirán citación automática.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Leyenda -->
    <div class="mt-7 bg-gradient-to-r from-blue-900 to-blue-800 p-5 rounded-2xl shadow-xl">
        <div class="flex flex-wrap items-center gap-6 text-sm text-white">
            <span class="font-black tracking-wide flex items-center gap-2">
                <i class="fi fi-sr-info"></i>
                LEYENDA:
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-emerald-400 rounded-full shadow"></span>
                <span class="font-semibold">0 faltas</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-yellow-400 rounded-full shadow"></span>
                <span class="font-semibold">1-2 faltas</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full">
                <span class="inline-block w-4 h-4 bg-red-400 rounded-full shadow"></span>
                <span class="font-semibold">3+ faltas → CITACIÓN</span>
            </span>
        </div>
    </div>

</div>

<!-- ============================================ -->
<!-- MODAL DE CONFIRMACIÓN CON WHATSAPP -->
<!-- ============================================ -->
@if(session('mostrar_modal') && session('modal_confirmacion'))
    @php $datos = session('modal_confirmacion'); @endphp
    <div id="modalConfirmacion" class="fixed inset-0 bg-blue-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 max-w-lg w-full border-t-8 border-emerald-500 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-300/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-300/20 rounded-full blur-3xl"></div>
            
            <div class="relative">
                <!-- Icono de éxito -->
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-emerald-600 to-emerald-500 shadow-xl">
                        <i class="fi fi-sr-check-circle text-5xl text-white"></i>
                    </div>
                </div>

                <h2 class="text-2xl font-black text-center text-gray-800 dark:text-gray-100 mb-2">Incidencia Registrada</h2>
                
                <!-- Número de estudiantes -->
                <div class="text-center my-4">
                    <span class="text-5xl font-black text-emerald-600 dark:text-emerald-400">{{ $datos['total'] }}</span>
                    <span class="text-xl font-bold text-gray-600 dark:text-gray-400 ml-2">
                        {{ $datos['total'] == 1 ? 'estudiante' : 'estudiantes' }}
                    </span>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $datos['total'] == 1 ? 'registrado' : 'registrados' }} correctamente</p>
                </div>

                <!-- Detalles de la incidencia -->
                <div class="bg-blue-50 dark:bg-gray-700 rounded-xl p-4 mb-4 border border-blue-200 dark:border-gray-600">
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-xs font-black text-blue-700 dark:text-blue-300">Tipo</p>
                            <p class="font-bold text-gray-800 dark:text-gray-100">{{ $datos['tipo'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-black text-blue-700 dark:text-blue-300">Materia</p>
                            <p class="font-bold text-gray-800 dark:text-gray-100">{{ $datos['materia'] }}</p>
                        </div>
                    </div>
                    @if($datos['descripcion'])
                        <div class="mt-2 pt-2 border-t border-blue-200 dark:border-gray-600">
                            <p class="text-xs font-black text-blue-700 dark:text-blue-300">Descripción</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $datos['descripcion'] }}</p>
                        </div>
                    @endif
                </div>

                <!-- Botón de WhatsApp -->
                <div class="mb-4">
                    <button onclick="enviarWhatsApp()" 
                            class="w-full flex items-center justify-center px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-2xl transition-all">
                        <i class="fi fi-sr-comment-alt text-2xl mr-3"></i>
                        Enviar Notificación a {{ $datos['total'] == 1 ? 'Padre' : 'Padres' }}
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-2">
                        Se enviará un mensaje de WhatsApp notificando la incidencia
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-2 justify-center border-t border-gray-200 dark:border-gray-700 pt-4">
                    <a href="{{ route('docente.incidencia.create', ['curso' => $datos['curso_id'] ?? '']) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-2xl transition-all">
                        <i class="fi fi-sr-document"></i>
                        Nueva Incidencia
                    </a>
                    <a href="{{ route('docente.curso.estudiantes', $datos['curso_id'] ?? 0) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-700 hover:bg-gray-800 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-2xl transition-all">
                        <i class="fi fi-sr-users"></i>
                        Ver Estudiantes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function enviarWhatsApp() {
            @if(isset($datos['estudiante_id']) && $datos['estudiante_id'])
                const url = '{{ route("docente.incidencia.enviar.whatsapp", $datos["incidencia_id"] ?? 0) }}';
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.open(data.whatsapp_url, '_blank');
                        } else {
                            alert('❌ ' + data.error);
                        }
                    })
                    .catch(error => {
                        alert('❌ Error al enviar el mensaje');
                    });
            @elseif(isset($datos['telefonos_padres']) && count($datos['telefonos_padres']) > 0)
                const telefonos = @json($datos['telefonos_padres'] ?? []);
                const tipo = @json($datos['tipo']);
                const materia = @json($datos['materia']);
                const descripcion = @json($datos['descripcion']);
                const estudiantes = @json($datos['estudiantes'] ?? []);
                
                if (telefonos.length === 0) {
                    alert('❌ No hay padres con número de teléfono registrado');
                    return;
                }

                let mensaje = `📋 *NOTIFICACIÓN DE INCIDENCIA* 📋\n\n`;
                mensaje += `🏫 U.E. GRAL. JOSÉ DE SAN MARTÍN\n\n`;
                mensaje += `Se ha registrado una incidencia en el colegio:\n\n`;
                mensaje += `⚠️ *Tipo:* ${tipo}\n`;
                mensaje += `📚 *Materia:* ${materia}\n`;
                mensaje += `📅 *Fecha:* ${new Date().toLocaleDateString('es-BO')} ${new Date().toLocaleTimeString('es-BO')}\n`;
                if (descripcion) {
                    mensaje += `\n📝 *Descripción:*\n${descripcion}\n`;
                }
                mensaje += `\n---\n`;
                mensaje += `👥 *Estudiantes afectados:*\n`;
                estudiantes.forEach(nombre => {
                    mensaje += `• ${nombre}\n`;
                });
                mensaje += `\n*Por favor, tomar las medidas correspondientes.*`;

                const telefono = telefonos[0];
                const url = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
                window.open(url, '_blank');
                
                if (telefonos.length > 1) {
                    setTimeout(() => {
                        alert(`✅ Mensaje enviado a ${telefonos.length} padres de familia.`);
                    }, 1000);
                }
            @else
                alert('❌ No hay número de teléfono registrado para enviar el mensaje');
            @endif
        }
    </script>
@endif

<!-- ============================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================ -->
<script>
    // ============================================
    // FUNCIONES PARA EL NUEVO DROPDOWN PERSONALIZADO
    // ============================================
    function toggleCustomDropdown() {
        const menu = document.getElementById('custom-dropdown-menu');
        const icon = document.getElementById('dropdown-icon');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            menu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Cerrar el menú si se hace clic fuera de él
    window.addEventListener('click', function(e) {
        const container = document.getElementById('custom-dropdown-container');
        const menu = document.getElementById('custom-dropdown-menu');
        const icon = document.getElementById('dropdown-icon');
        
        if (container && !container.contains(e.target) && menu && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Contador de seleccionados
        const checkboxes = document.querySelectorAll('.checkbox-estudiante');
        const contador = document.getElementById('contador-seleccionados');
        const alertaSeleccion = document.getElementById('alerta-seleccion');
        const totalSeleccionados = document.getElementById('total-seleccionados');
        const btnRegistrar = document.getElementById('btn-registrar');
        const inputEstudiantes = document.getElementById('estudiantes-ids');

        function actualizarContador() {
            const seleccionados = document.querySelectorAll('.checkbox-estudiante:checked');
            const count = seleccionados.length;
            contador.textContent = count;
            
            if (count > 0) {
                alertaSeleccion.classList.remove('hidden');
                totalSeleccionados.textContent = count;
                
                // Solo habilitar el botón si el docente tiene materias
                @if(count($materiasDocente) > 0)
                    btnRegistrar.disabled = false;
                @else
                    btnRegistrar.disabled = true;
                @endif
                
                const ids = Array.from(seleccionados).map(cb => cb.value).join(',');
                inputEstudiantes.value = ids;
            } else {
                alertaSeleccion.classList.add('hidden');
                btnRegistrar.disabled = true;
                inputEstudiantes.value = '';
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function(e) {
                e.stopPropagation();
                actualizarContador();
            });
        });

        window.seleccionarTodos = function() {
            checkboxes.forEach(cb => cb.checked = true);
            actualizarContador();
        }

        window.deseleccionarTodos = function() {
            checkboxes.forEach(cb => cb.checked = false);
            actualizarContador();
        }

        window.toggleAllCheckboxes = function(checkbox) {
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            actualizarContador();
        }

        window.toggleCheckbox = function(event, id) {
            if (event.target.tagName === 'INPUT') {
                actualizarContador();
                return;
            }
            const checkbox = document.getElementById(id);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                actualizarContador();
            }
        }

        actualizarContador();

        // Si hay modal, cerrar con clic fuera
        const modal = document.getElementById('modalConfirmacion');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        }

        // Mostrar/Ocultar campo "Otro" tipo de falta
        const tipoFaltaSelect = document.getElementById('tipo-falta');
        const campoOtroTipo = document.getElementById('campo-otro-tipo');
        const otroTipoInput = document.getElementById('otro-tipo-input');

        if (tipoFaltaSelect) {
            tipoFaltaSelect.addEventListener('change', function() {
                if (this.value === 'Otro') {
                    campoOtroTipo.classList.remove('hidden');
                    otroTipoInput.required = true;
                } else {
                    campoOtroTipo.classList.add('hidden');
                    otroTipoInput.required = false;
                    otroTipoInput.value = '';
                }
            });
        }
    });
</script>

@endsection