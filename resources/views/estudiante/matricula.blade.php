@extends('layouts.app')
@section('title', 'Mi Matrícula')
@section('page-title', 'Mi Matrícula')

@section('content')

@if(!$matricula)
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-12 text-center">
    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i class="fa-solid fa-file-signature text-slate-400 text-2xl"></i>
    </div>
    <p class="text-slate-500 font-semibold">Sin matrícula para {{ date('Y') }}</p>
    <p class="text-slate-400 text-sm mt-1">Comunícate con secretaría para más información.</p>
</div>
@else

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <!-- Datos de matrícula -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-file-signature text-violet-600"></i> Datos de matrícula
            </h3>
        </div>
        <div class="p-5 space-y-3">
            @php
            $rows = [
                ['Periodo',         $matricula->periodo],
                ['Nivel',           $alumno->nivel_academico],
                ['Grado / Sección', $alumno->grado_seccion],
                ['Sede',            $alumno->sede?->nombre ?? '—'],
                ['Estado',          $matricula->estado_pago],
                ['Monto matrícula', 'S/ ' . number_format($matricula->monto_matricula, 2)],
                ['Pensión mensual', 'S/ ' . number_format($matricula->pension_mensual, 2)],
            ];
            @endphp
            @foreach($rows as [$label, $value])
            <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                <span class="text-xs font-semibold text-slate-500">{{ $label }}</span>
                @if($label === 'Estado')
                <span class="text-xs font-bold px-2.5 py-1 rounded-lg
                    {{ $value === 'PAGADO' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $value }}
                </span>
                @else
                <span class="text-sm font-semibold text-slate-800">{{ $value }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Datos personales del alumno -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-user text-blue-600"></i> Datos del alumno
            </h3>
        </div>
        <div class="p-5 space-y-3">
            @php
            $rows2 = [
                ['Apellidos y nombres', $alumno->apellidos . ', ' . $alumno->nombres],
                ['DNI',                 $alumno->dni],
                ['Fecha de nac.',       $alumno?->fecha_nacimiento?->format('d/m/Y') ?? '—'],
                ['Sexo',                ucfirst(strtolower($alumno->sexo ?? '—'))],
                ['Ciudad',              $alumno->ciudad ?? '—'],
                ['Dirección',           $alumno->direccion ?? '—'],
            ];
            @endphp
            @foreach($rows2 as [$label, $value])
            <div class="flex items-start justify-between py-2 border-b border-slate-50 last:border-0 gap-3">
                <span class="text-xs font-semibold text-slate-500 shrink-0">{{ $label }}</span>
                <span class="text-sm font-semibold text-slate-800 text-right">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Apoderado -->
    @if($alumno->apoderado)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm lg:col-span-2">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-person-shelter text-teal-600"></i> Apoderado
            </h3>
        </div>
        <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-4">
            @php
            $rows3 = [
                ['Nombres',    $alumno->apoderado->nombres],
                ['Apellidos',  $alumno->apoderado->apellidos],
                ['Parentesco', ucfirst(strtolower($alumno->apoderado->parentesco ?? '—'))],
                ['DNI',        $alumno->apoderado->dni ?? '—'],
                ['Teléfono',   $alumno->apoderado->telefono ?? '—'],
                ['Email',      $alumno->apoderado->email ?? '—'],
            ];
            @endphp
            @foreach($rows3 as [$label, $value])
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ $label }}</p>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endif
@endsection
