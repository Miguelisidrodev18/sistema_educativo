@extends('layouts.app')
@section('title', 'Mi Asistencia')
@section('page-title', 'Mi Asistencia')

@section('content')

<!-- Filtro mes -->
<form method="GET" class="flex items-center gap-3 mb-5 flex-wrap">
    <select name="mes" onchange="this.form.submit()"
            class="border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $m)
        <option value="{{ $i+1 }}" {{ $mes == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
        @endforeach
    </select>
    <select name="anio" onchange="this.form.submit()"
            class="border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        @for($y = date('Y'); $y >= date('Y')-2; $y--)
        <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>
</form>

<!-- Resumen -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    @foreach([
        ['presente',    'Presente',    'bg-green-100 text-green-700',  'fa-check-circle'],
        ['tardanza',    'Tardanza',    'bg-yellow-100 text-yellow-700','fa-clock'],
        ['ausente',     'Ausente',     'bg-red-100 text-red-700',      'fa-xmark-circle'],
        ['justificado', 'Justificado', 'bg-blue-100 text-blue-700',    'fa-file-circle-check'],
    ] as [$key, $label, $cls, $icon])
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 text-center">
        <div class="w-9 h-9 rounded-xl {{ $cls }} flex items-center justify-center mx-auto mb-1.5">
            <i class="fa-solid {{ $icon }} text-sm"></i>
        </div>
        <p class="text-2xl font-black text-slate-800">{{ $resumen[$key] }}</p>
        <p class="text-xs text-slate-500">{{ $label }}</p>
    </div>
    @endforeach
</div>

<!-- Barra asistencia -->
@if($total > 0)
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-5">
    <div class="flex items-center justify-between text-sm mb-2">
        <span class="text-slate-600 font-medium">Asistencia efectiva</span>
        <span class="font-black {{ $pct >= 85 ? 'text-green-600' : ($pct >= 70 ? 'text-amber-600' : 'text-red-600') }}">{{ $pct }}%</span>
    </div>
    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full rounded-full {{ $pct >= 85 ? 'bg-green-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-red-500') }}"
             style="width: {{ $pct }}%"></div>
    </div>
    <p class="text-xs text-slate-400 mt-1.5">{{ $resumen['presente'] + $resumen['justificado'] }} de {{ $total }} días</p>
</div>
@endif

<!-- Lista de registros -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-blue-600"></i>
            Registros del mes ({{ $total }})
        </h3>
    </div>
    @if($registros->isNotEmpty())
    <div class="divide-y divide-slate-100">
        @foreach($registros as $reg)
        @php
            $cls = match($reg->estado) {
                'PRESENTE'    => ['bg-green-100 text-green-700',  'fa-check'],
                'TARDANZA'    => ['bg-yellow-100 text-yellow-700','fa-clock'],
                'AUSENTE'     => ['bg-red-100 text-red-700',      'fa-xmark'],
                'JUSTIFICADO' => ['bg-blue-100 text-blue-700',    'fa-file-check'],
                default       => ['bg-slate-100 text-slate-600',  'fa-circle'],
            };
        @endphp
        <div class="flex items-center gap-3 px-5 py-3">
            <div class="w-8 h-8 rounded-lg {{ $cls[0] }} flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $cls[1] }} text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800">
                    {{ \Carbon\Carbon::parse($reg->fecha)->locale('es')->isoFormat('dddd D') }}
                </p>
                <p class="text-xs text-slate-400">{{ $reg->hora_registro ?? 'Sin hora' }}</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $cls[0] }}">{{ $reg->estado }}</span>
        </div>
        @endforeach
    </div>
    @else
    <div class="px-5 py-12 text-center">
        <i class="fa-solid fa-calendar-xmark text-slate-300 text-3xl mb-2 block"></i>
        <p class="text-slate-400 text-sm">Sin registros este mes</p>
    </div>
    @endif
</div>
@endsection
