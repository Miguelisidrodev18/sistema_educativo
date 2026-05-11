@extends('layouts.app')

@section('title', 'Matrículas')
@section('page-title', 'Gestión de Matrículas')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-slate-700 text-sm">{{ $matriculas->total() }} matrícula(s)</h2>
    </div>
    <div class="flex gap-2">
        <!-- Import Modal Trigger -->
        <button @click="$dispatch('open-import-modal')"
                x-data
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-file-excel"></i> Importar Excel
        </button>
        <a href="{{ route('matriculas.create') }}"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg px-4 py-2 text-sm font-semibold transition">
            <i class="fa-solid fa-plus"></i> Nueva Matrícula
        </a>
    </div>
</div>

<!-- Import Modal -->
<div x-data="{ open: false }" @open-import-modal.window="open = true">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.stop>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-slate-800 font-bold text-lg">Importar Matrículas</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form action="{{ route('matriculas.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <p class="text-slate-500 text-sm mb-3">
                        Suba un archivo Excel con las columnas:<br>
                        <code class="bg-slate-100 px-1 rounded text-xs">dni_alumno, periodo, nivel_academico, grado_seccion, situacion, modalidad_pago, sede, monto_matricula, pension_mensual</code>
                    </p>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Archivo (.xlsx, .xls, .csv)</label>
                    <input type="file" name="archivo" accept=".xlsx,.xls,.csv" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                            class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-xl transition">
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
    <form action="{{ route('matriculas.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">

        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Buscar</label>
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Código, alumno, DNI..."
                       class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="min-w-28">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Período</label>
            <select name="periodo" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                @foreach($periodos as $p)
                    <option value="{{ $p }}" {{ request('periodo') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
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
            <a href="{{ route('matriculas.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Código</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Alumno</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Período</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nivel / Grado</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Situación</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Pago Matrícula</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($matriculas as $mat)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3">
                            <a href="{{ route('matriculas.show', $mat) }}"
                               class="font-mono text-xs text-blue-700 hover:underline font-semibold">
                                {{ $mat->codigo_matricula }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('alumnos.show', $mat->alumno) }}" class="font-medium text-slate-800 hover:text-blue-700">
                                {{ $mat->alumno->apellidos }}, {{ $mat->alumno->nombres }}
                            </a>
                            <p class="text-slate-400 text-xs font-mono">{{ $mat->alumno->dni }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-700 font-semibold">{{ $mat->periodo }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $mat->nivel_academico === 'INICIAL' ? 'bg-pink-100 text-pink-700' : ($mat->nivel_academico === 'PRIMARIA' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                {{ $mat->nivel_academico }}
                            </span>
                            <span class="text-slate-500 text-xs ml-1">{{ $mat->grado_seccion }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">{{ $mat->situacion }}</td>
                        <td class="px-4 py-3">
                            @if($mat->pagoMatricula)
                                @php
                                    $ep = $mat->pagoMatricula->estado_pago;
                                    $epClass = match($ep) {
                                        'PAGADO'   => 'bg-green-100 text-green-700',
                                        'PENDIENTE'=> 'bg-yellow-100 text-yellow-700',
                                        'VENCIDO'  => 'bg-red-100 text-red-700',
                                        'PARCIAL'  => 'bg-blue-100 text-blue-700',
                                        default    => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $epClass }}">{{ $ep }}</span>
                                <p class="text-slate-500 text-xs mt-0.5">S/ {{ number_format($mat->pagoMatricula->monto_matricula, 2) }}</p>
                            @else
                                <span class="text-slate-400 text-xs">Sin registro</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $mat->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($mat->estado) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fa-solid fa-file-circle-xmark text-4xl text-slate-300"></i>
                                <p class="text-slate-500 font-medium">No se encontraron matrículas</p>
                                <a href="{{ route('matriculas.create') }}" class="text-blue-700 hover:underline text-sm">
                                    Registrar primera matrícula
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($matriculas->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $matriculas->links() }}
        </div>
    @endif
</div>

@endsection
