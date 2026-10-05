<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Regente')</title>

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
        /* Botón toggle de tema */
        .theme-toggle {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 40px;
            height: 40px;
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
<body class="bg-gray-100 dark:bg-gray-900 transition-colors">

    <!-- NAVBAR -->
    <nav class="bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-lg relative z-20">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap justify-between items-center gap-3">

            <!-- Logo / Título -->
            <div class="flex items-center gap-3">
                <div class="bg-white/10 backdrop-blur-sm p-2 rounded-lg">
                    <i class="fi fi-sr-user-shield text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black flex items-center gap-2">
                        REGENTE
                    </h1>
                    <span class="text-xs text-blue-200">Panel de Control</span>
                </div>
            </div>

            <!-- Usuario + acciones -->
            <div class="flex items-center gap-3">

                <!-- Nombre del usuario -->
                <div class="hidden sm:flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 py-2">
                    <i class="fi fi-sr-user text-sm"></i>
                    <span class="text-sm font-semibold">
                        {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}
                    </span>
                </div>

                <!-- Toggle tema -->
                <button type="button" class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema">
                    <i id="theme-icon" class="fi fi-sr-moon"></i>
                </button>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fi fi-sr-sign-out-alt"></i>
                        <span class="hidden sm:inline">Salir</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- SIDEBAR + CONTENIDO -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700 min-h-screen transition-colors hidden md:block">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-bold flex items-center gap-2">
                    <i class="fi fi-sr-menu-burger"></i>
                    Menú Principal
                </p>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('regente.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition 
                           {{ request()->routeIs('regente.dashboard') 
                               ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-semibold shadow-sm' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                            <i class="fi fi-sr-dashboard text-xl"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('regente.asistencia') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition 
                           {{ request()->routeIs('regente.asistencia*') 
                               ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-semibold shadow-sm' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                            <i class="fi fi-sr-clipboard-list text-xl"></i>
                            <span>Tomar Asistencia</span>
                        </a>
                    </li>
                </ul>

                <!-- Separador con info -->
                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg p-3">
                        <p class="text-xs text-blue-800 dark:text-blue-300 font-bold flex items-center gap-1 mb-1">
                            <i class="fi fi-sr-info"></i>
                            Regente
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400">
                            Gestiona la asistencia diaria de los estudiantes.
                        </p>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Contenido -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script>
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