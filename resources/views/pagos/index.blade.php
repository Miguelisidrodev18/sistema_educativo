@extends('layouts.app')

@section('title', 'Pagos y Pensiones')
@section('page-title', 'Pagos y Pensiones')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Total Alumnos</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalAlumnos) }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-700"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Pagos Hoy</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($pagadosHoy) }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-receipt text-green-700"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">Recaudado {{ $anio }}</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">S/ {{ number_format($totalRecaudado, 2) }}</p>
            </div>
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-money-bill-wave text-emerald-700"></i>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
    <h2 class="text-slate-700 text-sm">{{ $alumnos->total() }} alumno(s)</h2>
    <div class="flex gap-2">
        <button x-data @click="$dispatch('open-import-pagos')"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-file-excel"></i> Importar Excel
        </button>
    </div>
</div>

<!-- Import Modal -->
<div x-data="{ open: false }" @open-import-pagos.window="open = true">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.stop>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-slate-800 font-bold text-lg">Importar Pagos</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form action="{{ route('pagos.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <p class="text-slate-500 text-sm mb-3">
                        Columnas requeridas:<br>
                        <code class="bg-slate-100 px-1 rounded text-xs">dni, mes_pagado, anio, monto, metodo_pago, numero_recibo, fecha_pago</code>
                    </p>
                    <input type="file" name="archivo" accept=".xlsx,.xls,.csv" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-xl transition">
                        <i class="fa-solid fa-upload mr-2"></i>Importar
                    </button>
                    <button type="button" @click="open = false"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 rounded-xl transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="{{ route('pagos.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">

        <div class="min-w-20">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Año</label>
            <input type="number" name="anio" value="{{ $anio }}" min="2020" max="2099"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Buscar alumno</label>
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Nombre, apellidos o DNI..."
                       class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="min-w-36">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Sede</label>
            <select name="sede_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todas</option>
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}" {{ request('sede_id') == $sede->id ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-32">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nivel</label>
            <select name="nivel" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="INICIAL"    {{ request('nivel') === 'INICIAL'    ? 'selected' : '' }}>Inicial</option>
                <option value="PRIMARIA"   {{ request('nivel') === 'PRIMARIA'   ? 'selected' : '' }}>Primaria</option>
                <option value="SECUNDARIA" {{ request('nivel') === 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-filter mr-1"></i>Filtrar
            </button>
            <a href="{{ route('pagos.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>
</div>

<!-- Meses activos info -->
<div class="flex items-center gap-2 mb-3 flex-wrap">
    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Meses activos:</span>
    @foreach($mesesActivos as $m)
        <span class="text-[10px] bg-teal-100 text-teal-700 font-bold px-2 py-0.5 rounded-full border border-teal-200">{{ substr($m,0,3) }}</span>
    @endforeach
    <a href="{{ route('configuracion.index') }}" class="text-[11px] text-blue-600 hover:underline ml-1">
        <i class="fa-solid fa-gear text-[9px] mr-0.5"></i>Configurar
    </a>
</div>

<!-- Table: Alumnos with progress bar + month status -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide min-w-45">Alumno</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide w-48">Progreso</th>
                    @foreach($mesesActivos as $mes)
                        <th class="text-center px-1.5 py-3 text-[10px] font-bold text-slate-400 uppercase min-w-9">
                            {{ substr($mes, 0, 3) }}
                        </th>
                    @endforeach
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($alumnos as $alumno)
                    @php
                        $pagosAlumno  = $alumno->pagosPension->keyBy('mes_pagado');
                        $mesesPagados = $pagosAlumno->count();
                        $pct          = $totalMeses > 0 ? round(($mesesPagados / $totalMeses) * 100) : 0;
                        $colorBar     = $pct >= 100 ? 'bg-green-500' : ($pct >= 50 ? 'bg-blue-500' : ($pct > 0 ? 'bg-yellow-400' : 'bg-slate-200'));
                        $colorText    = $pct >= 100 ? 'text-green-700' : ($pct >= 50 ? 'text-blue-700' : ($pct > 0 ? 'text-yellow-700' : 'text-slate-400'));
                    @endphp
                    <tr class="hover:bg-blue-50/20 transition group">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-800 leading-tight">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="font-mono text-slate-400 text-[10px]">{{ $alumno->dni }}</span>
                                @if($alumno->nivel_academico)
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold
                                        {{ $alumno->nivel_academico === 'INICIAL' ? 'bg-pink-100 text-pink-700' : ($alumno->nivel_academico === 'PRIMARIA' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                        {{ substr($alumno->nivel_academico,0,3) }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- ── BARRA DE PROGRESO ── --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="{{ $colorBar }} h-full rounded-full transition-all"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold {{ $colorText }} shrink-0 w-12 text-right">
                                    {{ $mesesPagados }}/{{ $totalMeses }}
                                </span>
                            </div>
                            @if($mesesPagados > 0)
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                S/ {{ number_format($pagosAlumno->sum('monto'), 2) }} recaudado
                            </p>
                            @endif
                        </td>

                        {{-- ── CHIPS DE MESES ── --}}
                        @foreach($mesesActivos as $mes)
                            <td class="px-1 py-3 text-center">
                                @if(isset($pagosAlumno[$mes]))
                                    <span title="S/ {{ number_format($pagosAlumno[$mes]->monto, 2) }}"
                                          class="inline-flex w-6 h-6 items-center justify-center rounded-full bg-green-100 text-green-600">
                                        <i class="fa-solid fa-check" style="font-size:8px"></i>
                                    </span>
                                @else
                                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-full bg-slate-100 text-slate-300">
                                        <i class="fa-solid fa-minus" style="font-size:8px"></i>
                                    </span>
                                @endif
                            </td>
                        @endforeach

                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pagos.alumno', $alumno) }}?anio={{ $anio }}"
                               class="inline-flex items-center gap-1 bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition opacity-80 group-hover:opacity-100">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($mesesActivos) + 3 }}" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i class="fa-solid fa-money-bill-slash text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 font-semibold">No se encontraron alumnos</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alumnos->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">{{ $alumnos->firstItem() }}–{{ $alumnos->lastItem() }} de {{ $alumnos->total() }}</p>
            {{ $alumnos->links() }}
        </div>
    @endif
</div>

@endsection
