@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 col-span-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Total Alumnos</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($stats['total_alumnos']) }}</p>
                <p class="text-slate-400 text-xs mt-1">Activos</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-700"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 col-span-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Docentes</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($stats['total_docentes']) }}</p>
                <p class="text-slate-400 text-xs mt-1">Personal activo</p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-chalkboard-user text-purple-700"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 col-span-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Matrículas {{ date('Y') }}</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($stats['matriculas_anio']) }}</p>
                <p class="text-slate-400 text-xs mt-1">Este año</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-file-signature text-green-700"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 col-span-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Pagos Hoy</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($stats['pagos_hoy']) }}</p>
                <p class="text-slate-400 text-xs mt-1">Pensiones cobradas</p>
            </div>
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-receipt text-yellow-700"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 col-span-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Recaudado Mes</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">S/ {{ number_format($stats['recaudado_mes'], 2) }}</p>
                <p class="text-slate-400 text-xs mt-1">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</p>
            </div>
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-money-bill-wave text-emerald-700"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 col-span-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Presentes Hoy</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($stats['asistencia_hoy']) }}</p>
                <p class="text-slate-400 text-xs mt-1">Alumnos</p>
            </div>
            <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-clipboard-check text-sky-700"></i>
            </div>
        </div>
    </div>

</div>

<!-- Quick Actions + Info -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Quick Actions -->
    <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-slate-800 font-semibold text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bolt text-yellow-500"></i> Acciones Rápidas
        </h3>
        <div class="space-y-2">
            <a href="{{ route('alumnos.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 text-slate-700 hover:text-blue-700 transition group">
                <div class="w-8 h-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-blue-700 text-sm"></i>
                </div>
                <span class="text-sm font-medium">Nuevo Alumno</span>
                <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-400"></i>
            </a>
            <a href="{{ route('matriculas.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 text-slate-700 hover:text-green-700 transition group">
                <div class="w-8 h-8 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-file-circle-plus text-green-700 text-sm"></i>
                </div>
                <span class="text-sm font-medium">Nueva Matrícula</span>
                <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-400"></i>
            </a>
            <a href="{{ route('pagos.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition group">
                <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-money-bill text-emerald-700 text-sm"></i>
                </div>
                <span class="text-sm font-medium">Registrar Pago</span>
                <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-400"></i>
            </a>
            <a href="{{ route('asistencias.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-sky-50 text-slate-700 hover:text-sky-700 transition group">
                <div class="w-8 h-8 bg-sky-100 group-hover:bg-sky-200 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-list text-sky-700 text-sm"></i>
                </div>
                <span class="text-sm font-medium">Tomar Asistencia</span>
                <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-400"></i>
            </a>
            <a href="{{ route('asistencias.qr') }}"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 text-slate-700 hover:text-purple-700 transition group">
                <div class="w-8 h-8 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-qrcode text-purple-700 text-sm"></i>
                </div>
                <span class="text-sm font-medium">Lector QR</span>
                <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-400"></i>
            </a>
        </div>
    </div>

    <!-- School Info -->
    <div class="lg:col-span-2 bg-gradient-to-br from-blue-900 to-blue-700 rounded-xl p-6 text-white">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-school text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold">Colegio Pre JEDSON</h2>
                <p class="text-blue-200 text-sm mt-1">Sistema de Gestión Escolar &mdash; Arequipa, Perú</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-xs uppercase tracking-wide mb-1">Año Académico</p>
                <p class="text-white text-2xl font-bold">{{ date('Y') }}</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-blue-200 text-xs uppercase tracking-wide mb-1">Sesión</p>
                <p class="text-white font-bold">{{ auth()->user()->name }}</p>
                <p class="text-blue-200 text-xs capitalize">{{ auth()->user()->user_type }}</p>
            </div>
        </div>

        <div class="mt-4 bg-white/10 rounded-xl p-4">
            <p class="text-blue-200 text-xs uppercase tracking-wide mb-2">Fecha y Hora del Sistema</p>
            <p class="text-white font-bold text-lg">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            <p class="text-blue-200 text-sm">{{ now()->format('H:i:s') }}</p>
        </div>
    </div>

</div>

@endsection
