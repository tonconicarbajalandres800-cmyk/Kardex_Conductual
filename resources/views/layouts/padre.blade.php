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
<body class="bg-slate-200 dark:bg-gray-950 transition-colors">

    <div class="flex h-screen">

        <!-- SIDEBAR / MENÚ LATERAL -->
        <div class="w-64 bg-green-800 dark:bg-green-950 text-white flex flex-col flex-shrink-0">
            <div class="p-4 text-center border-b border-green-700 dark:border-green-900">
                <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo del Colegio" class="mx-auto h-20 w-20">
                <h2 class="text-sm font-bold mt-2">U.E. GRAL. JOSÉ DE SAN MARTÍN</h2>
                <span class="text-xs text-green-300">Panel Padre</span>
            </div>

            <!-- MENÚ CON ICONOS -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('padre.dashboard') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-green-600 transition {{ request()->routeIs('padre.dashboard') ? 'bg-green-700' : '' }}">
                            <i class="fi fi-sr-home text-base"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/padre/asistencia') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-green-600 transition {{ request()->routeIs('padre.asistencia*') ? 'bg-green-700' : '' }}">
                            <i class="fi fi-sr-calendar-check text-base"></i>
                            <span>Asistencia</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('padre.historial.citaciones') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-green-600 transition {{ request()->routeIs('padre.historial.citaciones*') ? 'bg-green-700' : '' }}">
                            <i class="fi fi-sr-document text-base"></i>
                            <span>Historial de citaciones</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('padre.perfil') }}" class="flex items-center gap-3 py-2 px-4 rounded hover:bg-green-600 transition {{ request()->routeIs('padre.perfil*') ? 'bg-green-700' : '' }}">
                            <i class="fi fi-sr-user text-base"></i>
                            <span>Perfil</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-green-700 dark:border-green-900">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 text-left text-sm text-green-300 hover:text-white transition px-4 py-2 rounded hover:bg-green-600">
                        <i class="fi fi-sr-sign-out-alt text-base"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- HEADER -->
            <header class="bg-slate-100 dark:bg-gray-900 shadow-sm p-4 flex justify-between items-center border-b border-slate-300 dark:border-gray-800 transition-colors">
                <div>
                    <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-100">@yield('header', 'Panel Padre')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->nombre ?? 'Padre' }} {{ Auth::user()->apellidos ?? '' }}</span>
                    <div class="w-8 h-8 bg-green-200 dark:bg-green-800 rounded-full flex items-center justify-center text-green-700 dark:text-green-200 font-bold">
                        {{ substr(Auth::user()->nombre ?? 'P', 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- CONTENIDO -->
            <main class="flex-1 p-6 bg-slate-200 dark:bg-gray-950 transition-colors">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>