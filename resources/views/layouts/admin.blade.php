<!DOCTYPE html>
<html lang="es">
<head>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrador')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Colores institucionales */
        .bg-colegio { background-color: #1e3a8a; }
        .bg-colegio-claro { background-color: #1d4ed8; }
        .bg-colegio-oscuro { background-color: #0f172a; }
        .text-colegio { color: #1e3a8a; }
        .border-colegio { border-color: #1e3a8a; }
        
        /* Gradiente institucional elegante */
        .bg-colegio-gradiente { 
            background: linear-gradient(180deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);
        }
        
        .hover-colegio:hover {
            background-color: #1d4ed8;
            color: white;
        }
        
        .active-colegio {
            background-color: #facc15;
            color: #0f172a;
        }
        
        .sidebar-icon {
            width: 20px;
            text-align: center;
        }

        /* Scrollbar institucional */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }

        /* Scrollbar en modo oscuro */
        .dark ::-webkit-scrollbar-track {
            background: #1a202c;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #1d4ed8;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }
    </style>
    @stack('styles')

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
<body class="bg-slate-100 dark:bg-gray-950 transition-colors">
    <div class="flex h-screen">

        <!-- ============================================ -->
        <!-- MENÚ LATERAL CON COLORES INSTITUCIONALES      -->
        <!-- ============================================ -->
        <aside class="w-64 bg-colegio-gradiente text-white flex flex-col shadow-2xl">
            <!-- Logo y título -->
            <div class="p-4 border-b border-blue-700/50">
                <div class="flex items-center space-x-3">
                    <div class="h-14 w-14 rounded-full overflow-hidden border-2 border-yellow-400 bg-white flex items-center justify-center shadow-lg">
                        <img src="{{ asset('images/escudo.jpg') }}" alt="Escudo" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <span class="text-sm font-black leading-tight block text-white tracking-wide">KARDEX CONDUCTUAL</span>
                        <span class="text-[10px] text-blue-300 font-semibold">U.E. GRAL. JOSÉ DE SAN MARTÍN</span>
                    </div>
                </div>
            </div>

            <!-- Información del director -->
            <div class="p-4 border-b border-blue-700/50 bg-blue-950/30">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fi fi-sr-user-shield text-yellow-400"></i>
                    <p class="text-xs text-blue-300 font-bold uppercase tracking-wider">Director</p>
                </div>
                <p class="font-bold text-white text-sm">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-xs text-yellow-300 hover:text-yellow-100 transition inline-flex items-center gap-2 font-semibold">
                        <i class="fi fi-sr-sign-out-alt"></i>
                        Cerrar Sesión
                    </button>
                </form>
            </div>

            <!-- Menú de navegación -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <p class="text-[10px] text-blue-300/70 font-black uppercase tracking-widest px-3 mb-2">Principal</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 text-blue-950 font-bold shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' }}">
                    <i class="fi fi-sr-dashboard sidebar-icon text-base"></i>
                    <span class="text-sm">Inicio</span>
                </a>
                
                <p class="text-[10px] text-blue-300/70 font-black uppercase tracking-widest px-3 mt-4 mb-2">Gestión</p>
                
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.usuarios.*') ? 'bg-yellow-400 text-blue-950 font-bold shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' }}">
                    <i class="fi fi-sr-users sidebar-icon text-base"></i>
                    <span class="text-sm">Usuarios</span>
                </a>
                
                <a href="{{ route('admin.cursos.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cursos.*') ? 'bg-yellow-400 text-blue-950 font-bold shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' }}">
                    <i class="fi fi-sr-book sidebar-icon text-base"></i>
                    <span class="text-sm">Cursos</span>
                </a>
                
                <a href="{{ route('admin.citaciones.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.citaciones.*') ? 'bg-yellow-400 text-blue-950 font-bold shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' }}">
                    <i class="fi fi-sr-document sidebar-icon text-base"></i>
                    <span class="text-sm">Citaciones</span>
                </a>
                
                <p class="text-[10px] text-blue-300/70 font-black uppercase tracking-widest px-3 mt-4 mb-2">Sistema</p>
                
                <a href="{{ route('admin.configuracion.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.configuracion.*') ? 'bg-yellow-400 text-blue-950 font-bold shadow-lg' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' }}">
                    <i class="fi fi-sr-settings sidebar-icon text-base"></i>
                    <span class="text-sm">Configuración</span>
                </a>
            </nav>

            <!-- Footer del menú -->
            <div class="p-4 border-t border-blue-700/50 text-center bg-blue-950/30">
                <p class="text-[10px] text-blue-300/70 font-bold">Gestión {{ date('Y') }}</p>
                <p class="text-[10px] text-blue-300/50">Sistema Kardex Conductual</p>
            </div>
        </aside>

        <!-- ============================================ -->
        <!-- CONTENIDO PRINCIPAL                          -->
        <!-- ============================================ -->
        <main class="flex-1 p-6 overflow-y-auto bg-slate-100 dark:bg-gray-950 transition-colors">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>