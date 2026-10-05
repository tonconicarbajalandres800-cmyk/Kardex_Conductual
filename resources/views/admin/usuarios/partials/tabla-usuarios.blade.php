@php
    $tablaId = '';
    if(isset($usuarios) && $usuarios->count() > 0) {
        $primerUsuario = $usuarios->first();
        if($primerUsuario->rol === 'docente') {
            $tablaId = 'tabla-docentes';
        } elseif($primerUsuario->rol === 'padre') {
            $tablaId = 'tabla-padres';
        } elseif($primerUsuario->rol === 'estudiante') {
            $tablaId = 'tabla-estudiantes';
        } else {
            $tablaId = 'tabla-' . uniqid();
        }
    }
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm" id="{{ $tablaId }}">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">#</th>
                <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">FOTO</th>
                <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">NOMBRE</th>
                <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">EMAIL</th>
                <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">ASIGNACIÓN</th>
                <th class="text-left py-2 px-3 text-xs font-bold uppercase tracking-wider">ESTADO</th>
                <th class="text-center py-2 px-3 text-xs font-bold uppercase tracking-wider">ACCIONES</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($usuarios as $index => $usuario)
            @php
                // OBTENER EL ID DEL CURSO DEL USUARIO
                $cursoId = '';
                $nombreCurso = '';
                
                if ($usuario->rol === 'estudiante' && $usuario->estudiante) {
                    $curso = \App\Models\Curso::find($usuario->estudiante->curso_id);
                    if ($curso) {
                        $cursoId = $curso->id;
                        $nombreCurso = $curso->nombre . ' ' . $curso->paralelo;
                    }
                } elseif ($usuario->rol === 'docente' && $usuario->docente) {
                    $curso = \App\Models\Curso::find($usuario->docente->curso_encargado_id);
                    if ($curso) {
                        $cursoId = $curso->id;
                        $nombreCurso = $curso->nombre . ' ' . $curso->paralelo;
                    }
                } elseif ($usuario->rol === 'padre') {
                    $hijos = \App\Models\Estudiante::where('padre_id', $usuario->id)->get();
                    $cursosIds = [];
                    $cursosNombres = [];
                    foreach($hijos as $hijo) {
                        if($hijo->curso_id) {
                            $curso = \App\Models\Curso::find($hijo->curso_id);
                            if($curso) {
                                $cursosIds[] = $curso->id;
                                $cursosNombres[] = $curso->nombre . ' ' . $curso->paralelo;
                            }
                        }
                    }
                    $cursoId = implode(',', $cursosIds);
                    $nombreCurso = implode(', ', $cursosNombres);
                }
            @endphp
            <tr class="hover:bg-gray-50 transition" 
                data-curso="{{ $cursoId }}" 
                data-nombre-curso="{{ $nombreCurso }}">
                <td class="py-2 px-3 font-bold text-gray-600">{{ $index + 1 }}</td>
                
                <!-- FOTO -->
                <td class="py-2 px-3">
                    @php
                        $fotoUrl = null;
                        if ($usuario->foto) {
                            $nombreArchivo = basename($usuario->foto);
                            $fotoUrl = asset('storage/fotos/' . $nombreArchivo);
                        }
                    @endphp

                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" 
                             alt="Foto de {{ $usuario->nombre }}" 
                             class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                             onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-admin').style.display='flex';">
                        <div class="fallback-admin h-8 w-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white font-bold text-xs shadow-sm" style="display:none;">
                            {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                        </div>
                    @else
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                        </div>
                    @endif
                </td>
                
                <td class="py-2 px-3 font-medium text-gray-800">{{ $usuario->nombre }} {{ $usuario->apellidos }}</td>
                <td class="py-2 px-3 text-gray-600">{{ $usuario->email }}</td>
                <td class="py-2 px-3 text-gray-600 text-sm">
                    {{ $nombreCurso ?: 'Sin asignar' }}
                </td>
                <td class="py-2 px-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ ($usuario->estado ?? 'activo') === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                        {{ ($usuario->estado ?? 'activo') === 'inactivo' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ ($usuario->estado ?? 'activo') === 'suspendido' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($usuario->estado ?? 'Activo') }}
                    </span>
                </td>
                <td class="py-2 px-3 text-center">
                    <div class="flex justify-center space-x-2">
                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" 
                           class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-medium">
                            ✏️ Editar
                        </a>
                        @if(Auth::user()->rol === 'admin')
                            <button type="button" 
                                    onclick="confirmarEliminacion({{ $usuario->id }}, '{{ $usuario->nombre }} {{ $usuario->apellidos }}', '{{ $usuario->email }}', '{{ $usuario->rol }}')" 
                                    class="text-red-600 hover:text-red-800 hover:underline text-xs font-medium">
                                🗑️ Eliminar
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-4 text-center text-gray-400 text-sm">No hay usuarios en este rol.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>