@extends('layouts.app')

@section('title', 'Matrícula ' . $matricula->codigo_matricula)
@section('page-title', 'Detalle de Matrícula')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('matriculas.index') }}" class="text-slate-500 hover:text-blue-700 text-sm flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Volver a Matrículas
        </a>
        <a href="{{ route('alumnos.show', $matricula->alumno) }}" class="text-blue-700 hover:underline text-sm">
            Ver perfil del alumno
        </a>
    </div>

    <!-- Header card -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl p-6 text-white mb-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-blue-200 text-xs uppercase tracking-wide mb-1">Código de Matrícula</p>
                <h2 class="text-2xl font-extrabold font-mono">{{ $matricula->codigo_matricula }}</h2>
                <p class="text-blue-200 mt-1">{{ $matricula->alumno->apellidos }}, {{ $matricula->alumno->nombres }}</p>
                <p class="text-blue-300 text-xs mt-0.5">DNI: {{ $matricula->alumno->dni }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                    {{ $matricula->estado === 'activo' ? 'bg-green-400 text-green-900' : 'bg-slate-400 text-slate-900' }}">
                    {{ strtoupper($matricula->estado) }}
                </span>
                <p class="text-blue-200 text-xs mt-2">Período: {{ $matricula->periodo }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Datos académicos -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-slate-800 font-semibold text-base mb-4 flex items-center gap-2">
                <i class="fa-solid fa-book text-blue-700"></i> Datos Académicos
            </h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <dt class="text-slate-500">Nivel</dt>
                    <dd class="font-semibold">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $matricula->nivel_academico === 'INICIAL' ? 'bg-pink-100 text-pink-700' : ($matricula->nivel_academico === 'PRIMARIA' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                            {{ $matricula->nivel_academico }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <dt class="text-slate-500">Grado / Sección</dt>
                    <dd class="font-semibold text-slate-700">{{ $matricula->grado_seccion }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <dt class="text-slate-500">Situación</dt>
                    <dd class="font-semibold text-slate-700">{{ $matricula->situacion }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <dt class="text-slate-500">Modalidad Pago</dt>
                    <dd class="font-semibold text-slate-700 capitalize">{{ $matricula->modalidad_pago }}</dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-slate-500">Sede</dt>
                    <dd class="font-semibold text-slate-700">{{ $matricula->sede?->nombre ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Pago -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-slate-800 font-semibold text-base mb-4 flex items-center gap-2">
                <i class="fa-solid fa-money-bill text-green-600"></i> Información de Pago
            </h3>
            @if($matricula->pagoMatricula)
                @php
                    $pm = $matricula->pagoMatricula;
                    $epClass = match($pm->estado_pago) {
                        'PAGADO'   => 'bg-green-100 text-green-700',
                        'PENDIENTE'=> 'bg-yellow-100 text-yellow-700',
                        'VENCIDO'  => 'bg-red-100 text-red-700',
                        'PARCIAL'  => 'bg-blue-100 text-blue-700',
                        default    => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <dt class="text-slate-500">Monto Matrícula</dt>
                        <dd class="font-bold text-slate-800">S/ {{ number_format($pm->monto_matricula, 2) }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <dt class="text-slate-500">Pensión Mensual</dt>
                        <dd class="font-bold text-slate-800">S/ {{ number_format($pm->pension_mensual, 2) }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <dt class="text-slate-500">N° Pensiones</dt>
                        <dd class="font-semibold text-slate-700">{{ $pm->numero_pensiones }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <dt class="text-slate-500">Total Estimado</dt>
                        <dd class="font-bold text-blue-700">
                            S/ {{ number_format($pm->monto_matricula + ($pm->pension_mensual * $pm->numero_pensiones), 2) }}
                        </dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <dt class="text-slate-500">Estado Pago</dt>
                        <dd><span class="px-2 py-1 rounded-full text-xs font-bold {{ $epClass }}">{{ $pm->estado_pago }}</span></dd>
                    </div>
                    @if($pm->metodo_pago)
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <dt class="text-slate-500">Método</dt>
                            <dd class="font-semibold text-slate-700">{{ $pm->metodo_pago }}</dd>
                        </div>
                    @endif
                    @if($pm->fecha_pago)
                        <div class="flex justify-between py-2">
                            <dt class="text-slate-500">Fecha Pago</dt>
                            <dd class="font-semibold text-slate-700">{{ $pm->fecha_pago->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                </dl>
            @else
                <p class="text-slate-400 text-sm text-center py-6">Sin registro de pago</p>
            @endif
        </div>

    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('alumnos.ticket-pdf', $matricula->alumno) }}" target="_blank"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-file-pdf"></i> Ticket PDF
        </a>
        <a href="{{ route('pagos.alumno', $matricula->alumno) }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-money-bill"></i> Ver Pagos
        </a>
    </div>

</div>

@endsection
