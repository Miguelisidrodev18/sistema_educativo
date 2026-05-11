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
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
@keyframes orbit{0%{transform:rotate(0deg) translateX(110px) rotate(0deg)}100%{transform:rotate(360deg) translateX(110px) rotate(-360deg)}}
@keyframes pulse-ring{0%{transform:scale(0.9);opacity:0.7}50%{transform:scale(1.1);opacity:0.2}100%{transform:scale(0.9);opacity:0.7}}
.fade-up{animation:fadeUp 0.5s ease both}
.fade-up-d1{animation:fadeUp 0.5s 0.08s ease both}
.fade-up-d2{animation:fadeUp 0.5s 0.16s ease both}
.fade-up-d3{animation:fadeUp 0.5s 0.24s ease both}
.fade-up-d4{animation:fadeUp 0.5s 0.32s ease both}
.fade-in{animation:fadeIn 0.4s ease both}
.floating{animation:float 4s ease-in-out infinite}
.orbit{animation:orbit 22s linear infinite}
.orbit-rev{animation:orbit 32s linear infinite reverse}
.pulse-ring{animation:pulse-ring 3s ease-in-out infinite}
.login-bg{background:linear-gradient(135deg,#0c0f1a 0%,#111827 35%,#1e1b4b 65%,#0f172a 100%)}
.glass{background:rgba(255,255,255,0.055);backdrop-filter:blur(22px);border:1px solid rgba(255,255,255,0.09)}
.btn-shimmer{background-size:200% 100%;animation:shimmer 3s linear infinite;background-image:linear-gradient(90deg,#4f46e5,#7c3aed,#6366f1,#7c3aed,#4f46e5)}
.input-glow:focus{box-shadow:0 0 0 3px rgba(99,102,241,0.25)}
.role-card{transition:all 0.2s ease;cursor:pointer}
.role-card:hover{transform:translateY(-3px)}
.role-card:hover .role-ring{opacity:1}
.role-ring{opacity:0;transition:opacity 0.2s}
</style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

<!-- Fondo animado -->
<div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-blue-600/8 rounded-full blur-3xl floating"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-600/8 rounded-full blur-3xl" style="animation:float 5s ease-in-out infinite;animation-delay:1s"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="w-3 h-3 bg-blue-400/25 rounded-full orbit"></div>
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="w-2 h-2 bg-purple-400/20 rounded-full orbit-rev"></div>
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-56 h-56 border border-white/[0.04] rounded-full pulse-ring"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-white/[0.025] rounded-full pulse-ring" style="animation-delay:1.2s"></div>
    <div class="absolute inset-0" style="background-image:radial-gradient(rgba(255,255,255,0.025) 1px,transparent 1px);background-size:40px 40px"></div>
</div>

<div class="w-full max-w-lg relative z-10"
     x-data="{
        step: '{{ $errors->any() && old('role') ? 'login' : 'roles' }}',
        selectedRole: '{{ old('role') }}' || null,
        loading: false,
        showPass: false,
        roles: [
            { key: 'administrador', label: 'Administrador',  icon: 'fa-shield-halved',      from: 'from-blue-500',   to: 'to-blue-700',   ring: 'ring-blue-500/40',   text: 'text-blue-400',   desc: 'Gestión total del sistema' },
            { key: 'auxiliar',      label: 'Auxiliar',        icon: 'fa-clipboard-list',     from: 'from-teal-500',   to: 'to-teal-700',   ring: 'ring-teal-500/40',   text: 'text-teal-400',   desc: 'Apoyo administrativo' },
            { key: 'docente',       label: 'Docente',         icon: 'fa-chalkboard-user',    from: 'from-violet-500', to: 'to-violet-700', ring: 'ring-violet-500/40', text: 'text-violet-400', desc: 'Asistencia y alumnos' }
        ],
        get current() { return this.roles.find(r => r.key === this.selectedRole) }
     }">

    <!-- Volver al landing -->
    <a href="{{ route('landing') }}"
       class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm mb-6 transition group fade-up">
        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Volver al inicio
    </a>

    <!-- ── STEP 1: Selección de rol ── -->
    <div x-show="step === 'roles'" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4">

        <div class="glass rounded-3xl shadow-2xl shadow-black/30 overflow-hidden">

            <!-- Header -->
            <div class="relative px-8 pt-8 pb-6 text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-blue-600/8 to-transparent"></div>
                <div class="relative">
                    <!-- Logo -->
                    <div class="fade-up inline-block mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo JEDSON"
                             class="h-16 w-auto mx-auto floating" style="animation-duration:3.5s"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                        <div style="display:none" class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg floating" style="animation-duration:3.5s">
                            <i class="fa-solid fa-graduation-cap text-white text-2xl"></i>
                        </div>
                    </div>
                    <h1 class="fade-up-d1 text-white text-2xl font-extrabold tracking-tight">Colegio Pre JEDSON</h1>
                    <p class="fade-up-d2 text-slate-400 text-sm mt-1">Arequipa &bull; Perú &bull; Sistema de Gestión</p>
                </div>
            </div>

            <!-- Role picker -->
            <div class="px-8 pb-8">
                <p class="fade-up-d2 text-slate-300 text-sm font-semibold text-center mb-5">
                    Selecciona tu perfil para continuar
                </p>

                <div class="grid grid-cols-3 gap-3 fade-up-d3">
                    <template x-for="role in roles" :key="role.key">
                        <button type="button"
                                @click="selectedRole = role.key; step = 'login'"
                                class="role-card glass rounded-2xl p-4 flex flex-col items-center gap-3 group"
                                :class="`ring-0 hover:ring-2 ${role.ring}`">

                            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br shadow-lg"
                                 :class="`${role.from} ${role.to}`">
                                <i class="fa-solid text-white text-lg" :class="role.icon"></i>
                            </div>

                            <div class="text-center">
                                <p class="text-white text-xs font-bold leading-tight" x-text="role.label"></p>
                                <p class="text-slate-500 text-[10px] mt-0.5 leading-tight" x-text="role.desc"></p>
                            </div>

                            <div class="role-ring w-full h-0.5 rounded-full bg-gradient-to-r"
                                 :class="`${role.from} ${role.to}`"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <p class="fade-up-d4 text-center text-slate-600 text-xs mt-5">
            &copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Sistema de Gestión Escolar
        </p>
    </div>

    <!-- ── STEP 2: Formulario de login ── -->
    <div x-show="step === 'login'" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4">

        <div class="glass rounded-3xl shadow-2xl shadow-black/30 overflow-hidden">

            <!-- Header con rol seleccionado -->
            <div class="relative px-8 pt-7 pb-5 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-blue-600/8 to-transparent"></div>
                <div class="relative flex items-center gap-4">
                    <!-- Volver -->
                    <button type="button" @click="step = 'roles'"
                            class="flex-shrink-0 w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </button>

                    <!-- Logo pequeño -->
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto shrink-0"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div style="display:none" class="shrink-0 w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl items-center justify-center shadow-lg shadow-blue-500/25">
                        <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-sm leading-tight">Colegio Pre JEDSON</p>
                        <p class="text-slate-400 text-xs">Iniciar sesión</p>
                    </div>

                    <!-- Badge del rol -->
                    <template x-if="current">
                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/5 border border-white/10"
                              :class="current.text">
                            <i class="fa-solid" :class="current.icon"></i>
                            <span x-text="current.label"></span>
                        </span>
                    </template>
                </div>
            </div>

            <!-- Formulario -->
            <div class="px-8 pb-8">
                <form action="{{ route('login.post') }}" method="POST"
                      @submit="loading = true">
                    @csrf
                    <input type="hidden" name="role" :value="selectedRole">

                    @if($errors->any())
                        <div class="mb-5 flex items-start gap-2.5 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3">
                            <i class="fa-solid fa-circle-exclamation text-red-400 mt-0.5 flex-shrink-0"></i>
                            <div class="text-xs text-red-300 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- DNI o Correo -->
                    <div class="mb-4">
                        <label for="identifier" class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">
                            DNI o Correo Electrónico
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">
                                <i class="fa-solid fa-id-card text-sm"></i>
                            </span>
                            <input type="text" id="identifier" name="identifier"
                                   value="{{ old('identifier') }}"
                                   autocomplete="username"
                                   class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition input-glow {{ $errors->has('identifier') ? 'border-red-500/40 bg-red-500/5' : '' }}"
                                   placeholder="12345678 ó correo@jedson.edu.pe"
                                   required autofocus>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-5">
                        <label for="password" class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">
                            Contraseña
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'"
                                   id="password" name="password"
                                   autocomplete="current-password"
                                   class="w-full pl-10 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition input-glow {{ $errors->has('password') ? 'border-red-500/40 bg-red-500/5' : '' }}"
                                   placeholder="••••••••" required>
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
                                <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center mb-6">
                        <input type="checkbox" id="remember" name="remember"
                               class="w-4 h-4 text-indigo-600 bg-white/5 border-white/20 rounded focus:ring-indigo-500 focus:ring-2">
                        <label for="remember" class="ml-2 text-sm text-slate-400">Recordarme</label>
                    </div>

                    <!-- Botón -->
                    <button type="submit" :disabled="loading"
                            class="w-full btn-shimmer hover:brightness-110 text-white font-bold py-3.5 px-6 rounded-xl transition-all flex items-center justify-center gap-2 disabled:opacity-50 shadow-lg shadow-indigo-500/20">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Ingresar al Sistema
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                Verificando...
                            </span>
                        </template>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-slate-600 text-xs mt-5">
            &copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Sistema de Gestión Escolar
        </p>
    </div>

</div>


</body>
</html>
