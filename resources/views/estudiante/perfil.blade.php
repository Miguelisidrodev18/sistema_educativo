@extends('layouts.app')
@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Foto + info básica --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div style="background:linear-gradient(135deg,#1e3a5f,#2563eb);" class="h-16"></div>
            <div class="px-5 pb-5">
                <div class="-mt-8 mb-3 flex justify-center">
                    @if($alumno?->foto_path && Storage::disk('public')->exists($alumno->foto_path))
                        <img src="{{ Storage::url($alumno->foto_path) }}"
                             class="w-20 h-20 rounded-2xl border-4 border-white shadow-md object-cover" alt="Foto">
                    @else
                        <div class="w-20 h-20 rounded-2xl border-4 border-white shadow-md bg-blue-600 flex items-center justify-center text-white text-3xl font-black">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <p class="text-center font-bold text-slate-800 text-sm">{{ auth()->user()->name }}</p>
                @if($alumno)
                <p class="text-center text-xs text-slate-500 mt-0.5">{{ $alumno->nivel_academico }} · {{ $alumno->grado_seccion }}</p>
                @endif
                <div class="mt-4 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-id-card w-4 text-slate-400"></i>
                        DNI: <span class="font-mono font-semibold">{{ $alumno?->dni ?? auth()->user()->dni ?? '—' }}</span>
                    </div>
                    @if($alumno?->fecha_nacimiento)
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-cake-candles w-4 text-slate-400"></i>
                        {{ $alumno->fecha_nacimiento->format('d/m/Y') }} ({{ $alumno->edad }} años)
                    </div>
                    @endif
                    @if($alumno?->sede)
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-location-dot w-4 text-slate-400"></i>
                        {{ $alumno->sede->nombre }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Apoderado -->
        @if($alumno?->apoderado)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Apoderado</p>
            <p class="font-semibold text-slate-800 text-sm">{{ $alumno->apoderado->apellidos }}, {{ $alumno->apoderado->nombres }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ ucfirst(strtolower($alumno->apoderado->parentesco)) }}</p>
            @if($alumno->apoderado->telefono)
            <a href="tel:{{ $alumno->apoderado->telefono }}"
               class="inline-flex items-center gap-1.5 mt-2 text-xs text-blue-600 hover:underline">
                <i class="fa-solid fa-phone"></i> {{ $alumno->apoderado->telefono }}
            </a>
            @endif
        </div>
        @endif
    </div>

    {{-- Formulario de actualización --}}
    <div class="lg:col-span-2 space-y-4">

        <!-- Actualizar datos -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-blue-600"></i> Actualizar mis datos
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Puedes actualizar tu ciudad, dirección y foto</p>
            </div>
            <form action="{{ route('estudiante.perfil.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ciudad</label>
                        <input type="text" name="ciudad" value="{{ old('ciudad', $alumno?->ciudad) }}"
                               class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ej: Arequipa">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $alumno?->direccion) }}"
                               class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ej: Av. Los Álamos 123">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Foto de perfil</label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                    <p class="text-[11px] text-slate-400 mt-1">JPG, PNG o WEBP · máx. 3MB</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        <!-- Cambiar contraseña -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-amber-500"></i> Cambiar contraseña
                </h3>
            </div>
            <div class="p-5">
                <a href="{{ route('password.change') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <i class="fa-solid fa-key"></i> Ir a cambiar contraseña
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
