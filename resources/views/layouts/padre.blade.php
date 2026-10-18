<!DOCTYPE html>
<html lang="es">
<head>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Padre')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        <!-- SIDEBAR / MENÚ LATERAL (COLORES INSTITUCIONALES) -->
        <div class="w-64 bg-blue-900 dark:bg-gray-900 text-white flex flex-col flex-shrink-0 shadow-lg z-10">
            <div class="p-4 text-center border-b border-blue-800 dark:border-gray-800 bg-blue-950/30">
                <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo del Colegio" class="mx-auto h-20 w-20 rounded-full border-2 border-yellow-400 shadow-md">
                <h2 class="text-sm font-bold mt-2">U.E. GRAL. JOSÉ DE SAN MARTÍN</h2>
                <span class="text-xs text-blue-300">Panel Padre</span>
            </div>

            <!-- MENÚ CON ICONOS -->
            <nav class="flex-1 p-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('padre.dashboard') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('padre.dashboard') ? 'bg-blue-800 border-l-4 border-yellow-400 font-semibold' : 'border-l-4 border-transparent' }}">
                            <i class="fi fi-sr-home text-base"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/padre/asistencia') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('padre.asistencia*') ? 'bg-blue-800 border-l-4 border-yellow-400 font-semibold' : 'border-l-4 border-transparent' }}">
                            <i class="fi fi-sr-calendar-check text-base"></i>
                            <span>Asistencia</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('padre.historial.citaciones') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('padre.historial.citaciones*') ? 'bg-blue-800 border-l-4 border-yellow-400 font-semibold' : 'border-l-4 border-transparent' }}">
                            <i class="fi fi-sr-document text-base"></i>
                            <span>Historial de citaciones</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('padre.perfil') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('padre.perfil*') ? 'bg-blue-800 border-l-4 border-yellow-400 font-semibold' : 'border-l-4 border-transparent' }}">
                            <i class="fi fi-sr-user text-base"></i>
                            <span>Perfil</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-blue-800 dark:border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 text-left text-sm text-blue-200 hover:text-white transition px-4 py-2.5 rounded-lg hover:bg-red-600 font-semibold">
                        <i class="fi fi-sr-sign-out-alt text-base"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- HEADER -->
            <header class="bg-white dark:bg-gray-900 shadow-sm p-4 flex justify-between items-center border-b border-gray-200 dark:border-gray-800 transition-colors">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">@yield('header', 'Panel Padre')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ Auth::user()->nombre ?? 'Padre' }} {{ Auth::user()->apellidos ?? '' }}</span>
                    <div class="w-9 h-9 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-800 dark:text-blue-300 font-bold border-2 border-blue-700 dark:border-blue-500 shadow-sm">
                        {{ substr(Auth::user()->nombre ?? 'P', 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- CONTENIDO -->
            <main class="flex-1 p-6 bg-gray-100 dark:bg-gray-950 transition-colors">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>