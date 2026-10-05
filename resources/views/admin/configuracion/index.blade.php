@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')
<div>

    <!-- ENCABEZADO CON GRADIENTE -->
    <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 rounded-2xl shadow-lg p-6 text-white mb-6 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-56 h-56 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-blue-400/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black flex items-center gap-3">
                    <i class="fi fi-sr-settings"></i>
                    CONFIGURACIÓN DEL SISTEMA
                </h1>
                <p class="text-blue-200 text-sm mt-1">Personaliza el fondo, escudos, frase y año de la promoción</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. GRAL. JOSÉ DE SAN MARTÍN
                    </span>
                    <span class="bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full text-yellow-200 text-xs font-bold flex items-center gap-1">
                        <i class="fi fi-sr-calendar"></i>
                        Gestión {{ date('Y') }}
                    </span>
                </div>
            </div>
            <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                <i class="fi fi-sr-clock"></i>
                <span class="text-blue-200">Última actualización:</span>
                <span class="font-semibold">{{ now()->format('d/m/Y H:i') }}</span>
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <i class="fi fi-sr-check-circle"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 dark:text-emerald-300 hover:text-emerald-900 text-xl leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-4 shadow-sm">
            <div class="flex items-start gap-2">
                <i class="fi fi-sr-cross-circle text-xl"></i>
                <div>
                    <p class="font-semibold">Hay errores en el formulario:</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.configuracion.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">
                <!-- Grid de 2 columnas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Fondo del Login -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 transition">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fi fi-sr-picture text-blue-600 dark:text-blue-400"></i>
                            Fondo del Login
                        </label>
                        <div class="mb-3">
                            @php
                                $fondo = \App\Models\Configuracion::get('fondo_login', 'images/fondo-colegio.jpg');
                            @endphp
                            <img src="{{ asset('storage/' . $fondo) }}" 
                                 class="h-40 w-full object-cover rounded-lg border-2 border-gray-200 dark:border-gray-600" 
                                 id="vista-fondo">
                        </div>
                        <input type="file" name="fondo_login" accept="image/*" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-semibold hover:file:bg-blue-700 file:cursor-pointer"
                               onchange="previewImage(this, 'vista-fondo')">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fi fi-sr-info"></i>
                            JPG, PNG, WebP, GIF • Máximo 5MB
                        </p>
                    </div>

                    <!-- Escudo del Colegio -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 transition">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fi fi-sr-shield text-blue-600 dark:text-blue-400"></i>
                            Escudo del Colegio
                        </label>
                        <div class="mb-3 flex justify-center">
                            @php
                                $escudoColegio = \App\Models\Configuracion::get('escudo_colegio', 'images/escudo.png');
                            @endphp
                            <img src="{{ asset('storage/' . $escudoColegio) }}" 
                                 class="h-32 w-32 object-contain bg-white rounded-lg border-2 border-gray-200 dark:border-gray-600 p-2" 
                                 id="vista-escudo">
                        </div>
                        <input type="file" name="escudo_colegio" accept="image/*" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-semibold hover:file:bg-blue-700 file:cursor-pointer"
                               onchange="previewImage(this, 'vista-escudo')">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fi fi-sr-info"></i>
                            PNG, JPG, WebP • Máximo 2MB
                        </p>
                    </div>

                    <!-- Escudo de la Promoción -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 transition">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fi fi-sr-badge text-blue-600 dark:text-blue-400"></i>
                            Escudo de la Promoción
                        </label>
                        <div class="mb-3 flex justify-center">
                            @php
                                $escudoPromo = \App\Models\Configuracion::get('escudo_promocion', 'images/escudo-promocion.png');
                            @endphp
                            <img src="{{ asset('storage/' . $escudoPromo) }}" 
                                 class="h-32 w-32 object-contain bg-white rounded-lg border-2 border-gray-200 dark:border-gray-600 p-2" 
                                 id="vista-promo">
                        </div>
                        <input type="file" name="escudo_promocion" accept="image/*" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-semibold hover:file:bg-blue-700 file:cursor-pointer"
                               onchange="previewImage(this, 'vista-promo')">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fi fi-sr-info"></i>
                            PNG, JPG, WebP • Máximo 2MB
                        </p>
                    </div>

                    <!-- Frase de la Promoción -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 transition">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fi fi-sr-quote-right text-blue-600 dark:text-blue-400"></i>
                            Frase de la Promoción
                        </label>
                        @php
                            $frase = \App\Models\Configuracion::get('frase_promocion', 'Juntos hacia el futuro');
                        @endphp
                        <input type="text" name="frase_promocion" value="{{ old('frase_promocion', $frase) }}" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                               placeholder="Ej: 'Juntos hacia el futuro'">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fi fi-sr-info"></i>
                            Frase que aparecerá debajo del escudo
                        </p>
                    </div>

                    <!-- Año de la Promoción -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 transition md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                            <i class="fi fi-sr-calendar text-blue-600 dark:text-blue-400"></i>
                            Año de la Promoción
                        </label>
                        @php
                            $anio = \App\Models\Configuracion::get('anio_promocion', '2026');
                        @endphp
                        <input type="text" name="anio_promocion" value="{{ old('anio_promocion', $anio) }}" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                               placeholder="Ej: 2026">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fi fi-sr-info"></i>
                            Año que aparecerá en el login
                        </p>
                    </div>

                </div>
            </div>

            <!-- Botones -->
            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <i class="fi fi-sr-info"></i>
                    Los cambios se aplican inmediatamente
                </div>
                <div class="flex gap-3">
                    <button type="reset" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-6 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition font-semibold text-sm flex items-center gap-2">
                        <i class="fi fi-sr-refresh"></i>
                        Restablecer
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-lg transition font-semibold text-sm flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fi fi-sr-disk"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Leyenda -->
    <div class="mt-6 bg-gradient-to-r from-blue-900 to-blue-800 p-4 rounded-xl shadow-lg">
        <div class="flex flex-wrap items-center gap-6 text-sm text-white">
            <span class="font-black tracking-wide flex items-center gap-2">
                <i class="fi fi-sr-info"></i>
                RECOMENDACIONES:
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-blue-400 rounded-full shadow"></span>
                <span class="font-semibold">Fondo: 1920x1080px</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-emerald-400 rounded-full shadow"></span>
                <span class="font-semibold">Escudos: 500x500px</span>
            </span>
            <span class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full">
                <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full shadow"></span>
                <span class="font-semibold">Fondo transparente recomendado</span>
            </span>
        </div>
    </div>

</div>

<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection