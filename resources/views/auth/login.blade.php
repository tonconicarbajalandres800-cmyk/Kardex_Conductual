<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - U.E. Gral. José de San Martín</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Flaticon UIcons --}}
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-brands/css/uicons-brands.css'>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        colegio: {
                            DEFAULT: '#1a3c6e',
                            claro: '#2a5a9e',
                            oscuro: '#12294d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .password-wrapper { position: relative; }
        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #6b7280;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .eye-btn:hover { color: #1a3c6e; }

        .fondo-login {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            margin: 20px;
            position: relative;
            z-index: 1;
        }

        .dark .login-card {
            background: rgba(31, 41, 55, 0.97);
        }

        @media (min-width: 768px) {
            .login-card {
                flex-direction: row;
            }
        }

        .promocion-side {
            background: linear-gradient(135deg, #1a3c6e 0%, #2a5a9e 100%);
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .promocion-side::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            background: rgba(250, 204, 21, 0.15);
            border-radius: 50%;
            filter: blur(40px);
        }

        .promocion-side::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            background: rgba(96, 165, 250, 0.2);
            border-radius: 50%;
            filter: blur(40px);
        }

        .promocion-side > * {
            position: relative;
            z-index: 1;
        }

        @media (min-width: 768px) {
            .promocion-side {
                width: 38%;
            }
        }

        .form-side {
            padding: 2.5rem 2rem;
            width: 100%;
        }

        @media (min-width: 768px) {
            .form-side {
                width: 62%;
            }
        }

        /* Botón toggle de tema */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: black;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        /* Botón de refrescar captcha */
        .captcha-refresh {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: #f5f7fa;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            color: #1a3c6e;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .captcha-refresh:hover {
            background: #1a3c6e;
            color: white;
            transform: rotate(90deg);
        }
        .dark .captcha-refresh {
            background: #374151;
            border-color: #4b5563;
            color: #93c5fd;
        }
        .dark .captcha-refresh:hover {
            background: #2563eb;
            color: white;
        }
    </style>

    {{-- Script para aplicar tema antes del render (evita flash) --}}
    <script>
        (function() {
            const tema = localStorage.getItem('tema') || 'light';
            if (tema === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body>

    @php
        use App\Models\Configuracion;
        $fondo = Configuracion::get('fondo_login', 'images/fondo-colegio.jpg');
        $escudoColegio = Configuracion::get('escudo_colegio', 'images/escudo.png');
        $escudoPromo = Configuracion::get('escudo_promocion', 'images/escudo-promocion.png');
        $frase = Configuracion::get('frase_promocion', 'Juntos hacia el futuro');
        $anio = Configuracion::get('anio_promocion', '2026');
    @endphp

    <!-- FONDO COMPLETO -->
    <div class="fondo-login" style="background-image: url('{{ asset('storage/' . $fondo) }}');">

        <!-- Botón toggle tema -->
        <button type="button" class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema">
            <i id="theme-icon" class="fi fi-sr-moon"></i>
        </button>

        <!-- RECUADRO DEL LOGIN -->
        <div class="login-card">

            <!-- LADO IZQUIERDO: PROMOCIÓN -->
            <div class="promocion-side">
                <img src="{{ asset('storage/' . $escudoPromo) }}"
                     alt="Escudo de la Promoción"
                     class="w-40 h-40 object-contain mx-auto mb-4 drop-shadow-2xl">

                <p class="text-white text-sm font-light italic mb-1 leading-relaxed">
                    "{{ $frase }}"
                </p>
                <p class="text-yellow-200 text-xs font-bold tracking-widest uppercase mt-2">
                    Promoción {{ $anio }}
                </p>

                <div class="mt-6 pt-4 border-t border-white/20 w-full">
                    <p class="text-white/70 text-xs flex items-center justify-center gap-1">
                        <i class="fi fi-sr-school"></i>
                        U.E. Gral. José de San Martín
                    </p>
                </div>
            </div>

            <!-- LADO DERECHO: FORMULARIO -->
            <div class="form-side">

                <!-- ESCUDO DEL COLEGIO -->
                <div class="flex justify-end mb-2">
                    <img src="{{ asset('storage/' . $escudoColegio) }}"
                         alt="Escudo del Colegio"
                         class="h-16 w-16 object-contain">
                </div>

                <h2 class="text-2xl font-black text-colegio dark:text-blue-300 text-center">
                    UNIDAD EDUCATIVA
                </h2>
                <p class="text-center text-gray-600 dark:text-gray-400 text-sm mb-6 font-semibold">
                    "GRAL. JOSÉ DE SAN MARTÍN A"
                </p>

                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 rounded-lg mb-4 text-sm flex items-start gap-2">
                        <i class="fi fi-sr-cross-circle mt-0.5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('status'))
                    <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-3 rounded-lg mb-4 text-sm flex items-start gap-2">
                        <i class="fi fi-sr-check-circle mt-0.5"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                            <i class="fi fi-sr-envelope text-colegio dark:text-blue-400"></i>
                            Correo Electrónico
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-colegio focus:border-colegio transition @error('email') border-red-500 @enderror"
                               placeholder="ejemplo@correo.com"
                               required autofocus>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                            <i class="fi fi-sr-lock text-colegio dark:text-blue-400"></i>
                            Contraseña
                        </label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-colegio focus:border-colegio transition @error('password') border-red-500 @enderror"
                                   placeholder="••••••••"
                                   required>
                            <button type="button" class="eye-btn" onclick="togglePassword()" id="eyeBtn" title="Mostrar/ocultar contraseña">
                                <i id="eye-icon" class="fi fi-sr-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CAPTCHA -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                            <i class="fi fi-sr-shield-check text-colegio dark:text-blue-400"></i>
                            Código de Seguridad
                        </label>
                        <div class="flex items-center gap-3">
                            <img src="{{ captcha_src('default') }}" 
                                 id="captcha-img"
                                 alt="CAPTCHA"
                                 class="rounded-xl border-2 border-gray-200 dark:border-gray-600 cursor-pointer"
                                 onclick="refrescarCaptcha()"
                                 title="Click para refrescar">
                            
                            <button type="button" 
                                    class="captcha-refresh"
                                    onclick="refrescarCaptcha()"
                                    title="Refrescar CAPTCHA">
                                <i class="fi fi-sr-refresh text-xl"></i>
                            </button>
                        </div>
                        <input type="text" 
                               name="captcha" 
                               id="captcha"
                               class="w-full border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl px-4 py-3 mt-3 focus:ring-2 focus:ring-colegio focus:border-colegio transition @error('captcha') border-red-500 @enderror"
                               placeholder="Escribe el código de la imagen"
                               required
                               autocomplete="off"
                               maxlength="6">
                        @error('captcha')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fi fi-sr-cross-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Recordarme / Olvidé contraseña -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="rounded border-gray-300 dark:border-gray-600 text-colegio focus:ring-colegio">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Recordarme</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-colegio dark:text-blue-400 hover:underline font-semibold">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botón submit -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-colegio to-colegio-claro hover:from-colegio-claro hover:to-colegio text-white py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition transform hover:scale-[1.01] flex items-center justify-center gap-2">
                        <i class="fi fi-sr-sign-in-alt"></i>
                        Iniciar Sesión
                    </button>

                    <!-- Info -->
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1">
                        <i class="fi fi-sr-info"></i>
                        Si no tienes cuenta, acude a secretaría para registrarte.
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500 border-t border-gray-200 dark:border-gray-700 pt-4 flex items-center justify-center gap-1">
                    <i class="fi fi-sr-document"></i>
                    Términos y condiciones del reglamento interno
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle mostrar/ocultar contraseña con iconos Flaticon
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fi fi-sr-eye-crossed';
            } else {
                input.type = 'password';
                icon.className = 'fi fi-sr-eye';
            }
        }

        // Refrescar el captcha (evita caché con Math.random)
        function refrescarCaptcha() {
            const img = document.getElementById('captcha-img');
            const baseSrc = "{{ captcha_src('default') }}";
            img.src = baseSrc + '?' + Math.random();
            // Limpiar el campo del captcha al refrescar
            document.getElementById('captcha').value = '';
            document.getElementById('captcha').focus();
        }

        // Toggle tema claro/oscuro
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            const temaActual = localStorage.getItem('tema') || 'light';
            const nuevoTema = temaActual === 'dark' ? 'light' : 'dark';

            if (nuevoTema === 'dark') {
                html.classList.add('dark');
                icon.className = 'fi fi-sr-sun';
            } else {
                html.classList.remove('dark');
                icon.className = 'fi fi-sr-moon';
            }

            localStorage.setItem('tema', nuevoTema);
        }

        // Ajustar icono del toggle según el tema guardado
        (function() {
            const tema = localStorage.getItem('tema') || 'light';
            const icon = document.getElementById('theme-icon');
            if (icon && tema === 'dark') {
                icon.className = 'fi fi-sr-sun';
            }
        })();
    </script>

</body>
</html>