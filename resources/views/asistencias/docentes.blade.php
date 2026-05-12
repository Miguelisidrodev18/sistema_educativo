@extends('layouts.app')

@section('title', 'Asistencia Docentes')
@section('page-title', 'Control de Asistencia - Docentes')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-chalkboard-user text-slate-500"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-800 leading-none">{{ $stats['total'] }}</p>
            <p class="text-slate-500 text-xs mt-0.5">Total</p>
        </div>
    </div>
    <div class="bg-green-500 rounded-2xl shadow-sm shadow-green-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/25 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-check text-white text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-white leading-none">{{ $stats['presentes'] }}</p>
            <p class="text-green-100 text-xs mt-0.5">Presentes</p>
        </div>
    </div>
    <div class="bg-red-500 rounded-2xl shadow-sm shadow-red-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/25 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-xmark text-white text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-white leading-none">{{ $stats['ausentes'] }}</p>
            <p class="text-red-100 text-xs mt-0.5">Ausentes</p>
        </div>
    </div>
    <div class="bg-amber-400 rounded-2xl shadow-sm shadow-amber-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/25 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-clock text-white text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-white leading-none">{{ $stats['tardanza'] }}</p>
            <p class="text-amber-100 text-xs mt-0.5">Tardanza</p>
        </div>
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

    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
        <div>
            <p class="font-semibold text-slate-700 text-sm">{{ count($docentes) }} docente(s) / auxiliar(es)</p>
            <p class="text-xs text-blue-600 font-medium">
                {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="marcarTodos('PRESENTE')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-green-500 hover:bg-green-600 text-white transition shadow-sm shadow-green-200">
                <i class="fa-solid fa-check-double"></i> Todos presentes
            </button>
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm">
                <i class="fa-solid fa-floppy-disk"></i> Guardar todo
            </button>
        </div>
    </div>

    @forelse($docentes as $i => $docente)
    @php $asistencia = $docente->asistenciasDocente->first(); $estadoActual = $asistencia?->estado ?? ''; @endphp
    <div class="bg-white rounded-2xl border-2 mb-2 transition-all duration-200 overflow-hidden
                @if($estadoActual === 'PRESENTE')   border-green-400 @elseif($estadoActual === 'TARDANZA')    border-amber-400
                @elseif($estadoActual === 'AUSENTE') border-red-400   @elseif($estadoActual === 'JUSTIFICADO') border-blue-400
                @else border-slate-200 @endif"
         x-data="{ estado: '{{ $estadoActual }}' }"
         :class="{
             'border-green-400 bg-green-50/30':   estado === 'PRESENTE',
             'border-amber-400 bg-amber-50/30':   estado === 'TARDANZA',
             'border-red-400   bg-red-50/30':     estado === 'AUSENTE',
             'border-blue-400  bg-blue-50/30':    estado === 'JUSTIFICADO',
             'border-slate-200':                  estado === '',
         }">
        <div class="flex items-center gap-3 px-4 py-3">
            <span class="text-xs text-slate-400 font-mono w-5 shrink-0 text-right">{{ $i + 1 }}</span>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-sm font-black text-white
                        @if($estadoActual === 'PRESENTE')   bg-green-500 @elseif($estadoActual === 'TARDANZA')    bg-amber-500
                        @elseif($estadoActual === 'AUSENTE') bg-red-500   @elseif($estadoActual === 'JUSTIFICADO') bg-blue-500
                        @else bg-violet-500 @endif"
                 :class="{
                     'bg-green-500': estado === 'PRESENTE',
                     'bg-amber-500': estado === 'TARDANZA',
                     'bg-red-500':   estado === 'AUSENTE',
                     'bg-blue-500':  estado === 'JUSTIFICADO',
                     'bg-violet-500': estado === '',
                 }">
                {{ strtoupper(substr($docente->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-slate-800 text-sm truncate leading-tight">{{ $docente->name }}</p>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                        {{ $docente->user_type === 'docente' ? 'bg-violet-100 text-violet-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ ucfirst($docente->user_type) }}
                    </span>
                    @if($docente->sede)
                    <span class="text-xs text-slate-400">{{ $docente->sede->nombre }}</span>
                    @endif
                    @if($asistencia?->hora_registro)
                    <span class="text-xs text-slate-400">· {{ $asistencia->hora_registro }}</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $docente->id }}]" value="PRESENTE"
                           class="sr-only peer" {{ $estadoActual === 'PRESENTE' ? 'checked' : '' }}
                           @change="estado = 'PRESENTE'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-green-200 bg-green-50 text-green-300
                                 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-600 peer-checked:shadow-md
                                 hover:bg-green-100 hover:text-green-600 hover:border-green-300 active:scale-95" title="Presente">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $docente->id }}]" value="TARDANZA"
                           class="sr-only peer" {{ $estadoActual === 'TARDANZA' ? 'checked' : '' }}
                           @change="estado = 'TARDANZA'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-amber-200 bg-amber-50 text-amber-300
                                 peer-checked:bg-amber-400 peer-checked:text-white peer-checked:border-amber-500 peer-checked:shadow-md
                                 hover:bg-amber-100 hover:text-amber-600 hover:border-amber-300 active:scale-95" title="Tardanza">
                        <i class="fa-solid fa-clock"></i>
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $docente->id }}]" value="AUSENTE"
                           class="sr-only peer" {{ $estadoActual === 'AUSENTE' ? 'checked' : '' }}
                           @change="estado = 'AUSENTE'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-red-200 bg-red-50 text-red-300
                                 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-600 peer-checked:shadow-md
                                 hover:bg-red-100 hover:text-red-600 hover:border-red-300 active:scale-95" title="Ausente">
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $docente->id }}]" value="JUSTIFICADO"
                           class="sr-only peer" {{ $estadoActual === 'JUSTIFICADO' ? 'checked' : '' }}
                           @change="estado = 'JUSTIFICADO'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-blue-200 bg-blue-50 text-blue-300
                                 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md
                                 hover:bg-blue-100 hover:text-blue-600 hover:border-blue-300 active:scale-95" title="Justificado">
                        <i class="fa-solid fa-file-circle-check"></i>
                    </span>
                </label>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-14 text-center">
        <i class="fa-solid fa-chalkboard-user text-5xl text-slate-200 mb-3 block"></i>
        <p class="text-slate-500 font-semibold">No hay docentes para mostrar</p>
    </div>
    @endforelse
</form>

@endsection

@push('scripts')
<script>
function marcarTodos(estado) {
    document.querySelectorAll('input[type="radio"][value="' + estado + '"]').forEach(r => r.checked = true);
}
</script>
@endpush
