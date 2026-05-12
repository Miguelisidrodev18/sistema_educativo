@extends('layouts.app')

@section('title', 'Panel')
@section('page-title', 'Panel')

@section('content')

<!-- ══════════════════════════════════════════════════════
     SELECTOR RÁPIDO DE SEDE
     ══════════════════════════════════════════════════════ -->
@if($sedes->count() > 0)
<div class="mb-6 fade-up">
    <div class="flex items-center gap-3 flex-wrap">
        <!-- "Todas las sedes" -->
        <form action="{{ route('sedes.seleccionar', 0) }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border transition
                    {{ !$sedeActiva ? 'bg-slate-800 text-white border-slate-800 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-400' }}">
                <i class="fa-solid fa-globe text-xs"></i>
                Todas las sedes
                @if(!$sedeActiva)
                    <span class="bg-white/20 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">✓</span>
                @endif
            </button>
        </form>

        @foreach($sedes as $sede)
        <form action="{{ route('sedes.seleccionar', $sede) }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border transition
                    {{ $sedeActiva?->id === $sede->id
                        ? 'bg-blue-700 text-white border-blue-700 shadow-sm shadow-blue-500/20'
                        : 'bg-white text-slate-600 border-slate-200 hover:border-blue-400 hover:text-blue-700' }}">
                <i class="fa-solid fa-building-columns text-xs"></i>
                {{ $sede->nombre }}
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none
                    {{ $sedeActiva?->id === $sede->id ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ $sede->alumnos_count }}
                </span>
                @if($sedeActiva?->id === $sede->id)
                    <span class="text-white/80 text-[10px]">✓</span>
                @endif
            </button>
        </form>
        @endforeach
    </div>

    @if($sedeActiva)
    <p class="text-xs text-slate-400 mt-2 ml-1">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Mostrando datos de <strong class="text-blue-600">{{ $sedeActiva->nombre }}</strong>.
        Haz clic en "Todas las sedes" para ver el total.
    </p>
    @endif
</div>
@endif

<!-- ══════════════════════════════════════════════════════
     TARJETAS DE ESTADÍSTICAS
     ══════════════════════════════════════════════════════ -->
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-6 fade-up-d1">

    <div class="bg-blue-600 rounded-2xl shadow-md shadow-blue-200 p-5 text-white">
        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fa-solid fa-users text-white text-sm"></i>
        </div>
        <p class="text-3xl font-black leading-none">{{ number_format($stats['total_alumnos']) }}</p>
        <p class="text-blue-100 text-[11px] font-semibold uppercase tracking-wide mt-1.5">Alumnos</p>
        <p class="text-blue-200 text-xs">Activos</p>
    </div>

    <div class="bg-violet-600 rounded-2xl shadow-md shadow-violet-200 p-5 text-white">
        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fa-solid fa-chalkboard-user text-white text-sm"></i>
        </div>
        <p class="text-3xl font-black leading-none">{{ number_format($stats['total_docentes']) }}</p>
        <p class="text-violet-100 text-[11px] font-semibold uppercase tracking-wide mt-1.5">Docentes</p>
        <p class="text-violet-200 text-xs">Personal activo</p>
    </div>

    <div class="bg-teal-600 rounded-2xl shadow-md shadow-teal-200 p-5 text-white">
        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fa-solid fa-file-signature text-white text-sm"></i>
        </div>
        <p class="text-3xl font-black leading-none">{{ number_format($stats['matriculas_anio']) }}</p>
        <p class="text-teal-100 text-[11px] font-semibold uppercase tracking-wide mt-1.5">Matrículas</p>
        <p class="text-teal-200 text-xs">{{ date('Y') }}</p>
    </div>

    <div class="bg-amber-500 rounded-2xl shadow-md shadow-amber-200 p-5 text-white">
        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fa-solid fa-receipt text-white text-sm"></i>
        </div>
        <p class="text-3xl font-black leading-none">{{ number_format($stats['pagos_hoy']) }}</p>
        <p class="text-amber-100 text-[11px] font-semibold uppercase tracking-wide mt-1.5">Pagos Hoy</p>
        <p class="text-amber-200 text-xs">Pensiones</p>
    </div>

    <div class="bg-emerald-600 rounded-2xl shadow-md shadow-emerald-200 p-5 text-white">
        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fa-solid fa-money-bill-wave text-white text-sm"></i>
        </div>
        <p class="text-xl font-black leading-none">S/ {{ number_format($stats['recaudado_mes'], 0) }}</p>
        <p class="text-emerald-100 text-[11px] font-semibold uppercase tracking-wide mt-1.5">Recaudado</p>
        <p class="text-emerald-200 text-xs">{{ now()->locale('es')->isoFormat('MMM YYYY') }}</p>
    </div>

    <div class="bg-sky-500 rounded-2xl shadow-md shadow-sky-200 p-5 text-white">
        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center mb-3">
            <i class="fa-solid fa-clipboard-check text-white text-sm"></i>
        </div>
        <p class="text-3xl font-black leading-none">{{ number_format($stats['asistencia_hoy']) }}</p>
        <p class="text-sky-100 text-[11px] font-semibold uppercase tracking-wide mt-1.5">Presentes</p>
        <p class="text-sky-200 text-xs">Hoy</p>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════
     DISTRIBUCIÓN POR NIVEL + ACCIONES RÁPIDAS + INFO
     ══════════════════════════════════════════════════════ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up-d2">

    <!-- Distribución por Nivel/Grado -->
    <div class="lg:col-span-2 space-y-4">

        @if($distribucion->count() > 0)

            @php
                $colores = [
                    'INICIAL'    => ['bg' => 'bg-pink-50',   'border' => 'border-pink-200',  'badge' => 'bg-pink-100 text-pink-700',   'icon' => 'text-pink-500',   'bar' => 'bg-pink-400'],
                    'PRIMARIA'   => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',  'badge' => 'bg-blue-100 text-blue-700',   'icon' => 'text-blue-500',   'bar' => 'bg-blue-500'],
                    'SECUNDARIA' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200','badge' => 'bg-purple-100 text-purple-700','icon' => 'text-purple-500', 'bar' => 'bg-purple-500'],
                ];
                $maxTotal = $distribucion->flatten()->max('total') ?: 1;
            @endphp

            @foreach(['PRIMARIA', 'SECUNDARIA', 'INICIAL'] as $nivel)
                @if($distribucion->has($nivel))
                @php
                    $c      = $colores[$nivel];
                    $grados = $distribucion[$nivel];
                    $total  = $grados->sum('total');
                @endphp
                <div class="bg-white rounded-2xl border {{ $c['border'] }} shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3.5 {{ $c['bg'] }} border-b {{ $c['border'] }}">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid {{ $nivel === 'INICIAL' ? 'fa-child-reaching' : ($nivel === 'PRIMARIA' ? 'fa-book-open' : 'fa-graduation-cap') }} {{ $c['icon'] }} text-sm"></i>
                            <span class="font-bold text-slate-700 text-sm">{{ $nivel }}</span>
                        </div>
                        <span class="{{ $c['badge'] }} text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $total }} alumno{{ $total !== 1 ? 's' : '' }}
                        </span>
                    </div>
                    <div class="px-5 py-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($grados as $g)
                        <div class="flex items-center gap-2.5">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-xs font-semibold text-slate-700 truncate">{{ $g->grado_seccion }}</span>
                                    <span class="text-xs font-bold text-slate-500 ml-2 shrink-0">{{ $g->total }}</span>
                                </div>
                                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="{{ $c['bar'] }} h-full rounded-full transition-all"
                                         style="width: {{ ($g->total / $maxTotal) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach

        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-chart-bar text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-semibold text-sm">Sin datos de alumnos</p>
                <p class="text-slate-400 text-xs mt-1">
                    @if($sedeActiva)
                        No hay alumnos registrados en {{ $sedeActiva->nombre }}.
                    @else
                        No hay alumnos activos registrados aún.
                    @endif
                </p>
                <a href="{{ route('alumnos.create') }}"
                   class="inline-flex items-center gap-2 mt-4 text-sm text-blue-700 hover:underline font-semibold">
                    <i class="fa-solid fa-user-plus text-xs"></i> Registrar primer alumno
                </a>
            </div>
        @endif

    </div>

    <!-- Acciones Rápidas + Info -->
    <div class="space-y-4">

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-slate-700 font-bold text-sm mb-3 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-yellow-500 text-xs"></i> Acciones Rápidas
            </h3>
            <div class="space-y-1">
                <a href="{{ route('alumnos.create') }}"
                   class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-blue-50 text-slate-600 hover:text-blue-700 transition group text-sm">
                    <div class="w-7 h-7 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user-plus text-blue-600 text-xs"></i>
                    </div>
                    Nuevo Alumno
                    <i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 group-hover:text-blue-400"></i>
                </a>
                <a href="{{ route('matriculas.create') }}"
                   class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-green-50 text-slate-600 hover:text-green-700 transition group text-sm">
                    <div class="w-7 h-7 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-circle-plus text-green-600 text-xs"></i>
                    </div>
                    Nueva Matrícula
                    <i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 group-hover:text-green-400"></i>
                </a>
                <a href="{{ route('pagos.index') }}"
                   class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition group text-sm">
                    <div class="w-7 h-7 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-money-bill text-emerald-600 text-xs"></i>
                    </div>
                    Registrar Pago
                    <i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 group-hover:text-emerald-400"></i>
                </a>
                <a href="{{ route('asistencias.index') }}"
                   class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-sky-50 text-slate-600 hover:text-sky-700 transition group text-sm">
                    <div class="w-7 h-7 bg-sky-100 group-hover:bg-sky-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clipboard-list text-sky-600 text-xs"></i>
                    </div>
                    Tomar Asistencia
                    <i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 group-hover:text-sky-400"></i>
                </a>
                <a href="{{ route('asistencias.qr') }}"
                   class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-purple-50 text-slate-600 hover:text-purple-700 transition group text-sm">
                    <div class="w-7 h-7 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-qrcode text-purple-600 text-xs"></i>
                    </div>
                    Lector QR
                    <i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 group-hover:text-purple-400"></i>
                </a>
                <a href="{{ route('matriculas.plantilla', 'primaria') }}"
                   class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-teal-50 text-slate-600 hover:text-teal-700 transition group text-sm">
                    <div class="w-7 h-7 bg-teal-100 group-hover:bg-teal-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-excel text-teal-600 text-xs"></i>
                    </div>
                    Plantilla Excel
                    <i class="fa-solid fa-download ml-auto text-[10px] text-slate-300 group-hover:text-teal-400"></i>
                </a>
            </div>
        </div>

        <!-- Info del colegio -->
        <div style="background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 60%, #312e81 100%);" class="rounded-2xl p-5 text-white shadow-lg shadow-blue-900/20">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-school text-white text-base"></i>
                </div>
                <div>
                    <p class="text-white font-extrabold text-sm leading-tight">Colegio Pre JEDSON</p>
                    <p class="text-blue-300 text-[11px]">Arequipa, Perú</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="text-blue-300 text-[10px] uppercase tracking-wide">Año</p>
                    <p class="text-white text-xl font-extrabold">{{ date('Y') }}</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="text-blue-300 text-[10px] uppercase tracking-wide">Sede</p>
                    <p class="text-white text-xs font-bold leading-tight mt-0.5">
                        {{ $sedeActiva?->nombre ?? 'Todas' }}
                    </p>
                </div>
            </div>

            <div class="bg-white/10 rounded-xl p-3">
                <p class="text-blue-300 text-[10px] uppercase tracking-wide mb-1">Sesión</p>
                <p class="text-white text-sm font-bold leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-blue-300 text-xs capitalize">{{ auth()->user()->user_type }}</p>
            </div>
        </div>

    </div>

</div>

@endsection
