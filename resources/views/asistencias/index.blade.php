@extends('layouts.app')

@section('title', 'Asistencia Alumnos')
@section('page-title', 'Control de Asistencia — Alumnos')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-users text-slate-500"></i>
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

<!-- Filtros -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-5">
    <form action="{{ route('asistencias.index') }}" method="GET"
          class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Fecha</label>
            <input type="date" name="fecha" value="{{ $fecha }}"
                   class="border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Sede</label>
            <select name="sede_id"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Todas</option>
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}" {{ request('sede_id') == $sede->id ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-32">
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nivel</label>
            <select name="nivel"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Todos</option>
                <option value="INICIAL"    {{ request('nivel') === 'INICIAL'    ? 'selected' : '' }}>Inicial</option>
                <option value="PRIMARIA"   {{ request('nivel') === 'PRIMARIA'   ? 'selected' : '' }}>Primaria</option>
                <option value="SECUNDARIA" {{ request('nivel') === 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
            </select>
        </div>
        <div class="min-w-28">
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Grado</label>
            <input type="text" name="grado_seccion" value="{{ request('grado_seccion') }}"
                   placeholder="Ej: 3ro A"
                   class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-1.5">
                <i class="fa-solid fa-filter"></i> Filtrar
            </button>
            <a href="{{ route('asistencias.index') }}"
               class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-sm transition flex items-center">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>
    </form>
</div>

<!-- Formulario masivo -->
<form action="{{ route('asistencias.registrar-masivo') }}" method="POST" id="formMasivo">
    @csrf
    <input type="hidden" name="fecha" value="{{ $fecha }}">

    <!-- Barra de acciones -->
    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
        <div>
            <p class="font-semibold text-slate-700 text-sm">
                {{ count($alumnos) }} alumno(s)
            </p>
            <p class="text-xs text-blue-600 font-medium">
                {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button type="button" onclick="marcarTodos('PRESENTE')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-green-500 hover:bg-green-600 text-white transition shadow-sm shadow-green-200">
                <i class="fa-solid fa-check-double"></i> Todos presentes
            </button>
            <button type="button" onclick="marcarTodos('')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-700 transition">
                <i class="fa-solid fa-eraser"></i> Limpiar
            </button>
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm">
                <i class="fa-solid fa-floppy-disk"></i> Guardar todo
            </button>
        </div>
    </div>

    <!-- Lista de alumnos -->
    @forelse($alumnos as $i => $alumno)
    @php $asistencia = $alumno->asistencias->first(); $estadoActual = $asistencia?->estado ?? ''; @endphp
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

            <!-- Número -->
            <span class="text-xs text-slate-400 font-mono w-5 shrink-0 text-right">{{ $i + 1 }}</span>

            <!-- Avatar -->
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-xs font-black text-white
                        @if($estadoActual === 'PRESENTE')   bg-green-500 @elseif($estadoActual === 'TARDANZA')    bg-amber-500
                        @elseif($estadoActual === 'AUSENTE') bg-red-500   @elseif($estadoActual === 'JUSTIFICADO') bg-blue-500
                        @else bg-slate-400 @endif"
                 :class="{
                     'bg-green-500': estado === 'PRESENTE',
                     'bg-amber-500': estado === 'TARDANZA',
                     'bg-red-500':   estado === 'AUSENTE',
                     'bg-blue-500':  estado === 'JUSTIFICADO',
                     'bg-slate-300': estado === '',
                 }">
                {{ substr($alumno->apellidos, 0, 1) }}{{ substr($alumno->nombres, 0, 1) }}
            </div>

            <!-- Nombre -->
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-slate-800 text-sm truncate leading-tight">
                    {{ $alumno->apellidos }}, {{ $alumno->nombres }}
                </p>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">{{ $alumno->dni }}</span>
                    <span class="text-[10px] text-slate-400">·</span>
                    <span class="text-xs text-slate-500">{{ $alumno->nivel_academico }} {{ $alumno->grado_seccion }}</span>
                    @if($asistencia?->hora_registro)
                        <span class="text-[10px] text-slate-400">·</span>
                        <span class="text-xs text-slate-400">{{ $asistencia->hora_registro }}</span>
                    @endif
                </div>
            </div>

            <!-- Botones de estado -->
            <div class="flex items-center gap-1.5 shrink-0">

                {{-- PRESENTE --}}
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $alumno->id }}]" value="PRESENTE"
                           class="sr-only peer" {{ $estadoActual === 'PRESENTE' ? 'checked' : '' }}
                           @change="estado = 'PRESENTE'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-green-200 bg-green-50 text-green-300
                                 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-600 peer-checked:shadow-md
                                 hover:bg-green-100 hover:text-green-600 hover:border-green-300 active:scale-95"
                          title="Presente">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </label>

                {{-- TARDANZA --}}
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $alumno->id }}]" value="TARDANZA"
                           class="sr-only peer" {{ $estadoActual === 'TARDANZA' ? 'checked' : '' }}
                           @change="estado = 'TARDANZA'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-amber-200 bg-amber-50 text-amber-300
                                 peer-checked:bg-amber-400 peer-checked:text-white peer-checked:border-amber-500 peer-checked:shadow-md
                                 hover:bg-amber-100 hover:text-amber-600 hover:border-amber-300 active:scale-95"
                          title="Tardanza">
                        <i class="fa-solid fa-clock"></i>
                    </span>
                </label>

                {{-- AUSENTE --}}
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $alumno->id }}]" value="AUSENTE"
                           class="sr-only peer" {{ $estadoActual === 'AUSENTE' ? 'checked' : '' }}
                           @change="estado = 'AUSENTE'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-red-200 bg-red-50 text-red-300
                                 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-600 peer-checked:shadow-md
                                 hover:bg-red-100 hover:text-red-600 hover:border-red-300 active:scale-95"
                          title="Ausente">
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                </label>

                {{-- JUSTIFICADO --}}
                <label class="cursor-pointer">
                    <input type="radio" name="asistencias[{{ $alumno->id }}]" value="JUSTIFICADO"
                           class="sr-only peer" {{ $estadoActual === 'JUSTIFICADO' ? 'checked' : '' }}
                           @change="estado = 'JUSTIFICADO'">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl border-2 text-sm font-black transition-all duration-150 select-none
                                 border-blue-200 bg-blue-50 text-blue-300
                                 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md
                                 hover:bg-blue-100 hover:text-blue-600 hover:border-blue-300 active:scale-95"
                          title="Justificado">
                        <i class="fa-solid fa-file-circle-check"></i>
                    </span>
                </label>

            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-14 text-center">
        <i class="fa-solid fa-clipboard-question text-5xl text-slate-200 mb-3 block"></i>
        <p class="text-slate-500 font-semibold">No hay alumnos para mostrar</p>
        <p class="text-slate-400 text-sm mt-1">Ajusta los filtros para ver resultados</p>
    </div>
    @endforelse

    @if(count($alumnos) > 6)
    <!-- Guardar fijo al fondo en mobile -->
    <div class="sticky bottom-4 flex justify-center mt-4">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-300 transition active:scale-[.98]">
            <i class="fa-solid fa-floppy-disk"></i> Guardar asistencia
        </button>
    </div>
    @endif
</form>

@endsection

@push('scripts')
<script>
function marcarTodos(estado) {
    document.querySelectorAll('input[type="radio"]').forEach(r => {
        if (estado === '') {
            r.checked = false;
        } else if (r.value === estado) {
            r.checked = true;
            r.dispatchEvent(new Event('change'));
        }
    });
}
</script>
@endpush
