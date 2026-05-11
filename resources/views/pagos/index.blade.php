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

<!-- Table: Alumnos with month payment status -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Alumno</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nivel</th>
                    @foreach($meses as $mes)
                        <th class="text-center px-2 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide min-w-[52px]">
                            {{ substr($mes, 0, 3) }}
                        </th>
                    @endforeach
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($alumnos as $alumno)
                    @php
                        $pagosAlumno = $alumno->pagosPension->keyBy('mes_pagado');
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 min-w-[180px]">
                            <p class="font-semibold text-slate-800 text-xs">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</p>
                            <p class="text-slate-400 text-xs font-mono">{{ $alumno->dni }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($alumno->nivel_academico)
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $alumno->nivel_academico === 'INICIAL' ? 'bg-pink-100 text-pink-700' : ($alumno->nivel_academico === 'PRIMARIA' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                    {{ substr($alumno->nivel_academico, 0, 3) }}
                                </span>
                            @endif
                        </td>
                        @foreach($meses as $mes)
                            <td class="px-1 py-3 text-center">
                                @if(isset($pagosAlumno[$mes]))
                                    <span title="{{ $mes }} - S/ {{ number_format($pagosAlumno[$mes]->monto, 2) }}"
                                          class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-green-100 text-green-700 cursor-default">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </span>
                                @else
                                    <span title="{{ $mes }} - Pendiente"
                                          class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-slate-100 text-slate-400 cursor-default">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pagos.alumno', $alumno) }}"
                               class="inline-flex items-center gap-1 bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                <i class="fa-solid fa-hand-holding-dollar"></i> Gestionar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($meses) + 3 }}" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fa-solid fa-money-bill-slash text-4xl text-slate-300"></i>
                                <p class="text-slate-500 font-medium">No se encontraron alumnos</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alumnos->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $alumnos->links() }}
        </div>
    @endif
</div>

@endsection
