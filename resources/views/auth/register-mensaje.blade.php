@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md text-center">
        <div class="text-4xl mb-4">📝</div>
        <h2 class="text-2xl font-bold mb-4">Registro Presencial</h2>
        <p class="text-gray-700 mb-4">
            El registro de nuevos usuarios se realiza de manera <strong>presencial</strong> en la unidad educativa.<br>
            Acérquese a la secretaría del colegio para completar su registro.
        </p>
        <a href="{{ route('login') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Volver al inicio de sesión
        </a>
    </div>
</div>
@endsection