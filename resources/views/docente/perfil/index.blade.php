@extends('layouts.docente')

@section('title', 'Mi Perfil')

@section('header', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Encabezado -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-3xl shadow-2xl p-7 text-white mb-7 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-black tracking-tight drop-shadow-lg flex items-center gap-3">
                    <i class="fi fi-sr-user"></i>
                    MI PERFIL
                </h1>
                <p class="text-blue-200 text-base font-medium mt-1">Administra tus datos personales y de acceso</p>
            </div>
            <a href="{{ route('docente.dashboard') }}" class="bg-white/10 border border-white/30 text-white px-6 py-3 rounded-xl hover:bg-white/20 transition font-bold shadow-lg backdrop-blur-sm flex items-center gap-2">
                <i class="fi fi-sr-arrow-left"></i>
                Volver al Inicio
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-700 text-white p-4 rounded-2xl mb-5 shadow-xl flex items-center gap-3 font-bold">
            <i class="fi fi-sr-check-circle text-2xl"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-700 text-white p-4 rounded-2xl mb-5 shadow-xl font-bold">
            <div class="flex items-center mb-2 gap-3">
                <i class="fi fi-sr-cross-circle text-2xl"></i>
                <span>Corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside text-sm font-semibold pl-9 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('docente.perfil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto de perfil -->
            <div class="flex flex-col items-center mb-8">
                <div class="relative">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" 
                             class="w-36 h-36 rounded-full object-cover border-4 border-yellow-400 shadow-2xl ring-4 ring-blue-100">
                    @else
                        <div class="w-36 h-36 rounded-full bg-gradient-to-br from-blue-700 to-blue-800 flex items-center justify-center text-5xl font-black text-white border-4 border-yellow-400 shadow-2xl ring-4 ring-blue-100">
                            {{ substr($user->nombre, 0, 1) }}
                        </div>
                    @endif
                    <label class="absolute bottom-1 right-1 bg-blue-800 text-white p-3 rounded-full cursor-pointer hover:scale-110 transition shadow-xl ring-4 ring-white">
                        <i class="fi fi-sr-camera text-lg"></i>
                        <input type="file" name="foto" accept="image/*" class="hidden">
                    </label>
                </div>
                <p class="text-sm text-blue-600 dark:text-blue-400 font-semibold mt-3 flex items-center gap-1">
                    <i class="fi fi-sr-info"></i>
                    Haz clic en la cámara para cambiar tu foto
                </p>
            </div>

            <!-- Datos personales -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-t-4 border-blue-700 mb-6">
                <h3 class="text-sm font-black text-blue-900 dark:text-blue-200 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-user"></i>
                    Datos Personales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" 
                               class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}" 
                               class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" 
                               class="w-full border-2 border-blue-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition bg-blue-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100" required>
                    </div>
                </div>

                <!-- Materia (solo lectura) -->
                <div class="mt-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Materia</label>
                    <input type="text" value="{{ $docente->especialidad ?? 'No asignada' }}" 
                           class="w-full border-2 border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-100 dark:bg-gray-700 font-semibold text-gray-500 dark:text-gray-400" readonly>
                    <p class="text-xs text-gray-400 font-medium mt-1.5 flex items-center gap-1">
                        <i class="fi fi-sr-lock"></i>
                        La materia solo puede ser modificada por el administrador.
                    </p>
                </div>
            </div>

            <!-- Cambiar contraseña -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-t-4 border-yellow-500">
                <h3 class="text-sm font-black text-yellow-700 dark:text-yellow-300 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="fi fi-sr-lock"></i>
                    Cambiar Contraseña
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nueva Contraseña</label>
                        <input type="password" name="password" 
                               class="w-full border-2 border-yellow-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-4 focus:ring-yellow-300 focus:border-yellow-500 transition bg-yellow-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100" 
                               placeholder="Dejar en blanco para no cambiar">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full border-2 border-yellow-200 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-4 focus:ring-yellow-300 focus:border-yellow-500 transition bg-yellow-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-100" 
                               placeholder="Confirmar nueva contraseña">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-7 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('docente.dashboard') }}" 
                   class="px-7 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-black text-sm text-center shadow-md flex items-center justify-center gap-2">
                    <i class="fi fi-sr-cross"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-7 py-3.5 bg-blue-800 hover:bg-blue-900 text-white rounded-xl hover:shadow-2xl hover:scale-[1.02] transition-all font-black text-sm shadow-xl flex items-center justify-center gap-2">
                    <i class="fi fi-sr-disk"></i>
                    Actualizar Perfil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection