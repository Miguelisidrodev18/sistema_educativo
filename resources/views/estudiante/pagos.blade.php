@extends('layouts.app')
@section('title', 'Mis Pagos')
@section('page-title', 'Mis Pagos')

@section('content')

@if($matricula)
<!-- Tarjeta matrícula -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-5">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-signature text-violet-600"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-800">Matrícula {{ $matricula->periodo }}</p>
                <p class="text-xs text-slate-500">{{ $alumno->nivel_academico }} — {{ $alumno->grado_seccion }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-lg font-black text-slate-800">S/ {{ number_format($matricula->monto_matricula, 2) }}</p>
            <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg
                {{ $matricula->estado_pago === 'PAGADO' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                <i class="fa-solid {{ $matricula->estado_pago === 'PAGADO' ? 'fa-check' : 'fa-xmark' }} text-[10px]"></i>
                {{ $matricula->estado_pago }}
            </span>
        </div>
    </div>
    @if($matricula->pension_mensual)
    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-sm">
        <span class="text-slate-500 text-xs">Pensión mensual</span>
        <span class="font-bold text-slate-700">S/ {{ number_format($matricula->pension_mensual, 2) }}</span>
    </div>
    @endif
</div>
@endif

<!-- Pensiones por mes -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-money-bill-wave text-amber-500"></i>
            Pensiones {{ date('Y') }}
        </h3>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($mesesActivos as $idx => $mes)
            @php
                $nMes  = $mesInicio + $idx;
                $pago  = $pagosPorMes[$nMes] ?? null;
            @endphp
            <div class="rounded-xl border p-3 text-center
                {{ $pago ? 'bg-green-50 border-green-200' : 'bg-slate-50 border-slate-200' }}">
                <p class="text-xs font-bold uppercase tracking-wider
                    {{ $pago ? 'text-green-600' : 'text-slate-400' }}">{{ $mes }}</p>
                @if($pago)
                    <p class="text-sm font-black text-green-700 mt-1">S/ {{ number_format($pago->monto, 2) }}</p>
                    <div class="flex items-center justify-center gap-1 mt-1">
                        <i class="fa-solid fa-check-circle text-green-500 text-xs"></i>
                        <span class="text-[10px] text-green-600">Pagado</span>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m') }}</p>
                @else
                    <p class="text-xs text-slate-400 mt-2">—</p>
                    <div class="flex items-center justify-center gap-1 mt-1">
                        <i class="fa-solid fa-clock text-slate-300 text-xs"></i>
                        <span class="text-[10px] text-slate-400">Pendiente</span>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        @php
            $totalPagados = count(array_filter(range($mesInicio, $mesInicio + count($mesesActivos) - 1), fn($m) => isset($pagosPorMes[$m])));
            $totalMeses   = count($mesesActivos);
            $pct          = $totalMeses > 0 ? round($totalPagados / $totalMeses * 100) : 0;
        @endphp
        <div class="mt-5 pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                <span>{{ $totalPagados }} de {{ $totalMeses }} meses pagados</span>
                <span class="font-bold {{ $pct >= 100 ? 'text-green-600' : ($pct >= 50 ? 'text-blue-600' : 'text-amber-600') }}">{{ $pct }}%</span>
            </div>
            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all
                    {{ $pct >= 100 ? 'bg-green-500' : ($pct >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>
    </div>
</div>
@endsection
