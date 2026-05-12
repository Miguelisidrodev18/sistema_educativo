<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión — Colegio Pre JEDSON</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
*{font-family:'Inter',sans-serif}
[x-cloak]{display:none!important}

/* ── Animaciones ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
@keyframes blobMove{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(30px,-20px) scale(1.05)}66%{transform:translate(-20px,15px) scale(0.97)}}
@keyframes slideLeft{from{opacity:0;transform:translateX(-30px)}to{opacity:1;transform:translateX(0)}}
@keyframes slideRight{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}
@keyframes countUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
@keyframes cardPop{from{opacity:0;transform:scale(0.92) translateY(16px)}to{opacity:1;transform:scale(1) translateY(0)}}
@keyframes spin{to{transform:rotate(360deg)}}

.fade-up   {animation:fadeUp 0.55s ease both}
.fade-up-d1{animation:fadeUp 0.55s 0.1s  ease both}
.fade-up-d2{animation:fadeUp 0.55s 0.2s  ease both}
.fade-up-d3{animation:fadeUp 0.55s 0.3s  ease both}
.fade-up-d4{animation:fadeUp 0.55s 0.4s  ease both}
.slide-left {animation:slideLeft  0.6s ease both}
.slide-right{animation:slideRight 0.6s ease both}
.floating  {animation:float 4s ease-in-out infinite}
.blob      {animation:blobMove 8s ease-in-out infinite}
.blob2     {animation:blobMove 11s ease-in-out infinite reverse}
.blob3     {animation:blobMove 9s  ease-in-out infinite 2s}
.card-pop  {animation:cardPop 0.45s ease both}
.card-pop-d1{animation:cardPop 0.45s 0.08s ease both}
.card-pop-d2{animation:cardPop 0.45s 0.16s ease both}
.card-pop-d3{animation:cardPop 0.45s 0.24s ease both}
.card-pop-d3{animation:cardPop 0.45s 0.24s ease both}
.btn-shimmer{background-size:200% 100%;animation:shimmer 3s linear infinite;background-image:linear-gradient(90deg,#1e40af,#3b82f6,#2563eb,#3b82f6,#1e40af)}

/* ── Tarjetas de rol ── */
.role-card{
    transition:all 0.25s cubic-bezier(.175,.885,.32,1.275);
    cursor:pointer;
    background:#f8fafc;
    border:1.5px solid #e2e8f0;
}
.role-card:hover{
    transform:translateY(-4px) scale(1.02);
    border-color:#93c5fd;
    background:#eff6ff;
    box-shadow:0 8px 24px rgba(59,130,246,0.13);
}
.role-card.selected{
    border-color:#3b82f6;
    background:#eff6ff;
    box-shadow:0 0 0 3px rgba(59,130,246,0.2);
}

/* ── Inputs ── */
.input-field{
    width:100%;padding:.75rem 1rem .75rem 2.75rem;
    border:1.5px solid #e2e8f0;border-radius:.75rem;
    font-size:.875rem;color:#1e293b;background:#f8fafc;
    outline:none;transition:all .2s;
}
.input-field:focus{border-color:#3b82f6;background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.15)}
.input-field::placeholder{color:#94a3b8}

/* ── Stat chips ── */
.stat-chip{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    border-radius:1rem;padding:.75rem 1.5rem;
    backdrop-filter:blur(8px);
    transition:all .2s;
}
.stat-chip:hover{background:rgba(255,255,255,.13)}

/* ── Fondo general ── */
.page-bg{
    background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 30%,#faf5ff 60%,#fefce8 100%);
}
</style>
</head>
<body class="page-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

<!-- Blobs decorativos de fondo -->
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="blob  absolute -top-32 -left-32  w-96 h-96  bg-blue-300/25  rounded-full blur-3xl"></div>
    <div class="blob2 absolute -bottom-24 -right-24 w-96 h-96  bg-purple-300/20 rounded-full blur-3xl"></div>
    <div class="blob3 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-teal-200/15 rounded-full blur-3xl"></div>
    <div class="blob  absolute top-10 right-1/4 w-48 h-48 bg-yellow-200/20 rounded-full blur-2xl" style="animation-duration:13s"></div>
</div>

<!-- ════════════════════════════════════════════
     CONTENEDOR PRINCIPAL SPLIT
     ════════════════════════════════════════════ -->
<div class="relative z-10 w-full max-w-5xl"
     x-data="{
        step: '{{ $errors->any() && old('role') ? 'login' : 'roles' }}',
        selectedRole: '{{ old('role') }}' || null,
        loading: false,
        showPass: false,
        roles: [
            { key: 'administrador', label: 'Administrador', icon: 'fa-shield-halved',    color: '#2563eb', bg: '#eff6ff', desc: 'Gestión total del sistema' },
            { key: 'auxiliar',      label: 'Auxiliar',      icon: 'fa-clipboard-list',  color: '#0d9488', bg: '#f0fdfa', desc: 'Apoyo administrativo'      },
            { key: 'docente',       label: 'Docente',        icon: 'fa-chalkboard-user', color: '#7c3aed', bg: '#f5f3ff', desc: 'Asistencia y alumnos'      },
            { key: 'estudiante',    label: 'Alumno',         icon: 'fa-graduation-cap',  color: '#d97706', bg: '#fffbeb', desc: 'Mis pagos y asistencias'   }
        ],
        get current() { return this.roles.find(r => r.key === this.selectedRole) }
     }">

    <!-- ── Tarjeta split ── -->
    <div class="flex rounded-3xl overflow-hidden shadow-2xl shadow-slate-900/20" style="min-height:520px">

        <!-- ══════════════════════════
             PANEL IZQUIERDO (azul oscuro)
             ══════════════════════════ -->
        <div class="slide-left relative hidden md:flex flex-col items-center justify-between w-80 shrink-0 p-8 overflow-hidden"
             style="background:linear-gradient(160deg,#0f172a 0%,#1e3a5f 50%,#0f2644 100%)">

            <!-- Decoración interna -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
                <div class="absolute inset-0" style="background-image:radial-gradient(rgba(255,255,255,.03) 1px,transparent 1px);background-size:28px 28px"></div>
            </div>

            <!-- Logo + nombre -->
            <div class="relative text-center">
                <div class="fade-up inline-block mb-5">
                    <div class="floating w-24 h-24 mx-auto mb-1 flex items-center justify-center" style="animation-duration:3.5s">
                        @php
                            $loginLogo = collect(['logo.png','logo.svg','ebe.png','logo.jpg','logo.webp'])
                                ->first(fn($f) => file_exists(public_path("images/{$f}")));
                        @endphp
                        @if($loginLogo)
                        <img src="{{ asset('images/'.$loginLogo) }}" alt="Logo JEDSON"
                             class="h-24 w-auto object-contain drop-shadow-2xl">
                        @else
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-xl shadow-blue-500/30">
                            <i class="fa-solid fa-graduation-cap text-white text-4xl"></i>
                        </div>
                        @endif
                    </div>
                </div>

                <h1 class="fade-up-d1 text-white text-xl font-extrabold leading-tight">Colegio Pre<br><span class="text-blue-300">JEDSON</span></h1>
                <p class="fade-up-d2 text-slate-400 text-xs mt-2 font-medium">Sistema de Gestión Escolar</p>
                <div class="fade-up-d2 w-10 h-0.5 bg-blue-500/60 mx-auto mt-3 rounded-full"></div>
                <p class="fade-up-d3 text-slate-500 text-xs mt-3">Arequipa &bull; Perú</p>
            </div>

            <!-- Estadísticas -->
            <div class="relative w-full space-y-3 fade-up-d3">
                <div class="stat-chip flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-calendar-check text-blue-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-blue-300 text-lg font-extrabold leading-none">5+</p>
                        <p class="text-slate-500 text-[10px] uppercase tracking-wide font-semibold">Años de experiencia</p>
                    </div>
                </div>
                <div class="stat-chip flex items-center gap-3">
                    <div class="w-8 h-8 bg-teal-500/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user-graduate text-teal-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-teal-300 text-lg font-extrabold leading-none">300+</p>
                        <p class="text-slate-500 text-[10px] uppercase tracking-wide font-semibold">Estudiantes</p>
                    </div>
                </div>
                <div class="stat-chip flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-award text-purple-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-purple-300 text-lg font-extrabold leading-none">Excelencia</p>
                        <p class="text-slate-500 text-[10px] uppercase tracking-wide font-semibold">Educativa</p>
                    </div>
                </div>
            </div>

            <!-- Footer panel izquierdo -->
            <p class="relative text-slate-600 text-[10px] text-center fade-up-d4">&copy; {{ date('Y') }} Colegio Pre JEDSON</p>
        </div>

        <!-- ══════════════════════════
             PANEL DERECHO (blanco)
             ══════════════════════════ -->
        <div class="slide-right flex-1 bg-white flex flex-col">

            <!-- Volver al inicio (top bar) -->
            <div class="flex items-center justify-between px-8 pt-6 pb-2">
                <a href="{{ route('landing') }}"
                   class="inline-flex items-center gap-1.5 text-slate-400 hover:text-blue-600 text-sm transition group font-medium">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform text-xs"></i>
                    Volver al inicio
                </a>
                <!-- Logo móvil (solo en pantallas pequeñas) -->
                <div class="flex md:hidden items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto"
                         onerror="this.style.display='none'">
                    <span class="text-sm font-bold text-slate-700">JEDSON</span>
                </div>
            </div>

            <!-- ── STEP 1: Selección de rol ── -->
            <div x-show="step === 'roles'" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-4"
                 class="flex-1 flex flex-col justify-center px-8 pb-8 pt-4">

                <!-- Bienvenida -->
                <div class="mb-8">
                    <h2 class="fade-up text-2xl font-extrabold text-slate-800">
                        ¡Bienvenido! <span class="inline-block" style="animation:float 2s ease-in-out infinite">👋</span>
                    </h2>
                    <p class="fade-up-d1 text-slate-500 text-sm mt-1">Selecciona tu tipo de acceso para continuar</p>
                </div>

                <!-- Cards de rol (3 tarjetas en fila, o 2+1 centrado) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Administrador -->
                    <button type="button"
                            @click="selectedRole = 'administrador'; step = 'login'"
                            class="card-pop role-card rounded-2xl p-6 flex flex-col items-center gap-3 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-md"
                             style="background:#eff6ff">
                            <i class="fa-solid fa-shield-halved text-2xl" style="color:#2563eb"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Administrador</p>
                            <p class="text-slate-400 text-xs mt-0.5">Gestión total del sistema</p>
                        </div>
                    </button>

                    <!-- Auxiliar -->
                    <button type="button"
                            @click="selectedRole = 'auxiliar'; step = 'login'"
                            class="card-pop-d1 role-card rounded-2xl p-6 flex flex-col items-center gap-3 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-md"
                             style="background:#f0fdfa">
                            <i class="fa-solid fa-clipboard-list text-2xl" style="color:#0d9488"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Auxiliar</p>
                            <p class="text-slate-400 text-xs mt-0.5">Apoyo administrativo</p>
                        </div>
                    </button>

                    <!-- Docente -->
                    <button type="button"
                            @click="selectedRole = 'docente'; step = 'login'"
                            class="card-pop-d2 role-card rounded-2xl p-6 flex flex-col items-center gap-3 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-md"
                             style="background:#f5f3ff">
                            <i class="fa-solid fa-chalkboard-user text-2xl" style="color:#7c3aed"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Docente</p>
                            <p class="text-slate-400 text-xs mt-0.5">Asistencia y alumnos</p>
                        </div>
                    </button>

                    <!-- Alumno -->
                    <button type="button"
                            @click="selectedRole = 'estudiante'; step = 'login'"
                            class="card-pop-d3 role-card rounded-2xl p-6 flex flex-col items-center gap-3 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-md"
                             style="background:#fffbeb">
                            <i class="fa-solid fa-graduation-cap text-2xl" style="color:#d97706"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Alumno</p>
                            <p class="text-slate-400 text-xs mt-0.5">Mis pagos y asistencias</p>
                        </div>
                    </button>
                </div>

                <p class="mt-8 text-center text-slate-400 text-xs">
                    &copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Sistema de Gestión Escolar
                </p>
            </div>

            <!-- ── STEP 2: Formulario de login ── -->
            <div x-show="step === 'login'" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-4"
                 class="flex-1 flex flex-col justify-center px-8 pb-8 pt-4">

                <!-- Cabecera con rol seleccionado -->
                <div class="mb-6 fade-up">
                    <div class="flex items-center gap-3 mb-4">
                        <button type="button" @click="step = 'roles'"
                                class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-500 hover:text-blue-600 transition shadow-sm">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                        </button>
                        <template x-if="current">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border"
                                  :style="`color:${current.color};background:${current.bg};border-color:${current.color}33`">
                                <i class="fa-solid" :class="current.icon"></i>
                                <span x-text="current.label"></span>
                            </span>
                        </template>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-800">Iniciar sesión</h2>
                    <p class="text-slate-500 text-sm mt-1">Ingresa tus credenciales para acceder</p>
                </div>

                <!-- Sesión expirada -->
                @if(session('error'))
                    <div class="mb-5 flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 fade-up">
                        <i class="fa-solid fa-clock text-amber-500 mt-0.5 shrink-0 text-sm"></i>
                        <p class="text-xs text-amber-700">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Errores de validación -->
                @if($errors->any())
                    <div class="mb-5 flex items-start gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 fade-up">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 shrink-0 text-sm"></i>
                        <div class="text-xs text-red-600 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" @submit="loading = true" class="space-y-4 fade-up-d1">
                    @csrf
                    <input type="hidden" name="role" :value="selectedRole">

                    <!-- DNI o Correo -->
                    <div>
                        <label for="identifier" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                            DNI o Correo Electrónico
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-id-card text-sm"></i>
                            </span>
                            <input type="text" id="identifier" name="identifier"
                                   value="{{ old('identifier') }}"
                                   autocomplete="username"
                                   class="input-field {{ $errors->has('identifier') ? 'border-red-400 bg-red-50' : '' }}"
                                   placeholder="12345678 ó correo@jedson.edu.pe"
                                   required autofocus>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Contraseña
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'"
                                   id="password" name="password"
                                   autocomplete="current-password"
                                   class="input-field pr-12 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
                                   placeholder="••••••••" required>
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-sm text-slate-500">Recordarme</label>
                    </div>

                    <!-- Botón -->
                    <button type="submit" :disabled="loading"
                            class="w-full btn-shimmer text-white font-bold py-3.5 px-6 rounded-xl transition-all flex items-center justify-center gap-2 disabled:opacity-60 shadow-lg shadow-blue-500/25 hover:brightness-110 mt-2">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Ingresar al Sistema
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner" style="animation:spin .8s linear infinite"></i>
                                Verificando...
                            </span>
                        </template>
                    </button>
                </form>

                <p class="mt-6 text-center text-slate-400 text-xs">
                    &copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Sistema de Gestión Escolar
                </p>
            </div>
            <!-- fin panel derecho -->
        </div>
        <!-- fin split -->
    </div>

</div>
</body>
</html>
