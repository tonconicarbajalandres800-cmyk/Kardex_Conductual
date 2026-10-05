<!DOCTYPE html>
<html lang="es">
<head>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Docente - Kardex Conductual')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Colores institucionales */
        .bg-colegio { background-color: #1a3c6e; }
        .bg-colegio-claro { background-color: #2a5a9e; }
        .bg-colegio-oscuro { background-color: #0f2a4a; }
        .text-colegio { color: #1a3c6e; }
        .border-colegio { border-color: #1a3c6e; }
        .bg-colegio-gradiente { 
            background: linear-gradient(180deg, #1a3c6e 0%, #2a5a9e 100%);
        }
        .hover-colegio:hover {
            background-color: #2a5a9e;
            color: white;
        }
        .active-colegio {
            background-color: #2a5a9e;
            color: white;
        }
        .sidebar-icon {
            width: 24px;
            text-align: center;
        }
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }
        ::-webkit-scrollbar-thumb {
            background: #1a3c6e;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2a5a9e;
        }
        .dark ::-webkit-scrollbar-track {
            background: #1a202c;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #2a5a9e;
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-950 transition-colors">

    <div class="flex h-screen">

        <!-- ============================================ -->
        <!-- SIDEBAR / MENÚ LATERAL (COLORES INSTITUCIONALES) -->
        <!-- ============================================ -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 via-blue-800 to-blue-700 text-white flex flex-col shadow-2xl">
            <!-- Logo y título -->
            <div class="p-4 text-center border-b border-blue-600/30">
                <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo del Colegio" class="mx-auto h-20 w-20 rounded-full border-2 border-yellow-400 p-1">
                <h2 class="text-sm font-bold mt-2 text-white">U.E. GRAL. JOSÉ DE SAN MARTÍN</h2>
                <span class="text-xs text-blue-200">Kardex Conductual</span>
            </div>

            <!-- Información del docente -->
            <div class="p-4 border-b border-blue-600/30">
                <p class="text-xs text-blue-200">Docente:</p>
                <p class="font-semibold text-sm text-white">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</p>
            </div>

            <!-- Menú de navegación -->
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('docente.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('docente.dashboard') ? 'bg-yellow-400 text-blue-900 font-semibold' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    <i class="fi fi-sr-chart-histogram sidebar-icon text-base"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('docente.cursos.seleccionar') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('docente.cursos.*') ? 'bg-yellow-400 text-blue-900 font-semibold' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    <i class="fi fi-sr-book sidebar-icon text-base"></i>
                    <span>Mis Cursos</span>
                </a>
                <a href="{{ route('docente.incidencia.create') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('docente.incidencia.*') ? 'bg-yellow-400 text-blue-900 font-semibold' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    <i class="fi fi-sr-triangle-warning sidebar-icon text-base"></i>
                    <span>Registrar Incidencia</span>
                </a>
                <a href="{{ route('docente.estadisticas.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('docente.estadisticas.*') ? 'bg-yellow-400 text-blue-900 font-semibold' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    <i class="fi fi-sr-stats sidebar-icon text-base"></i>
                    <span>Estadísticas</span>
                </a>
                <a href="{{ route('docente.perfil') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('docente.perfil') ? 'bg-yellow-400 text-blue-900 font-semibold' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    <i class="fi fi-sr-user sidebar-icon text-base"></i>
                    <span>Mi Perfil</span>
                </a>
            </nav>

            <!-- Footer del menú -->
            <div class="p-4 border-t border-blue-600/30 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-blue-200 hover:text-yellow-300 transition inline-flex items-center gap-2">
                        <i class="fi fi-sr-sign-out-alt"></i>
                        Cerrar Sesión
                    </button>
                </form>
                <p class="text-xs text-blue-300/50 mt-2">Gestión {{ date('Y') }}</p>
            </div>
        </aside>

        <!-- ============================================ -->
        <!-- CONTENIDO PRINCIPAL -->
        <!-- ============================================ -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- Barra superior (modo oscuro) -->
            <div class="flex justify-end p-4 pb-0">
                <button id="theme-toggle" type="button"
                    class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-xl shadow-sm border transition-colors
                           bg-white border-gray-200 text-gray-700 hover:bg-gray-100
                           dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100 dark:hover:bg-gray-700">
                    <i id="theme-toggle-icon" class="fi fi-sr-moon text-base"></i>
                    <span id="theme-toggle-label">Modo oscuro</span>
                </button>
            </div>

            <!-- CONTENIDO -->
            <main class="flex-1 p-6 bg-gray-100 dark:bg-gray-950 transition-colors">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            const root = document.documentElement;
            const toggleBtn = document.getElementById('theme-toggle');
            const icon = document.getElementById('theme-toggle-icon');
            const label = document.getElementById('theme-toggle-label');

            function applyTheme(theme) {
                if (theme === 'dark') {
                    root.classList.add('dark');
                    icon.className = 'fi fi-sr-sun text-base';
                    label.textContent = 'Modo claro';
                } else {
                    root.classList.remove('dark');
                    icon.className = 'fi fi-sr-moon text-base';
                    label.textContent = 'Modo oscuro';
                }
            }

            applyTheme(root.classList.contains('dark') ? 'dark' : 'light');

            toggleBtn.addEventListener('click', function () {
                const isDark = root.classList.contains('dark');
                const next = isDark ? 'light' : 'dark';
                applyTheme(next);
                localStorage.setItem('theme', next);
            });
        })();
    </script>

</body>
</html>