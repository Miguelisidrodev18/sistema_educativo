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
@keyframes fadeUp{from{opacity:0;transform:translateY(25px)}to{opacity:1;transform:translateY(0)}}
@keyframes float{0%,100%{transform:translateY(0px)}50%{transform:translateY(-15px)}}
@keyframes float2{0%,100%{transform:translateY(0px) rotate(0deg)}50%{transform:translateY(-10px) rotate(5deg)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
@keyframes orbit{0%{transform:rotate(0deg) translateX(120px) rotate(0deg)}100%{transform:rotate(360deg) translateX(120px) rotate(-360deg)}}
@keyframes pulse-ring{0%{transform:scale(0.9);opacity:0.8}50%{transform:scale(1.1);opacity:0.3}100%{transform:scale(0.9);opacity:0.8}}
.fade-up{animation:fadeUp 0.6s ease both}
.fade-up-d1{animation:fadeUp 0.6s 0.1s ease both}
.fade-up-d2{animation:fadeUp 0.6s 0.2s ease both}
.fade-up-d3{animation:fadeUp 0.6s 0.3s ease both}
.fade-up-d4{animation:fadeUp 0.6s 0.4s ease both}
.floating{animation:float 4s ease-in-out infinite}
.floating2{animation:float2 5s ease-in-out infinite}
.orbit{animation:orbit 20s linear infinite}
.pulse-ring{animation:pulse-ring 3s ease-in-out infinite}
.glass-card{background:rgba(255,255,255,0.06);backdrop-filter:blur(24px);border:1px solid rgba(255,255,255,0.1)}
.input-glow:focus{box-shadow:0 0 0 3px rgba(99,102,241,0.3)}
.btn-shimmer{background-size:200% 100%;animation:shimmer 3s linear infinite;background-image:linear-gradient(90deg,#4f46e5 0%,#7c3aed 25%,#6366f1 50%,#7c3aed 75%,#4f46e5 100%)}
.login-bg{background:linear-gradient(135deg,#0c0f1a 0%,#111827 30%,#1e1b4b 60%,#0f172a 100%)}
</style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

<!-- Animated background elements -->
<div class="absolute inset-0 overflow-hidden pointer-events-none">
<div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl floating"></div>
<div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl floating2"></div>
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
<div class="w-3 h-3 bg-blue-400/30 rounded-full orbit"></div>
</div>
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
<div class="w-2 h-2 bg-indigo-400/20 rounded-full orbit" style="animation-duration:30s;animation-direction:reverse"></div>
</div>
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-60 h-60 border border-white/5 rounded-full pulse-ring"></div>
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-white/[0.03] rounded-full pulse-ring" style="animation-delay:1s"></div>
<!-- Grid pattern -->
<div class="absolute inset-0" style="background-image:radial-gradient(rgba(255,255,255,0.03) 1px,transparent 1px);background-size:40px 40px"></div>
</div>

<div class="w-full max-w-md relative z-10">

<!-- Back to landing -->
<a href="{{ route('landing') }}" class="fade-up inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm mb-6 transition group">
<i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Volver al inicio
</a>

<!-- Card -->
<div class="glass-card rounded-3xl shadow-2xl shadow-black/30 overflow-hidden">

<!-- Header -->
<div class="relative px-8 pt-8 pb-6 text-center overflow-hidden">
<div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 to-transparent"></div>
<div class="relative">
<div class="fade-up inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-4 shadow-lg shadow-blue-500/30 floating" style="animation-duration:3s">
<i class="fa-solid fa-graduation-cap text-white text-2xl"></i>
</div>
<h1 class="fade-up-d1 text-white text-2xl font-extrabold tracking-tight">Colegio Pre JEDSON</h1>
<p class="fade-up-d2 text-slate-400 text-sm mt-1">Arequipa &bull; Perú &bull; Sistema de Gestión</p>
</div>
</div>

<!-- Form -->
<div class="px-8 pb-8">
<h2 class="fade-up-d2 text-white text-lg font-bold mb-6 text-center">Iniciar Sesión</h2>

<form action="{{ route('login.post') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
@csrf

<!-- Email -->
<div class="mb-5 fade-up-d2">
<label for="email" class="block text-sm font-medium text-slate-300 mb-2">
<i class="fa-regular fa-envelope mr-1.5 text-slate-500"></i>Correo Electrónico
</label>
<input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all input-glow {{ $errors->has('email') ? 'border-red-500/50 bg-red-500/5' : '' }}"
placeholder="usuario@jedson.edu.pe" required>
@error('email')
<p class="mt-1.5 text-xs text-red-400 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
@enderror
</div>

<!-- Password -->
<div class="mb-5 fade-up-d3" x-data="{ showPass: false }">
<label for="password" class="block text-sm font-medium text-slate-300 mb-2">
<i class="fa-solid fa-lock mr-1.5 text-slate-500"></i>Contraseña
</label>
<div class="relative">
<input :type="showPass ? 'text' : 'password'" id="password" name="password" autocomplete="current-password"
class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all input-glow pr-12 {{ $errors->has('password') ? 'border-red-500/50 bg-red-500/5' : '' }}"
placeholder="••••••••" required>
<button type="button" @click="showPass = !showPass"
class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
<i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
</button>
</div>
@error('password')
<p class="mt-1.5 text-xs text-red-400 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
@enderror
</div>

<!-- Remember -->
<div class="flex items-center mb-6 fade-up-d3">
<input type="checkbox" id="remember" name="remember"
class="w-4 h-4 text-indigo-600 bg-white/5 border-white/20 rounded focus:ring-indigo-500 focus:ring-2">
<label for="remember" class="ml-2 text-sm text-slate-400">Recordarme</label>
</div>

<!-- Submit -->
<button type="submit" :disabled="loading"
class="fade-up-d4 w-full btn-shimmer hover:brightness-110 text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
<span x-show="!loading"><i class="fa-solid fa-right-to-bracket mr-2"></i>Ingresar al Sistema</span>
<span x-show="loading" class="flex items-center gap-2"><i class="fa-solid fa-spinner fa-spin"></i>Verificando...</span>
</button>
</form>
</div>

</div>

<p class="fade-up-d4 text-center text-slate-600 text-xs mt-6">
&copy; {{ date('Y') }} Colegio Pre JEDSON &mdash; Sistema de Gestión Escolar
</p>

</div>
</body>
</html>
