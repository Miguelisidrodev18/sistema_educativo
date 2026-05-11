@extends('layouts.app')

@section('title', 'Asistencia Alumnos')
@section('page-title', 'Control de Asistencia - Alumnos')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        <p class="text-slate-500 text-xs mt-1">Total</p>
    </div>
    <div class="bg-green-50 rounded-xl border border-green-200 p-4 text-center">
        <p class="text-2xl font-bold text-green-700">{{ $stats['presentes'] }}</p>
        <p class="text-green-600 text-xs mt-1">Presentes</p>
    </div>
    <div class="bg-red-50 rounded-xl border border-red-200 p-4 text-center">
        <p class="text-2xl font-bold text-red-700">{{ $stats['ausentes'] }}</p>
        <p class="text-red-600 text-xs mt-1">Ausentes</p>
    </div>
    <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4 text-center">
        <p class="text-2xl font-bold text-yellow-700">{{ $stats['tardanza'] }}</p>
        <p class="text-yellow-600 text-xs mt-1">Tardanza</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="{{ route('asistencias.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Fecha</label>
            <input type="date" name="fecha" value="{{ $fecha }}"
                   class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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

        <div class="min-w-28">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Grado</label>
            <input type="text" name="grado_seccion" value="{{ request('grado_seccion') }}"
                   placeholder="Ej: 3ro A"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-filter mr-1"></i>Filtrar
            </button>
            <a href="{{ route('asistencias.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>
</div>

<!-- Bulk Action Form -->
<form action="{{ route('asistencias.registrar-masivo') }}" method="POST" id="formMasivo">
    @csrf
    <input type="hidden" name="fecha" value="{{ $fecha }}">

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-slate-700 font-semibold text-sm">
            {{ count($alumnos) }} alumno(s) &mdash;
            <span class="text-blue-700">{{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
        </h3>
        <div class="flex gap-2">
            <button type="button" onclick="marcarTodos('PRESENTE')"
                    class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                <i class="fa-solid fa-check-double mr-1"></i>Todos Presentes
            </button>
            <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition">
                <i class="fa-solid fa-floppy-disk mr-1"></i>Guardar Todo
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">#</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Alumno</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nivel / Grado</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Hora Registro</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($alumnos as $i => $alumno)
                        @php
                            $asistencia = $alumno->asistencias->first();
                            $estadoActual = $asistencia?->estado ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-700 text-xs font-bold">
                                            {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidos, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-xs">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</p>
                                        <p class="text-slate-400 text-xs font-mono">{{ $alumno->dni }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                {{ $alumno->nivel_academico }} &bull; {{ $alumno->grado_seccion }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ $asistencia?->hora_registro ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1" x-data>
                                    @foreach(['PRESENTE' => ['bg-green-100 text-green-700 border-green-300', 'PRESENTE', 'P'], 'TARDANZA' => ['bg-yellow-100 text-yellow-700 border-yellow-300', 'TARDANZA', 'T'], 'AUSENTE' => ['bg-red-100 text-red-700 border-red-300', 'AUSENTE', 'A'], 'JUSTIFICADO' => ['bg-blue-100 text-blue-700 border-blue-300', 'JUSTIFICADO', 'J']] as $est => [$class, $label, $short])
                                        <label class="cursor-pointer">
                                            <input type="radio"
                                                   name="asistencias[{{ $alumno->id }}]"
                                                   value="{{ $est }}"
                                                   class="sr-only peer"
                                                   {{ $estadoActual === $est ? 'checked' : '' }}>
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 text-xs font-bold transition
                                                         {{ $class }}
                                                         peer-checked:ring-2 peer-checked:ring-offset-1
                                                         {{ $estadoActual === $est ? 'ring-2 ring-offset-1' : 'opacity-50 hover:opacity-100' }}">
                                                {{ $short }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-solid fa-clipboard-question text-4xl text-slate-300"></i>
                                    <p class="text-slate-500 font-medium">No hay alumnos para mostrar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function marcarTodos(estado) {
    document.querySelectorAll('input[type="radio"][value="' + estado + '"]').forEach(r => r.checked = true);
}
</script>
@endpush
