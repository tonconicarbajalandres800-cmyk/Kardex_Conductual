@extends('layouts.admin')

@section('title', 'Detalle de Citación')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📋 Detalle de Citación</h1>
            <p class="text-sm text-gray-500">Información completa de la citación #{{ $citacion->id }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.citaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                ← Volver
            </a>
            @if($citacion->estado === 'pendiente' || $citacion->estado === 'reprogramada')
                <a href="{{ route('admin.citaciones.edit', $citacion->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    ✏️ Editar
                </a>
            @endif
        </div>
    </div>

    <!-- Tarjeta de información -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">👤 Estudiante</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Nombre:</span>
                            <p class="font-medium">{{ $citacion->estudiante->nombre ?? 'N/A' }} {{ $citacion->estudiante->apellido ?? '' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="font-medium">{{ $citacion->estudiante->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Curso:</span>
                            <p class="font-medium">
                                {{ $citacion->estudiante->curso ? $citacion->estudiante->curso->nombre . ' ' . $citacion->estudiante->curso->paralelo : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">👨‍🏫 Docente</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Nombre:</span>
                            <p class="font-medium">{{ $citacion->docente->user->nombre ?? 'N/A' }} {{ $citacion->docente->user->apellidos ?? '' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="font-medium">{{ $citacion->docente->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Especialidad:</span>
                            <p class="font-medium">{{ $citacion->docente->especialidad ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fila completa - Motivo -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">📝 Motivo</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $citacion->motivo }}</p>
                    </div>
                </div>

                <!-- Fila completa - Información adicional -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">📅 Información Adicional</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Fecha Programada:</span>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($citacion->fecha_programada)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Estado:</span>
                            <p>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $citacion->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $citacion->estado === 'confirmada' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $citacion->estado === 'reprogramada' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $citacion->estado === 'justificada' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $citacion->estado === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($citacion->estado) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Creada:</span>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($citacion->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    @if($citacion->estado === 'pendiente')
        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="confirmarEnvio({{ $citacion->id }})" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                📤 Enviar Citación
            </button>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEnvio(id) {
        Swal.fire({
            title: '¿Enviar citación?',
            text: "La citación será enviada al estudiante y/o padre de familia",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '📤 Sí, enviar',
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