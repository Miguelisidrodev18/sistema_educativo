<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Colegio Pre JEDSON') - Sistema de Gestión</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'jedson': {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#0b3d91',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1rem; border-radius: 0.5rem;
            color: #cbd5e1; font-size: 0.875rem; font-weight: 500;
            transition: background-color 0.15s, color 0.15s;
            text-decoration: none;
        }
        .sidebar-link:hover { background-color: #334155; color: #fff; }
        .sidebar-link.active { background-color: #1d4ed8; color: #fff; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-slate-100 font-sans" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">

<div class="flex h-full min-h-screen">

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 transition-all duration-300"
        :class="sidebarOpen ? 'w-64' : 'w-16'"
        x-cloak
    >
        <!-- Logo -->
        <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-700 min-h-[64px]">
            <div class="flex-shrink-0 w-9 h-9 bg-blue-700 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
            </div>
            <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                <p class="text-white font-bold text-sm leading-tight">Colegio Pre</p>
                <p class="text-blue-400 font-extrabold text-base leading-tight">JEDSON</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-1">

            <div x-show="sidebarOpen" x-transition class="px-3 mb-2">
                <p class="text-slate-500 text-xs uppercase font-semibold tracking-wider">Principal</p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-3 mb-2">
                <p class="text-slate-500 text-xs uppercase font-semibold tracking-wider">Gestión</p>
            </div>

            <a href="{{ route('alumnos.index') }}"
               class="sidebar-link {{ request()->routeIs('alumnos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Alumnos</span>
            </a>

            <a href="{{ route('matriculas.index') }}"
               class="sidebar-link {{ request()->routeIs('matriculas.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-signature w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Matrículas</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-3 mb-2">
                <p class="text-slate-500 text-xs uppercase font-semibold tracking-wider">Pagos</p>
            </div>

            <a href="{{ route('pagos.index') }}"
               class="sidebar-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-wave w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Pagos & Pensiones</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-3 mb-2">
                <p class="text-slate-500 text-xs uppercase font-semibold tracking-wider">Asistencia</p>
            </div>

            <a href="{{ route('asistencias.index') }}"
               class="sidebar-link {{ request()->routeIs('asistencias.index') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Alumnos</span>
            </a>

            <a href="{{ route('asistencias.docentes') }}"
               class="sidebar-link {{ request()->routeIs('asistencias.docentes') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Docentes</span>
            </a>

            <a href="{{ route('asistencias.qr') }}"
               class="sidebar-link {{ request()->routeIs('asistencias.qr') ? 'active' : '' }}">
                <i class="fa-solid fa-qrcode w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Lector QR</span>
            </a>

        </nav>

        <!-- User info -->
        <div class="border-t border-slate-700 px-3 py-3">
            <div class="flex items-center gap-3" x-show="sidebarOpen" x-transition>
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs capitalize">{{ auth()->user()->user_type }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors" title="Cerrar sesión">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
            <div x-show="!sidebarOpen" class="flex justify-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors" title="Cerrar sesión">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-16'">

        <!-- Top bar -->
        <header class="sticky top-0 z-40 bg-white border-b border-slate-200 h-16 flex items-center px-6 gap-4">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="text-slate-500 hover:text-slate-900 transition-colors">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <div class="flex-1">
                <h1 class="text-slate-800 font-semibold text-lg">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3 text-sm text-slate-500">
                <i class="fa-regular fa-calendar"></i>
                <span>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="hidden md:block">
                    <p class="text-slate-700 text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs capitalize">{{ auth()->user()->user_type }}</p>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 p-6">

            <!-- Flash messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition
                     class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                     x-transition
                     class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        <span class="text-red-800 text-sm font-medium">Se encontraron errores:</span>
                    </div>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 px-6 py-3 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Arequipa, Perú. Sistema de Gestión Escolar.
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
