@extends('layouts.app')

@section('title', 'Asistencia Docentes')
@section('page-title', 'Control de Asistencia - Docentes')

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
    <form action="{{ route('asistencias.docentes') }}" method="GET" class="flex flex-wrap gap-3 items-end">

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

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-filter mr-1"></i>Filtrar
            </button>
            <a href="{{ route('asistencias.docentes') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>
</div>

<!-- Form -->
<form action="{{ route('asistencias.registrar-masivo-docentes') }}" method="POST">
    @csrf
    <input type="hidden" name="fecha" value="{{ $fecha }}">

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-slate-700 font-semibold text-sm">
            {{ count($docentes) }} docente(s) / auxiliar(es) &mdash;
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
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Docente / Auxiliar</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Tipo</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Sede</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Hora</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($docentes as $i => $docente)
                        @php
                            $asistencia = $docente->asistenciasDocente->first();
                            $estadoActual = $asistencia?->estado ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-purple-700 text-xs font-bold">{{ substr($docente->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-xs">{{ $docente->name }}</p>
                                        <p class="text-slate-400 text-xs">{{ $docente->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full capitalize
                                    {{ $docente->user_type === 'docente' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $docente->user_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">{{ $docente->sede?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $asistencia?->hora_registro ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    @foreach(['PRESENTE' => 'P', 'TARDANZA' => 'T', 'AUSENTE' => 'A', 'JUSTIFICADO' => 'J'] as $est => $short)
                                        @php
                                            $class = match($est) {
                                                'PRESENTE'    => 'bg-green-100 text-green-700 border-green-300',
                                                'TARDANZA'    => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                                'AUSENTE'     => 'bg-red-100 text-red-700 border-red-300',
                                                'JUSTIFICADO' => 'bg-blue-100 text-blue-700 border-blue-300',
                                            };
                                        @endphp
                                        <label class="cursor-pointer">
                                            <input type="radio"
                                                   name="asistencias[{{ $docente->id }}]"
                                                   value="{{ $est }}"
                                                   class="sr-only peer"
                                                   {{ $estadoActual === $est ? 'checked' : '' }}>
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 text-xs font-bold transition {{ $class }}
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
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-solid fa-chalkboard-user text-4xl text-slate-300"></i>
                                    <p class="text-slate-500 font-medium">No hay docentes registrados</p>
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
