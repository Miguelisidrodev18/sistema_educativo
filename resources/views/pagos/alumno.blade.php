@extends('layouts.app')

@section('title', 'Pagos - ' . $alumno->nombre_completo)
@section('page-title', 'Gestión de Pagos')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('pagos.index') }}" class="text-slate-500 hover:text-blue-700 text-sm flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Volver a Pagos
    </a>
    <a href="{{ route('alumnos.show', $alumno) }}" class="text-blue-700 hover:underline text-sm">
        Ver perfil completo
    </a>
</div>

<!-- Alumno info -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center gap-4">
        @if($alumno->foto_path)
            <img src="{{ Storage::url($alumno->foto_path) }}" alt=""
                 class="w-16 h-16 rounded-full object-cover border-2 border-blue-100">
        @else
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <span class="text-blue-700 text-xl font-bold">
                    {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidos, 0, 1) }}
                </span>
            </div>
        @endif
        <div class="flex-1">
            <h2 class="text-slate-800 font-bold text-lg">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</h2>
            <p class="text-slate-500 text-sm">DNI: <span class="font-mono">{{ $alumno->dni }}</span>
                @if($alumno->nivel_academico)
                    &bull; {{ $alumno->nivel_academico }} &bull; {{ $alumno->grado_seccion }}
                @endif
                @if($alumno->sede)
                    &bull; {{ $alumno->sede->nombre }}
                @endif
            </p>
        </div>
        <div class="text-right">
            <p class="text-slate-400 text-xs">Año</p>
            <p class="text-slate-800 font-bold text-2xl">{{ $anio }}</p>
        </div>
    </div>

    @if($alumno->tipo_descuento !== 'ninguno' && $alumno->monto_descuento > 0)
        <div class="mt-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2">
            <i class="fa-solid fa-tag text-emerald-600"></i>
            <span class="text-emerald-800 text-sm">
                Descuento {{ $alumno->tipo_descuento }}: <strong>S/ {{ number_format($alumno->monto_descuento, 2) }}</strong>
                @if($alumno->descripcion_descuento) &mdash; {{ $alumno->descripcion_descuento }} @endif
            </span>
        </div>
    @endif
</div>

<!-- Year selector -->
<div class="flex items-center gap-3 mb-6">
    @foreach([date('Y') - 1, date('Y'), date('Y') + 1] as $y)
        <a href="{{ route('pagos.alumno', $alumno) }}?anio={{ $y }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition
                  {{ $anio == $y ? 'bg-blue-700 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            {{ $y }}
        </a>
    @endforeach
</div>

<!-- Month Grid -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-6" x-data="{ openModal: false, selectedMes: '', monto: 0 }">

    @foreach($meses as $mes)
        @php
            $pago = $pagos[$mes] ?? null;
        @endphp
        <div class="bg-white rounded-xl shadow-sm border {{ $pago ? 'border-green-300 bg-green-50' : 'border-slate-200' }} p-4">
            <div class="flex items-start justify-between mb-3">
                <h4 class="font-bold text-slate-700 text-sm">{{ $mes }}</h4>
                @if($pago)
                    <span class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-white text-xs"></i>
                    </span>
                @else
                    <span class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-minus text-slate-400 text-xs"></i>
                    </span>
                @endif
            </div>

            @if($pago)
                <p class="text-green-700 font-bold text-lg">S/ {{ number_format($pago->monto, 2) }}</p>
                <p class="text-green-600 text-xs mt-1">{{ $pago->metodo_pago }}</p>
                <p class="text-slate-400 text-xs">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</p>
                @if($pago->numero_recibo)
                    <p class="text-slate-400 text-xs">Recibo: {{ $pago->numero_recibo }}</p>
                @endif
            @else
                <p class="text-slate-400 text-sm">Pendiente</p>
                <button
                    @click="openModal = true; selectedMes = '{{ $mes }}'"
                    class="mt-2 w-full bg-blue-700 hover:bg-blue-800 text-white text-xs font-semibold py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-plus mr-1"></i> Registrar
                </button>
            @endif
        </div>
    @endforeach

    <!-- Register Payment Modal -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="openModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.stop>
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-slate-800 font-bold text-lg">Registrar Pago</h3>
                    <p class="text-slate-500 text-sm" x-text="'Mes: ' + selectedMes + ' {{ $anio }}'"></p>
                </div>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('pagos.registrar', $alumno) }}" method="POST">
                @csrf
                <input type="hidden" name="mes_pagado" :value="selectedMes">
                <input type="hidden" name="anio" value="{{ $anio }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Monto (S/) <span class="text-red-500">*</span></label>
                        <input type="number" name="monto" step="0.01" min="0" required
                               placeholder="0.00"
                               class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Método de Pago <span class="text-red-500">*</span></label>
                        <select name="metodo_pago" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="TRANSFERENCIA">Transferencia</option>
                            <option value="YAPE">Yape</option>
                            <option value="PLIN">Plin</option>
                            <option value="DEPOSITO">Depósito Bancario</option>
                            <option value="TARJETA">Tarjeta</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">N° Recibo / Operación</label>
                        <input type="text" name="numero_recibo" maxlength="60"
                               class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Opcional">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Fecha de Pago <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_pago" required value="{{ date('Y-m-d') }}"
                               class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-xl transition">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Guardar Pago
                    </button>
                    <button type="button" @click="openModal = false"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-3 rounded-xl transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Summary -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    @php
        $totalPagado = $pagos->sum('monto');
        $mesesPagados = $pagos->count();
    @endphp
    <h3 class="text-slate-800 font-semibold text-base mb-4">Resumen {{ $anio }}</h3>
    <div class="grid grid-cols-3 gap-4">
        <div class="text-center p-4 bg-green-50 rounded-xl">
            <p class="text-2xl font-bold text-green-700">{{ $mesesPagados }}</p>
            <p class="text-green-600 text-xs font-medium mt-1">Meses Pagados</p>
        </div>
        <div class="text-center p-4 bg-yellow-50 rounded-xl">
            <p class="text-2xl font-bold text-yellow-700">{{ count($meses) - $mesesPagados }}</p>
            <p class="text-yellow-600 text-xs font-medium mt-1">Meses Pendientes</p>
        </div>
        <div class="text-center p-4 bg-blue-50 rounded-xl">
            <p class="text-2xl font-bold text-blue-700">S/ {{ number_format($totalPagado, 2) }}</p>
            <p class="text-blue-600 text-xs font-medium mt-1">Total Recaudado</p>
        </div>
    </div>
</div>

@endsection
