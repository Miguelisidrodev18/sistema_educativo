@extends('layouts.app')
@section('title', 'Mi Portal')
@section('page-title', 'Mi Portal')

@section('content')
@php
$nombre = $alumno ? ($alumno->nombres . ' ' . $alumno->apellidos) : auth()->user()->name;
$hora   = now()->hour;
$saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');
@endphp

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Bienvenida -->
<div style="background: linear-gradient(135deg,#1e3a5f,#2563eb,#4f46e5);" class="rounded-2xl p-5 text-white mb-5 shadow-lg">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 text-2xl font-black">
            {{ strtoupper(substr($nombre, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-blue-200 text-xs font-medium">{{ $saludo }},</p>
            <p class="text-white font-black text-lg leading-tight truncate">{{ $nombre }}</p>
            @if($alumno)
            <p class="text-blue-300 text-xs mt-0.5">
                {{ $alumno->nivel_academico }} — {{ $alumno->grado_seccion }}
                @if($alumno->sede) · {{ $alumno->sede->nombre }} @endif
            </p>
            @endif
        </div>
    </div>
</div>

@if($alumno)
<!-- Stats rápidas -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center mb-2">
            <i class="fa-solid fa-clipboard-check text-green-600"></i>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $stats['pctAsistencia'] ?? 0 }}%</p>
        <p class="text-xs text-slate-500 mt-0.5">Asistencia</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center mb-2">
            <i class="fa-solid fa-calendar-days text-blue-600"></i>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $stats['presentes'] ?? 0 }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Días presente</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <div class="w-9 h-9 bg-violet-100 rounded-xl flex items-center justify-center mb-2">
            <i class="fa-solid fa-file-signature text-violet-600"></i>
        </div>
        <p class="text-xs font-black text-slate-800 mt-1">
            {{ $stats['matricula'] ? 'MATRICULADO' : 'SIN MATRÍCULA' }}
        </p>
        <p class="text-xs text-slate-500 mt-0.5">Matrícula {{ date('Y') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center mb-2">
            <i class="fa-solid fa-money-bill-wave text-amber-600"></i>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $stats['pagosMes'] ?? 0 }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Pago(s) este mes</p>
    </div>
</div>
@endif

<!-- Menú rápido -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <a href="{{ route('estudiante.perfil') }}"
       class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-blue-300 hover:shadow-md transition group flex flex-col items-center text-center gap-3">
        <div class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-2xl flex items-center justify-center transition">
            <i class="fa-solid fa-user-circle text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="font-bold text-slate-800 text-sm">Mi Perfil</p>
            <p class="text-xs text-slate-400 mt-0.5">Datos y foto</p>
        </div>
    </a>
    <a href="{{ route('estudiante.matricula') }}"
       class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-violet-300 hover:shadow-md transition group flex flex-col items-center text-center gap-3">
        <div class="w-12 h-12 bg-violet-100 group-hover:bg-violet-200 rounded-2xl flex items-center justify-center transition">
            <i class="fa-solid fa-file-signature text-violet-600 text-xl"></i>
        </div>
        <div>
            <p class="font-bold text-slate-800 text-sm">Matrícula</p>
            <p class="text-xs text-slate-400 mt-0.5">Mi inscripción</p>
        </div>
    </a>
    <a href="{{ route('estudiante.pagos') }}"
       class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-amber-300 hover:shadow-md transition group flex flex-col items-center text-center gap-3">
        <div class="w-12 h-12 bg-amber-100 group-hover:bg-amber-200 rounded-2xl flex items-center justify-center transition">
            <i class="fa-solid fa-money-bill-wave text-amber-600 text-xl"></i>
        </div>
        <div>
            <p class="font-bold text-slate-800 text-sm">Mis Pagos</p>
            <p class="text-xs text-slate-400 mt-0.5">Pensiones</p>
        </div>
    </a>
    <a href="{{ route('estudiante.asistencias') }}"
       class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-green-300 hover:shadow-md transition group flex flex-col items-center text-center gap-3">
        <div class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-2xl flex items-center justify-center transition">
            <i class="fa-solid fa-clipboard-check text-green-600 text-xl"></i>
        </div>
        <div>
            <p class="font-bold text-slate-800 text-sm">Asistencias</p>
            <p class="text-xs text-slate-400 mt-0.5">Mi historial</p>
        </div>
    </a>
</div>

@endsection
