@extends('layouts.app')

@section('title', 'Registro de Usuario')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <div class="text-center mb-6">
            <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo del Colegio" class="mx-auto h-20 w-20">
            <h2 class="text-2xl font-bold mt-2">Registro de Usuario</h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <!-- Nombre -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Apellidos -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="apellidos">Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- CI -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ci">CI</label>
                <input type="text" name="ci" id="ci" value="{{ old('ci') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Rol -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rol">Rol</label>
                <select name="rol" id="rol"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="padre">Padre de Familia</option>
                    <option value="docente">Docente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <!-- Curso (solo para padres/estudiantes) -->
            <div class="mb-4" id="curso-field">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="curso">Curso</label>
                <input type="text" name="curso" id="curso" value="{{ old('curso') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Materia (solo para docentes) -->
            <div class="mb-4 hidden" id="materia-field">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="materia">Materia</label>
                <input type="text" name="materia" id="materia" value="{{ old('materia') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Teléfono -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono">Teléfono / Celular</label>
                <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Correo -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Contraseña</label>
                <input type="password" name="password" id="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Foto -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="foto">Foto (opcional)</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                Crear
            </button>

            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>

            <div class="mt-6 text-center text-xs text-gray-500">
                Términos y condiciones del reglamento interno
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('rol').addEventListener('change', function() {
        const curso = document.getElementById('curso-field');
        const materia = document.getElementById('materia-field');
        if (this.value === 'padre') {
            curso.classList.remove('hidden');
            materia.classList.add('hidden');
        } else if (this.value === 'docente') {
            curso.classList.add('hidden');
            materia.classList.remove('hidden');
        } else {
            curso.classList.add('hidden');
            materia.classList.add('hidden');
        }
    });
    document.getElementById('rol').dispatchEvent(new Event('change'));
</script>
@endsection