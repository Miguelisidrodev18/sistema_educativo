<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cambiar Contraseña — JEDSON</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4"
      style="background: linear-gradient(135deg,#e0e7ff 0%,#f0fdf4 50%,#fef9c3 100%);">

<div class="w-full max-w-md" x-data="{ show1: false, show2: false }">

    <!-- Logo / encabezado -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto mx-auto mb-3 drop-shadow" alt="Logo"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
        <div style="display:none" class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl items-center justify-center mx-auto mb-3 shadow-lg">
            <i class="fa-solid fa-graduation-cap text-white text-2xl"></i>
        </div>
        <h1 class="text-slate-800 font-black text-2xl">Colegio Pre <span class="text-blue-700">JEDSON</span></h1>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-slate-100">

        <!-- Alerta de primer login -->
        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6">
            <i class="fa-solid fa-shield-halved text-amber-500 mt-0.5 shrink-0"></i>
            <div>
                <p class="text-sm font-semibold text-amber-800">Cambio de contraseña requerido</p>
                <p class="text-xs text-amber-600 mt-0.5">
                    @if(session('info')){{ session('info') }}@else
                    Por seguridad debes crear tu propia contraseña antes de continuar.
                    @endif
                </p>
            </div>
        </div>

        @if($errors->any())
        <div class="flex items-start gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 shrink-0 text-sm"></i>
            <div class="text-xs text-red-600">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('password.change.update') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nueva contraseña</label>
                <div class="relative">
                    <input :type="show1 ? 'text' : 'password'" name="password" required
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 pr-11 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Mínimo 8 caracteres">
                    <button type="button" @click="show1 = !show1"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i :class="show1 ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Confirmar contraseña</label>
                <div class="relative">
                    <input :type="show2 ? 'text' : 'password'" name="password_confirmation" required
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 pr-11 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Repite la contraseña">
                    <button type="button" @click="show2 = !show2"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i :class="show2 ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm flex items-center justify-center gap-2 shadow-sm active:scale-[.98]">
                <i class="fa-solid fa-lock"></i> Guardar y continuar
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-slate-100 flex justify-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-slate-400 hover:text-red-500 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <p class="text-center text-[11px] text-slate-400 mt-6">© {{ date('Y') }} Colegio Pre JEDSON</p>
</div>
</body>
</html>
