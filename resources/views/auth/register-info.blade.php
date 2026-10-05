@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
        <div class="text-center mb-6">
            <img src="{{ asset('images/escudo.png') }}" alt="Escudo" class="mx-auto h-20 w-20">
            <h2 class="text-2xl font-bold mt-2">Registro de Usuario</h2>
        </div>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <p class="text-yellow-700 font-medium">📋 El registro de usuarios es presencial</p>
            <p class="text-gray-600 text-sm mt-2">
                Para crear una cuenta, debe acudir a la <strong>secretaría de la Unidad Educativa</strong> 
                con sus documentos personales. El personal administrativo le proporcionará sus credenciales 
                de acceso.
            </p>
        </div>
        <div class="mt-4 text-sm text-gray-500">
            <p>🏫 Unidad Educativa Gral. José de San Martín "A"</p>
            <p>📍 Villa San Antonio Bajo - La Paz</p>
            <p>📞 2-2222222</p>
        </div>
        <div class="mt-6">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">← Volver al inicio de sesión</a>
        </div>
    </div>
</div>
@endsection