@extends('layouts.app')

@section('title', 'Crear Usuario')
@section('page-title', 'Crear Usuario')

@section('content')

<div class="max-w-xl mx-auto"
     x-data="{
        step: {{ $errors->has('master_key') || ($errors->any() && old('master_key')) ? 1 : ($errors->any() ? 2 : 1) }},
        masterOk: {{ $errors->any() && !$errors->has('master_key') && old('master_key') ? 'true' : 'false' }},
        showMaster: false,
        showPass: false,
        showConfirm: false
     }">

    <!-- Encabezado -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('users.index') }}"
           class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-500 hover:text-slate-800 transition shadow-sm">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-slate-800 font-bold">Nuevo usuario del sistema</h2>
            <p class="text-slate-500 text-xs">Se requiere clave maestra para continuar</p>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="POST" @submit.prevent="step === 1 ? (masterOk = true, step = 2) : $el.submit()">
        @csrf

        <!-- ── PASO 1: Clave maestra ── -->
        <div x-show="step === 1"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-key text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold">Verificación requerida</p>
                            <p class="text-blue-200 text-xs">Ingresa la clave maestra del sistema</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    @error('master_key')
                        <div class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
                            <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Clave Maestra
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input :type="showMaster ? 'text' : 'password'"
                               name="master_key"
                               value="{{ old('master_key') }}"
                               class="w-full pl-10 pr-12 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="••••••••••••"
                               required autofocus>
                        <button type="button" @click="showMaster = !showMaster"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <i :class="showMaster ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-slate-400">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Esta clave está configurada por el administrador del servidor.
                    </p>
                </div>

                <div class="px-6 pb-6">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm">
                        <i class="fa-solid fa-arrow-right"></i>
                        Verificar y continuar
                    </button>
                </div>
            </div>
        </div>

        <!-- ── PASO 2: Datos del usuario ── -->
        <div x-show="step === 2" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0">

            @if($errors->hasAny(['name','dni','email','password','user_type','sede_id']))
                <div class="mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                    <div class="text-sm text-red-700 space-y-0.5">
                        @foreach($errors->all() as $e)
                            @if(!str_contains($e, 'maestra'))
                                <p>{{ $e }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

                <!-- Verificado badge -->
                <div class="flex items-center gap-2 px-6 py-4 border-b border-slate-100 bg-green-50">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span class="text-sm font-semibold text-green-700">Clave maestra verificada</span>
                    <button type="button" @click="step = 1; masterOk = false"
                            class="ml-auto text-xs text-slate-400 hover:text-slate-600 underline">
                        Cambiar
                    </button>
                </div>

                <div class="p-6 space-y-5">

                    <!-- Nombre completo -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nombre completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('name') ? 'border-red-300 bg-red-50' : '' }}"
                               placeholder="Ej: Juan Carlos Mamani Torres" required>
                    </div>

                    <!-- DNI + Email en grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                DNI <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dni" value="{{ old('dni') }}"
                                   maxlength="8" pattern="\d{8}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('dni') ? 'border-red-300 bg-red-50' : '' }}"
                                   placeholder="12345678" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('email') ? 'border-red-300 bg-red-50' : '' }}"
                                   placeholder="usuario@jedson.edu.pe" required>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showPass ? 'text' : 'password'" name="password"
                                       class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('password') ? 'border-red-300 bg-red-50' : '' }}"
                                       placeholder="Mín. 8 caracteres" required>
                                <button type="button" @click="showPass = !showPass"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Confirmar contraseña <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                                       class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="Repite la contraseña" required>
                                <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i :class="showConfirm ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Perfil + Sede -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Perfil / Rol <span class="text-red-500">*</span>
                            </label>
                            <select name="user_type"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white {{ $errors->has('user_type') ? 'border-red-300 bg-red-50' : '' }}"
                                    required>
                                <option value="">Seleccionar…</option>
                                @foreach(['administrador' => 'Administrador', 'auxiliar' => 'Auxiliar', 'docente' => 'Docente', 'estudiante' => 'Estudiante'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('user_type') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sede</label>
                            <select name="sede_id"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                                <option value="">Sin sede asignada</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>
                                        {{ $sede->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                        <input type="checkbox" name="activo" value="1" id="activo"
                               {{ old('activo', '1') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <label for="activo" class="text-sm font-medium text-slate-700">
                            Cuenta activa al crear
                        </label>
                        <span class="ml-auto text-xs text-slate-400">Si no se marca, la cuenta queda desactivada</span>
                    </div>

                </div>

                <!-- Footer del form -->
                <div class="px-6 pb-6 flex items-center gap-3">
                    <a href="{{ route('users.index') }}"
                       class="flex-1 text-center py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm rounded-xl transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm">
                        <i class="fa-solid fa-user-plus"></i>
                        Crear Usuario
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection
