@extends('layouts.app')
@section('title', 'Configuración')
@section('page-title', 'Configuración del Colegio')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ── FIRMA DEL DIRECTOR ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden fade-up">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-signature text-blue-600 text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800 text-sm">Firma del Director(a)</p>
                <p class="text-slate-400 text-xs">Aparece en las constancias de matrícula</p>
            </div>
        </div>
        <div class="p-6">
            @if($firmaUrl)
            <div class="mb-4 flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <img src="{{ $firmaUrl }}" alt="Firma Director" class="h-16 object-contain bg-white border border-slate-200 rounded-lg p-2">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-700">Firma configurada</p>
                    <p class="text-xs text-slate-400 mt-0.5">PNG/JPG — se mostrará en las constancias</p>
                </div>
                <form action="{{ route('configuracion.eliminar-firma') }}" method="POST"
                      onsubmit="return confirm('¿Eliminar la firma actual?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg transition">
                        <i class="fa-solid fa-trash mr-1"></i>Eliminar
                    </button>
                </form>
            </div>
            @else
            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm text-yellow-700">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                No hay firma configurada. Las constancias se imprimirán sin firma.
            </div>
            @endif

            <form action="{{ route('configuracion.subir-firma') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            {{ $firmaUrl ? 'Reemplazar firma' : 'Subir firma' }} (PNG o JPG)
                        </label>
                        <input type="file" name="firma" accept=".png,.jpg,.jpeg" required
                               class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-[11px] text-slate-400 mt-1">Recomendado: fondo blanco o transparente, máx. 2 MB</p>
                    </div>
                    <button type="submit"
                            class="shrink-0 bg-blue-700 hover:bg-blue-800 text-white font-bold px-5 py-2.5 rounded-xl transition text-sm shadow-sm shadow-blue-500/20">
                        <i class="fa-solid fa-upload mr-1"></i>Subir
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MESES DE PAGO ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden fade-up-d1">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 bg-teal-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-calendar-days text-teal-600 text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800 text-sm">Meses de Pago de Pensiones</p>
                <p class="text-slate-400 text-xs">Define el rango de meses activos para cobro de pensiones</p>
            </div>
        </div>
        <div class="p-6">

            {{-- Preview meses activos --}}
            @php
                $mesInicio = (int)$cfg['mes_inicio'];
                $mesFin    = (int)$cfg['mes_fin'];
                $activos   = array_slice($mesesTodos, $mesInicio - 1, $mesFin - $mesInicio + 1);
            @endphp
            <div class="mb-4 p-4 bg-teal-50 border border-teal-200 rounded-xl">
                <p class="text-xs font-bold text-teal-700 uppercase tracking-wide mb-2">
                    Meses activos actuales ({{ count($activos) }} meses):
                </p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($activos as $m)
                        <span class="text-xs bg-teal-100 text-teal-700 font-semibold px-2.5 py-1 rounded-full border border-teal-200">{{ $m }}</span>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('configuracion.guardar') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Mes de inicio <span class="text-red-500">*</span>
                        </label>
                        <select name="mes_inicio" required
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                            @foreach($mesesTodos as $i => $mes)
                                <option value="{{ $i + 1 }}" {{ $cfg['mes_inicio'] == $i + 1 ? 'selected' : '' }}>
                                    {{ $mes }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Mes de fin <span class="text-red-500">*</span>
                        </label>
                        <select name="mes_fin" required
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                            @foreach($mesesTodos as $i => $mes)
                                <option value="{{ $i + 1 }}" {{ $cfg['mes_fin'] == $i + 1 ? 'selected' : '' }}>
                                    {{ $mes }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 mb-4">
                    <i class="fa-solid fa-circle-info text-blue-400 text-sm"></i>
                    <p class="text-xs text-slate-500">
                        Ejemplo: <strong>Marzo → Diciembre</strong> = 10 meses de pensiones.
                        La barra de progreso de cada alumno refleja este rango.
                    </p>
                </div>
                <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-xl transition shadow-sm text-sm">
                    <i class="fa-solid fa-save mr-1"></i>Guardar configuración de meses
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
