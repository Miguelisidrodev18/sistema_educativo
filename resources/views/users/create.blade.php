@extends('layouts.app')

@section('title', 'Crear Usuario')
@section('page-title', 'Crear Usuario')

@section('content')

<div class="max-w-xl mx-auto" x-data="{ showPass: false, showConfirm: false }">

    <!-- Encabezado -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('users.index') }}"
           class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-500 hover:text-slate-800 transition shadow-sm">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-slate-800 font-bold">Nuevo usuario del sistema</h2>
            <p class="text-slate-500 text-xs">Completa los datos para crear la cuenta</p>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">

            <!-- Nombre completo -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Nombre completo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition {{ $errors->has('name') ? 'border-red-300 bg-red-50' : '' }}"
                       placeholder="Ej: Juan Carlos Mamani Torres">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- DNI + Email -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        DNI <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="dni" value="{{ old('dni') }}" maxlength="8" pattern="\d{8}" required
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition {{ $errors->has('dni') ? 'border-red-300 bg-red-50' : '' }}"
                           placeholder="12345678">
                    @error('dni')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition {{ $errors->has('email') ? 'border-red-300 bg-red-50' : '' }}"
                           placeholder="usuario@jedson.edu.pe">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Contraseña -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Contraseña <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                               class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition {{ $errors->has('password') ? 'border-red-300 bg-red-50' : '' }}"
                               placeholder="Mín. 8 caracteres">
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-xs"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Confirmar contraseña <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                               class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition"
                               placeholder="Repite la contraseña">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
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
                    <select name="user_type" required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition {{ $errors->has('user_type') ? 'border-red-300 bg-red-50' : '' }}">
                        <option value="">Seleccionar…</option>
                        @foreach(['administrador' => 'Administrador', 'auxiliar' => 'Auxiliar', 'docente' => 'Docente', 'estudiante' => 'Estudiante'] as $val => $label)
                            <option value="{{ $val }}" {{ old('user_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('user_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sede</label>
                    <select name="sede_id"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition">
                        <option value="">Sin sede asignada</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Estado -->
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <input type="checkbox" name="activo" value="1" id="activo"
                       {{ old('activo', '1') ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                <label for="activo" class="text-sm font-medium text-slate-700 cursor-pointer">
                    Cuenta activa al crear
                </label>
                <span class="ml-auto text-xs text-slate-400">Si no se marca, la cuenta queda desactivada</span>
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('users.index') }}"
                   class="flex-1 text-center py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 rounded-xl transition shadow-sm shadow-blue-500/20">
                    <i class="fa-solid fa-user-plus"></i>
                    Crear Usuario
                </button>
            </div>

        </div>
    </form>
</div>

@endsection
