@extends('layouts.padre')

@section('title', 'Mi Perfil')

@section('header', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-[#1a1a2e] rounded-xl shadow-md overflow-hidden border-t-4 border-[#6a8fc9]">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-[#1a3a2e] border-l-4 border-[#5a8a6a] text-[#8ac89a] p-4 rounded mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#3a1a1a] border-l-4 border-[#8a3a3a] text-[#e8a0a0] p-4 rounded mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fi fi-sr-triangle-warning"></i>
                        <span class="font-semibold">Hay errores en el formulario:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('padre.perfil.update') }}">
                @csrf
                @method('PUT')

                <!-- Datos personales -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-[#8ab4f8] border-b-2 border-[#6a8fc9] pb-2 inline-flex items-center gap-2">
                        <i class="fi fi-sr-user"></i>
                        DATOS PERSONALES
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-[#c8d6e5] mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}"
                               class="w-full bg-[#16213e] border-2 border-[#4a6fa5] rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6a8fc9] focus:border-[#6a8fc9] transition-all text-[#e8edf5] placeholder-gray-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#c8d6e5] mb-1">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}"
                               class="w-full bg-[#16213e] border-2 border-[#4a6fa5] rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6a8fc9] focus:border-[#6a8fc9] transition-all text-[#e8edf5] placeholder-gray-400" required>
                    </div>
                </div>

                <!-- Datos de contacto -->
                <div class="mt-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-[#8ab4f8] border-b-2 border-[#6a8fc9] pb-2 inline-flex items-center gap-2">
                            <i class="fi fi-sr-phone"></i>
                            DATOS DE CONTACTO
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-[#c8d6e5] mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full bg-[#16213e] border-2 border-[#4a6fa5] rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6a8fc9] focus:border-[#6a8fc9] transition-all text-[#e8edf5] placeholder-gray-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#c8d6e5] mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                                   class="w-full bg-[#16213e] border-2 border-[#4a6fa5] rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6a8fc9] focus:border-[#6a8fc9] transition-all text-[#e8edf5] placeholder-gray-400" required>
                        </div>
                    </div>
                </div>

                <!-- Cambiar contraseña -->
                <div class="mt-6 pt-6 border-t-2 border-[#4a6fa5]">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-[#8ab4f8] border-b-2 border-[#6a8fc9] pb-2 inline-flex items-center gap-2">
                            <i class="fi fi-sr-lock"></i>
                            CAMBIAR CONTRASEÑA
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-[#c8d6e5] mb-1">Nueva Contraseña</label>
                            <input type="password" name="password"
                                   class="w-full bg-[#16213e] border-2 border-[#4a6fa5] rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6a8fc9] focus:border-[#6a8fc9] transition-all text-[#e8edf5] placeholder-gray-400"
                                   placeholder="Dejar en blanco para no cambiar">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#c8d6e5] mb-1">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full bg-[#16213e] border-2 border-[#4a6fa5] rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#6a8fc9] focus:border-[#6a8fc9] transition-all text-[#e8edf5] placeholder-gray-400"
                                   placeholder="Confirmar nueva contraseña">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('padre.dashboard') }}" class="px-6 py-2.5 bg-[#2a2a4a] text-[#c8d6e5] rounded-lg hover:bg-[#3a3a5a] transition-colors font-semibold flex items-center gap-2">
                        <i class="fi fi-sr-cross"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#4a6fa5] text-white rounded-lg hover:bg-[#5a7fb5] transition-colors font-semibold shadow-sm hover:shadow-md flex items-center gap-2">
                        <i class="fi fi-sr-disk"></i>
                        Actualizar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection