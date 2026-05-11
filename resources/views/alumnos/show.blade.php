@extends('layouts.app')

@section('title', $alumno->nombre_completo)
@section('page-title', 'Perfil del Alumno')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('alumnos.index') }}" class="text-slate-500 hover:text-blue-700 text-sm flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Volver a Alumnos
    </a>
    <div class="flex gap-2">
        <a href="{{ route('alumnos.edit', $alumno) }}"
           class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg px-3 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-pen"></i> Editar
        </a>
        <a href="{{ route('alumnos.ticket-pdf', $alumno) }}" target="_blank"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-ticket"></i> Ticket
        </a>
        <a href="{{ route('alumnos.constancia-pdf', $alumno) }}" target="_blank"
           class="inline-flex items-center gap-2 bg-slate-700 hover:bg-slate-800 text-white rounded-lg px-3 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-file-pdf"></i> Constancia
        </a>
        <a href="{{ route('pagos.alumno', $alumno) }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white rounded-lg px-3 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-money-bill"></i> Pagos
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left column: Personal info -->
    <div class="space-y-6">

        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
            @if($alumno->foto_path)
                <img src="{{ Storage::url($alumno->foto_path) }}" alt="{{ $alumno->nombre_completo }}"
                     class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-blue-100">
            @else
                <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mx-auto border-4 border-blue-50">
                    <span class="text-blue-700 text-3xl font-bold">
                        {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidos, 0, 1) }}
                    </span>
                </div>
            @endif

            <h2 class="text-slate-800 font-bold text-lg mt-3">{{ $alumno->apellidos }},<br>{{ $alumno->nombres }}</h2>
            <p class="text-slate-500 text-sm mt-1 font-mono">DNI: {{ $alumno->dni }}</p>

            <div class="mt-3 flex flex-wrap justify-center gap-2">
                @if($alumno->nivel_academico)
                    <span class="text-xs font-medium px-3 py-1 rounded-full
                        {{ $alumno->nivel_academico === 'INICIAL' ? 'bg-pink-100 text-pink-700' : ($alumno->nivel_academico === 'PRIMARIA' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ $alumno->nivel_academico }}
                    </span>
                @endif
                @if($alumno->grado_seccion)
                    <span class="text-xs font-medium bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                        {{ $alumno->grado_seccion }}
                    </span>
                @endif
                <span class="text-xs font-medium px-3 py-1 rounded-full {{ $alumno->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $alumno->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            @if($alumno->qr_code_path)
                <div class="mt-4">
                    <img src="{{ Storage::url($alumno->qr_code_path) }}" alt="QR"
                         class="w-24 h-24 mx-auto border border-slate-200 rounded-lg p-1">
                    <p class="text-slate-400 text-xs mt-1">Código QR</p>
                </div>
            @endif
        </div>

        <!-- Personal Details -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-slate-800 font-semibold text-sm mb-4 uppercase tracking-wide">Datos Personales</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Sexo</dt>
                    <dd class="font-medium text-slate-700">{{ $alumno->sexo ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Fecha Nac.</dt>
                    <dd class="font-medium text-slate-700">{{ $alumno->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Edad</dt>
                    <dd class="font-medium text-slate-700">{{ $alumno->edad ? $alumno->edad . ' años' : '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Ciudad</dt>
                    <dd class="font-medium text-slate-700">{{ $alumno->ciudad ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Dirección</dt>
                    <dd class="font-medium text-slate-700 text-right max-w-32">{{ $alumno->direccion ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Sede</dt>
                    <dd class="font-medium text-slate-700">{{ $alumno->sede?->nombre ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Repitente</dt>
                    <dd class="font-medium text-slate-700">{{ $alumno->repitencia ? 'Sí' : 'No' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Apoderado -->
        @if($alumno->apoderado)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-slate-800 font-semibold text-sm mb-4 uppercase tracking-wide">Apoderado</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-slate-500 text-xs">Nombre</dt>
                        <dd class="font-medium text-slate-700">{{ $alumno->apoderado->nombre_completo }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 text-xs">DNI</dt>
                        <dd class="font-mono text-slate-700">{{ $alumno->apoderado->dni ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 text-xs">Teléfono</dt>
                        <dd class="text-slate-700">{{ $alumno->apoderado->telefono ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 text-xs">Parentesco</dt>
                        <dd class="text-slate-700">{{ $alumno->apoderado->parentesco ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        @endif

    </div>

    <!-- Right column: Payments & Enrollment -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Descuento -->
        @if($alumno->tipo_descuento !== 'ninguno')
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-tag text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-emerald-800 font-semibold text-sm capitalize">Descuento: {{ $alumno->tipo_descuento }}</p>
                    <p class="text-emerald-700 text-sm">S/ {{ number_format($alumno->monto_descuento, 2) }}
                        @if($alumno->descripcion_descuento)
                            &mdash; {{ $alumno->descripcion_descuento }}
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <!-- Matrículas -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-slate-800 font-semibold text-base flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-blue-700"></i> Matrículas
                </h3>
                <a href="{{ route('matriculas.create') }}?alumno_id={{ $alumno->id }}"
                   class="text-blue-700 hover:underline text-xs font-medium">+ Nueva matrícula</a>
            </div>

            @if($alumno->matriculas->isNotEmpty())
                <div class="space-y-3">
                    @foreach($alumno->matriculas->sortByDesc('periodo') as $matricula)
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-slate-800 font-semibold text-sm">{{ $matricula->codigo_matricula }}</p>
                                    <p class="text-slate-500 text-xs mt-0.5">
                                        {{ $matricula->periodo }} &bull; {{ $matricula->nivel_academico }} &bull; {{ $matricula->grado_seccion }}
                                    </p>
                                    <p class="text-slate-500 text-xs">{{ $matricula->situacion }} &bull; {{ ucfirst($matricula->modalidad_pago) }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $matricula->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ ucfirst($matricula->estado) }}
                                </span>
                            </div>
                            @if($matricula->pagoMatricula)
                                <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <p class="text-slate-400">Matrícula</p>
                                        <p class="font-semibold text-slate-700">S/ {{ number_format($matricula->pagoMatricula->monto_matricula, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Pensión</p>
                                        <p class="font-semibold text-slate-700">S/ {{ number_format($matricula->pagoMatricula->pension_mensual, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400">Estado</p>
                                        @php
                                            $ep = $matricula->pagoMatricula->estado_pago;
                                            $epClass = match($ep) {
                                                'PAGADO'   => 'bg-green-100 text-green-700',
                                                'PENDIENTE'=> 'bg-yellow-100 text-yellow-700',
                                                'VENCIDO'  => 'bg-red-100 text-red-700',
                                                'PARCIAL'  => 'bg-blue-100 text-blue-700',
                                                default    => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $epClass }}">{{ $ep }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-sm text-center py-6">Sin matrículas registradas</p>
            @endif
        </div>

        <!-- Pagos Pensiones -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-slate-800 font-semibold text-base flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-wave text-green-600"></i> Historial de Pensiones
                </h3>
                <a href="{{ route('pagos.alumno', $alumno) }}"
                   class="text-blue-700 hover:underline text-xs font-medium">Ver todo</a>
            </div>

            @if($alumno->pagosPension->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="text-left px-3 py-2 text-slate-500 font-semibold">Mes</th>
                                <th class="text-left px-3 py-2 text-slate-500 font-semibold">Año</th>
                                <th class="text-right px-3 py-2 text-slate-500 font-semibold">Monto</th>
                                <th class="text-left px-3 py-2 text-slate-500 font-semibold">Método</th>
                                <th class="text-left px-3 py-2 text-slate-500 font-semibold">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($alumno->pagosPension->sortByDesc('fecha_pago')->take(12) as $pago)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3 py-2 font-medium text-slate-700">{{ $pago->mes_pagado }}</td>
                                    <td class="px-3 py-2 text-slate-600">{{ $pago->anio }}</td>
                                    <td class="px-3 py-2 text-right font-semibold text-slate-700">S/ {{ number_format($pago->monto, 2) }}</td>
                                    <td class="px-3 py-2 text-slate-600">{{ $pago->metodo_pago }}</td>
                                    <td class="px-3 py-2 text-slate-500">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-slate-400 text-sm text-center py-6">Sin pagos de pensión registrados</p>
            @endif
        </div>

    </div>
</div>

@endsection
