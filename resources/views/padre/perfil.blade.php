@extends('layouts.padre')

@section('title', 'Mi Perfil')

@section('header', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border-t-4 border-blue-800">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-4 rounded mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded mb-4">
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
                    <h3 class="text-lg font-black text-blue-900 dark:text-blue-200 border-b-2 border-gray-200 dark:border-gray-700 pb-2 inline-flex items-center gap-2">
                        <i class="fi fi-sr-user"></i>
                        DATOS PERSONALES
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <!-- oninput evita que se escriban números, pattern lo valida antes de enviar -->
                        <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                               title="Solo se permiten letras y espacios"
                               oninput="this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '')"
                               class="w-full bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-800 dark:text-gray-100 placeholder-gray-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                               title="Solo se permiten letras y espacios"
                               oninput="this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '')"
                               class="w-full bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-800 dark:text-gray-100 placeholder-gray-400" required>
                    </div>
                </div>

                <!-- Datos de contacto -->
                <div class="mt-6">
                    <div class="mb-6 mt-6">
                        <h3 class="text-lg font-black text-blue-900 dark:text-blue-200 border-b-2 border-gray-200 dark:border-gray-700 pb-2 inline-flex items-center gap-2">
                            <i class="fi fi-sr-phone"></i>
                            DATOS DE CONTACTO
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-800 dark:text-gray-100 placeholder-gray-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                                   class="w-full bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-800 dark:text-gray-100 placeholder-gray-400" required>
                        </div>
                    </div>
                </div>

                <!-- Cambiar contraseña -->
                <div class="mt-6 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                    <div class="mb-6">
                        <h3 class="text-lg font-black text-blue-900 dark:text-blue-200 border-b-2 border-gray-200 dark:border-gray-700 pb-2 inline-flex items-center gap-2">
                            <i class="fi fi-sr-lock"></i>
                            CAMBIAR CONTRASEÑA
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nueva Contraseña</label>
                            <!-- Contenedor relativo para posicionar el ojo -->
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       class="w-full bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-800 dark:text-gray-100 placeholder-gray-400"
                                       placeholder="Dejar en blanco para no cambiar">
                                <!-- Botón del ojo -->
                                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 transition-colors">
                                    <i id="icon-password" class="fi fi-sr-eye text-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Confirmar Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-800 dark:text-gray-100 placeholder-gray-400"
                                       placeholder="Confirmar nueva contraseña">
                                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 transition-colors">
                                    <i id="icon-password_confirmation" class="fi fi-sr-eye text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('padre.dashboard') }}" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-semibold flex items-center gap-2">
                        <i class="fi fi-sr-cross"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors font-semibold shadow-sm hover:shadow-md flex items-center gap-2">
                        <i class="fi fi-sr-disk"></i>
                        Actualizar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para mostrar/ocultar contraseña -->
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById('icon-' + inputId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fi-sr-eye');
            icon.classList.add('fi-sr-eye-crossed');
        } else {
            input.type = 'password';
            icon.classList.remove('fi-sr-eye-crossed');
            icon.classList.add('fi-sr-eye');
        }
    }
</script>
@endsection