<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Colegio Pre JEDSON') — Sistema de Gestión</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *{ font-family:'Inter',sans-serif }
        [x-cloak]{ display:none!important }

        /* ══════════════════════════════
           SIDEBAR — dinámico y animado
           ══════════════════════════════ */

        /* Fondo sidebar con micro-gradiente */
        aside {
            background: linear-gradient(180deg,#ffffff 0%,#f8faff 100%) !important;
        }

        /* ── Nav links ── */
        .nav-link{
            display:flex; align-items:center; gap:.75rem;
            padding:.6rem 1rem; border-radius:.75rem;
            color:#64748b; font-size:.8125rem; font-weight:500;
            transition:all .22s cubic-bezier(.4,0,.2,1);
            text-decoration:none; position:relative; overflow:hidden;
        }
        /* Ícono: transition independiente */
        .nav-link i{
            transition: transform .22s cubic-bezier(.4,0,.2,1),
                        color .22s ease;
            font-size:.875rem;
        }
        /* Hover: desplazamiento sutil + fondo */
        .nav-link:hover{
            background: linear-gradient(90deg,#eff6ff,#f0f9ff);
            color:#1d4ed8;
            transform: translateX(2px);
        }
        .nav-link:hover i{ transform: scale(1.15); color:#2563eb; }

        /* Activo: gradiente azul sólido */
        .nav-link.active{
            background: linear-gradient(90deg,#2563eb 0%,#4f46e5 100%);
            color:#fff !important;
            font-weight:700;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
        }
        .nav-link.active i{ color:#fff !important; transform:scale(1); }
        .nav-link.active::after{
            content:'';
            position:absolute; right:10px; top:50%; transform:translateY(-50%);
            width:6px; height:6px; border-radius:50%;
            background:rgba(255,255,255,.6);
            animation: activePulse 2s ease-in-out infinite;
        }
        @keyframes activePulse{
            0%,100%{ opacity:.6; transform:translateY(-50%) scale(1); }
            50%{ opacity:1; transform:translateY(-50%) scale(1.4); }
        }

        /* ── Sección labels ── */
        .nav-section{
            font-size:.6rem; font-weight:800; text-transform:uppercase;
            letter-spacing:.1em; color:#cbd5e1;
            padding:.6rem .9rem .2rem;
            display:flex; align-items:center; gap:.5rem;
        }
        .nav-section::after{
            content:''; flex:1; height:1px;
            background:linear-gradient(90deg,#e2e8f0,transparent);
        }

        /* ── Entrada animada de los nav items ── */
        @keyframes navSlideIn{
            from{ opacity:0; transform:translateX(-12px) }
            to  { opacity:1; transform:translateX(0) }
        }
        nav a, nav div{ animation: navSlideIn .3s ease both; }
        nav a:nth-child(1){ animation-delay:.04s }
        nav a:nth-child(2){ animation-delay:.08s }
        nav a:nth-child(3){ animation-delay:.12s }
        nav a:nth-child(4){ animation-delay:.16s }
        nav a:nth-child(5){ animation-delay:.20s }
        nav a:nth-child(6){ animation-delay:.24s }
        nav a:nth-child(7){ animation-delay:.28s }
        nav a:nth-child(8){ animation-delay:.32s }
        nav a:nth-child(9){ animation-delay:.36s }

        /* ── Fondo principal ── */
        .page-bg{
            background:linear-gradient(135deg,#f0f9ff 0%,#e8f4fd 30%,#f5f3ff 65%,#fefce8 100%);
        }

        /* ── Blobs de fondo ── */
        .blob-1{ animation: blobA 9s ease-in-out infinite; }
        .blob-2{ animation: blobA 12s ease-in-out infinite reverse; }
        .blob-3{ animation: blobA 10s ease-in-out infinite 2s; }
        @keyframes blobA{
            0%,100%{ transform:translate(0,0) scale(1) }
            33%{ transform:translate(18px,-14px) scale(1.04) }
            66%{ transform:translate(-14px,10px) scale(.97) }
        }

        /* ── Animaciones de página ── */
        @keyframes slideDown{ from{ opacity:0;transform:translateY(-8px) } to{ opacity:1;transform:translateY(0) } }
        @keyframes fadeUp   { from{ opacity:0;transform:translateY(14px) } to{ opacity:1;transform:translateY(0) } }
        .slide-down { animation:slideDown .3s ease both }
        .fade-up    { animation:fadeUp .4s       ease both }
        .fade-up-d1 { animation:fadeUp .4s .07s  ease both }
        .fade-up-d2 { animation:fadeUp .4s .14s  ease both }
        .fade-up-d3 { animation:fadeUp .4s .21s  ease both }
    </style>
    @stack('styles')
</head>

<body class="h-full page-bg font-sans min-h-screen relative"
      x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">

<!-- Blobs decorativos de fondo -->
<div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="blob-1 absolute -top-20 -left-20 w-80 h-80 bg-blue-300/15 rounded-full blur-3xl"></div>
    <div class="blob-2 absolute -bottom-16 -right-16 w-96 h-96 bg-purple-300/12 rounded-full blur-3xl"></div>
    <div class="blob-3 absolute top-1/2 right-1/4 w-64 h-64 bg-teal-200/10 rounded-full blur-3xl"></div>
</div>

<div class="flex h-full min-h-screen">

    <!-- ══════════════════════════
         SIDEBAR (fondo blanco)
         ══════════════════════════ -->
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-200 shadow-sm transition-all duration-300"
           :class="sidebarOpen ? 'w-60' : 'w-16'"
           x-cloak>

        <!-- Logo -->
        <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 min-h-[64px]">
            <div class="shrink-0">
                @php
                    $sidebarLogoFile = collect(['logo.png','logo.svg','ebe.png','logo.jpg','logo.webp'])
                        ->first(fn($f) => file_exists(public_path("images/{$f}")));
                @endphp
                @if($sidebarLogoFile)
                <img src="{{ asset('images/'.$sidebarLogoFile) }}" alt="Logo" class="w-9 h-9 rounded-xl object-contain">
                @else
                <div style="display:flex" class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl items-center justify-center shadow">
                    <i class="fa-solid fa-graduation-cap text-white text-base"></i>
                </div>
                @endif
            </div>
            <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                <p class="text-slate-500 font-semibold text-xs leading-tight">Colegio Pre</p>
                <p class="text-blue-700 font-extrabold text-base leading-tight">JEDSON</p>
            </div>
        </div>

        @php $esEstudiante = auth()->user()->isEstudiante(); @endphp

        <!-- Selector de sede (solo no-estudiantes) -->
        @php $todasSedes = $esEstudiante ? collect() : \App\Models\Sede::where('activo',true)->orderBy('nombre')->get(); @endphp
        @if(!$esEstudiante && $todasSedes->count() > 0)
        <div class="px-3 py-2 border-b border-slate-100" x-show="sidebarOpen" x-transition>
            @php $sedeActualNombre = \App\Models\Sede::find(session('sede_id'))?->nombre; @endphp
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sede activa</p>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl border text-xs font-semibold transition
                            {{ $sedeActualNombre ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100' }}">
                    <span class="flex items-center gap-1.5 truncate">
                        <i class="fa-solid fa-building-columns text-[10px] shrink-0"></i>
                        <span class="truncate">{{ $sedeActualNombre ?? 'Todas las sedes' }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-[9px] shrink-0 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-cloak @click.outside="open = false"
                     class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl border border-slate-200 shadow-lg z-50 overflow-hidden"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <!-- Todas -->
                    <form action="{{ route('sedes.seleccionar', 0) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-3 py-2 text-xs font-semibold hover:bg-slate-50 transition flex items-center gap-2
                                    {{ !session('sede_id') ? 'text-blue-700 bg-blue-50' : 'text-slate-600' }}">
                            <i class="fa-solid fa-globe text-[9px] w-3"></i>
                            Todas las sedes
                            @if(!session('sede_id'))
                                <i class="fa-solid fa-check ml-auto text-blue-600 text-[9px]"></i>
                            @endif
                        </button>
                    </form>
                    @foreach($todasSedes as $s)
                    <form action="{{ route('sedes.seleccionar', $s) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-3 py-2 text-xs font-semibold hover:bg-blue-50 transition flex items-center gap-2
                                    {{ session('sede_id') == $s->id ? 'text-blue-700 bg-blue-50' : 'text-slate-600' }}">
                            <i class="fa-solid fa-building-columns text-[9px] w-3"></i>
                            {{ $s->nombre }}
                            @if(session('sede_id') == $s->id)
                                <i class="fa-solid fa-check ml-auto text-blue-600 text-[9px]"></i>
                            @endif
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Ícono de sede colapsado -->
        <div class="px-2 py-2 border-b border-slate-100 flex justify-center" x-show="!sidebarOpen">
            @php $haySedeActiva = session('sede_id'); @endphp
            <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $haySedeActiva ? 'bg-blue-100' : 'bg-slate-100' }}"
                 title="{{ $sedeActualNombre ?? 'Todas las sedes' }}">
                <i class="fa-solid fa-building-columns text-sm {{ $haySedeActiva ? 'text-blue-600' : 'text-slate-400' }}"></i>
            </div>
        </div>
        @endif

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-2 py-3 space-y-0.5">

        @if($esEstudiante)
            {{-- ══ MENÚ ESTUDIANTE ══ --}}
            <div x-show="sidebarOpen" x-transition><p class="nav-section">Mi Portal</p></div>

            <a href="{{ route('estudiante.dashboard') }}"
               class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Inicio</span>
            </a>

            <a href="{{ route('estudiante.perfil') }}"
               class="nav-link {{ request()->routeIs('estudiante.perfil*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-circle w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Mi Perfil</span>
            </a>

            <div x-show="sidebarOpen" x-transition><p class="nav-section mt-2">Académico</p></div>

            <a href="{{ route('estudiante.matricula') }}"
               class="nav-link {{ request()->routeIs('estudiante.matricula') ? 'active' : '' }}">
                <i class="fa-solid fa-file-signature w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Mi Matrícula</span>
            </a>

            <a href="{{ route('estudiante.pagos') }}"
               class="nav-link {{ request()->routeIs('estudiante.pagos') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-wave w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Mis Pagos</span>
            </a>

            <a href="{{ route('estudiante.asistencias') }}"
               class="nav-link {{ request()->routeIs('estudiante.asistencias') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-check w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Asistencias</span>
            </a>

        @else
            {{-- ══ MENÚ PERSONAL (admin / auxiliar / docente) ══ --}}
            <div x-show="sidebarOpen" x-transition><p class="nav-section">Principal</p></div>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Panel</span>
            </a>

            <div x-show="sidebarOpen" x-transition><p class="nav-section mt-2">Gestión</p></div>

            <a href="{{ route('alumnos.index') }}"
               class="nav-link {{ request()->routeIs('alumnos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Alumnos</span>
            </a>

            <a href="{{ route('matriculas.index') }}"
               class="nav-link {{ request()->routeIs('matriculas.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-signature w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Matrículas</span>
            </a>

            <div x-show="sidebarOpen" x-transition><p class="nav-section mt-2">Pagos</p></div>

            <a href="{{ route('pagos.index') }}"
               class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-wave w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Pagos & Pensiones</span>
            </a>

            <div x-show="sidebarOpen" x-transition><p class="nav-section mt-2">Asistencia</p></div>

            <a href="{{ route('asistencias.index') }}"
               class="nav-link {{ request()->routeIs('asistencias.index') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-check w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Alumnos</span>
            </a>

            <a href="{{ route('asistencias.docentes') }}"
               class="nav-link {{ request()->routeIs('asistencias.docentes') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Docentes</span>
            </a>

            <a href="{{ route('asistencias.qr') }}"
               class="nav-link {{ request()->routeIs('asistencias.qr') ? 'active' : '' }}">
                <i class="fa-solid fa-qrcode w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Lector QR</span>
            </a>

            @if(auth()->user()->isAdmin())
            <div x-show="sidebarOpen" x-transition><p class="nav-section mt-2">Sistema</p></div>

            <a href="{{ route('sedes.index') }}"
               class="nav-link {{ request()->routeIs('sedes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-building-columns w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Sedes</span>
            </a>

            <a href="{{ route('configuracion.index') }}"
               class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Configuración</span>
            </a>

            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear w-5 text-center shrink-0 text-slate-400"></i>
                <span x-show="sidebarOpen" x-transition class="truncate">Usuarios</span>
            </a>
            @endif
        @endif

        </nav>

        <!-- User info -->
        <div class="border-t border-slate-100 px-3 py-3">
            <div class="flex items-center gap-2.5" x-show="sidebarOpen" x-transition>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shrink-0 shadow-sm">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-700 text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-[11px] capitalize">{{ auth()->user()->user_type }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition p-1 rounded-lg hover:bg-red-50" title="Cerrar sesión">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
            <div x-show="!sidebarOpen" class="flex justify-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition p-1 rounded-lg hover:bg-red-50" title="Cerrar sesión">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ══════════════════════════
         CONTENIDO PRINCIPAL
         ══════════════════════════ -->
    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300"
         :class="sidebarOpen ? 'ml-60' : 'ml-16'">

        <!-- Top bar -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80 h-16 flex items-center px-6 gap-4 shadow-sm slide-down">

            <button @click="sidebarOpen = !sidebarOpen"
                    class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="flex-1 min-w-0">
                <h1 class="text-slate-800 font-bold text-base truncate">@yield('page-title', 'Panel')</h1>
            </div>

            <!-- Sede badge en topbar -->
            @php $sedeActiva = \App\Models\Sede::find(session('sede_id')); @endphp
            @if($sedeActiva)
                <div class="hidden sm:flex items-center gap-1.5 bg-blue-600 text-white rounded-full px-3 py-1 text-xs font-bold shadow-sm shadow-blue-500/30">
                    <i class="fa-solid fa-building-columns text-[10px]"></i>
                    {{ $sedeActiva->nombre }}
                </div>
            @else
                <div class="hidden sm:flex items-center gap-1.5 bg-slate-100 text-slate-500 rounded-full px-3 py-1 text-xs font-semibold border border-slate-200">
                    <i class="fa-solid fa-globe text-[10px]"></i>
                    Todas las sedes
                </div>
            @endif

            <div class="hidden md:flex items-center gap-1.5 text-slate-400 text-xs">
                <i class="fa-regular fa-calendar"></i>
                <span>{{ now()->locale('es')->isoFormat('D MMM YYYY') }}</span>
            </div>

            <!-- Avatar -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-sm">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="hidden lg:block">
                    <p class="text-slate-700 text-sm font-semibold leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-[11px] capitalize">{{ auth()->user()->user_type }}</p>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 p-6">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4500)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 shadow-sm">
                    <div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check text-green-600 text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                     x-transition
                     class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 shadow-sm">
                    <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-600 text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500 text-sm"></i>
                        <span class="text-red-800 text-sm font-semibold">Se encontraron errores:</span>
                    </div>
                    <ul class="list-disc list-inside text-red-700 text-xs space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-white/60 border-t border-slate-200/80 px-6 py-3 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Arequipa, Perú &mdash; Sistema de Gestión Escolar
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
