@extends('layouts.docente')

@section('title', 'Subir fotos de estudiantes')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">📸 Subir fotos de estudiantes</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow divide-y">
        @foreach($estudiantes as $estudiante)
            <div class="flex items-center justify-between p-4">

                <div class="flex items-center gap-4">
                    @if($estudiante->foto)
                        <img src="{{ asset('storage/' . $estudiante->foto) }}"
                             alt="Foto de {{ $estudiante->nombre }}"
                             class="h-14 w-14 object-cover rounded-full border">
                    @else
                        <div class="h-14 w-14 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <p class="font-semibold text-gray-800">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $estudiante->foto ? 'Foto cargada' : 'Sin foto todavía' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('fotos-estudiantes.upload', $estudiante->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="flex items-center gap-2">
                    @csrf
                    <input type="file" name="foto" accept="image/*" required
                           class="text-sm">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
                        Subir
                    </button>
                </form>

            </div>
        @endforeach
    </div>
</div>
@endsection