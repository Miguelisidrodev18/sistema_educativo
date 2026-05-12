@extends('layouts.app')

@section('title', 'Matrículas')
@section('page-title', 'Gestión de Matrículas')

@section('content')

{{-- ══════════════════════════════════════════════════════
     CABECERA: contador + botón nueva matrícula
     ══════════════════════════════════════════════════════ --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5 fade-up">
    <p class="text-slate-500 text-sm">{{ $matriculas->total() }} matrícula(s) encontrada(s)</p>
    <a href="{{ route('matriculas.create') }}"
       class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition shadow-sm shadow-blue-500/20">
        <i class="fa-solid fa-plus"></i> Nueva Matrícula
    </a>
</div>

{{-- ══════════════════════════════════════════════════════
     TARJETA DE IMPORTACIÓN MASIVA
     ══════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-5 overflow-hidden fade-up-d1"
     x-data="{ expanded: {{ session('import_open') ? 'true' : 'false' }}, uploading: false }">

    {{-- Cabecera colapsable --}}
    <button type="button" @click="expanded = !expanded"
            class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition text-left">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-excel text-teal-600 text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800 text-sm">Importación Masiva de Matrículas</p>
                <p class="text-slate-400 text-xs">Descarga la plantilla, llénala y súbela para registrar alumnos en lote</p>
            </div>
        </div>
        <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-200"
           :class="expanded ? 'rotate-180' : ''"></i>
    </button>

    {{-- Cuerpo colapsable --}}
    <div x-show="expanded" x-collapse x-cloak>
        <div class="border-t border-slate-100 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ── PASO 1: Descargar plantilla ── --}}
                <div class="bg-gradient-to-br from-teal-50 to-emerald-50 border border-teal-200 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-teal-600 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-white font-black text-sm">1</span>
                        </div>
                        <div>
                            <p class="font-bold text-teal-800 text-sm">Descarga la plantilla</p>
                            <p class="text-teal-600 text-xs">Archivo Excel con ejemplos de Primaria y Secundaria</p>
                        </div>
                    </div>

                    {{-- Estructura de hojas por nivel --}}
                    <div class="space-y-2 mb-4">
                        <div class="bg-white/70 rounded-xl p-3 border border-teal-100">
                            <p class="text-[10px] font-bold text-pink-700 uppercase tracking-wide mb-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-child-reaching text-[9px]"></i> Inicial — hojas:
                            </p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(['3 AÑOS','4 AÑOS','5 AÑOS'] as $g)
                                    <span class="text-[10px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded-full font-semibold">{{ $g }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-white/70 rounded-xl p-3 border border-teal-100">
                            <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wide mb-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-book-open text-[9px]"></i> Primaria — hojas:
                            </p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(['PRIMERO','SEGUNDO','TERCERO','CUARTO','QUINTO','SEXTO'] as $g)
                                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">{{ $g }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-white/70 rounded-xl p-3 border border-teal-100">
                            <p class="text-[10px] font-bold text-purple-700 uppercase tracking-wide mb-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-graduation-cap text-[9px]"></i> Secundaria — hojas:
                            </p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(['PRIMERO A','PRIMERO B','SEGUNDO','TERCERO','CUARTO','QUINTO'] as $g)
                                    <span class="text-[10px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-semibold">{{ $g }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Columnas del Excel --}}
                    <div class="bg-white/60 rounded-xl p-3 border border-teal-100 mb-4">
                        <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wide mb-2">Columnas de cada hoja</p>
                        <div class="grid grid-cols-2 gap-x-2 gap-y-1">
                            @foreach(['N°','DNI ALUMNO','APELLIDOS','NOMBRES','FECHA NAC. (DD/MM/YYYY)','SEXO (M/F)','CIUDAD','DIRECCIÓN','INST. PROCEDENCIA','REPITE (SI/NO)','SITUACIÓN','DNI APODERADO','APELLIDOS APODERADO','NOMBRES APODERADO','PARENTESCO','TELÉFONO','EMAIL','OCUPACIÓN','ESTADO CIVIL','MONTO MATRÍCULA','PENSIÓN MENSUAL'] as $col)
                            <code class="text-[9px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-mono truncate block">{{ $col }}</code>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3 botones de descarga --}}
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('matriculas.plantilla', 'inicial') }}"
                           class="flex flex-col items-center justify-center gap-1 bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-xl transition text-xs shadow-sm">
                            <i class="fa-solid fa-download text-sm"></i>
                            Inicial
                        </a>
                        <a href="{{ route('matriculas.plantilla', 'primaria') }}"
                           class="flex flex-col items-center justify-center gap-1 bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition text-xs shadow-sm shadow-blue-500/20">
                            <i class="fa-solid fa-download text-sm"></i>
                            Primaria
                        </a>
                        <a href="{{ route('matriculas.plantilla', 'secundaria') }}"
                           class="flex flex-col items-center justify-center gap-1 bg-purple-700 hover:bg-purple-800 text-white font-bold py-3 rounded-xl transition text-xs shadow-sm">
                            <i class="fa-solid fa-download text-sm"></i>
                            Secundaria
                        </a>
                    </div>
                </div>

                {{-- ── PASO 2: Subir archivo ── --}}
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5"
                     x-data="{ archivo: null, dragging: false }">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-blue-700 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-white font-black text-sm">2</span>
                        </div>
                        <div>
                            <p class="font-bold text-blue-800 text-sm">Sube el archivo lleno</p>
                            <p class="text-blue-600 text-xs">Acepta .xlsx · .xls · máx. 10 MB</p>
                        </div>
                    </div>

                    <form action="{{ route('matriculas.importar') }}" method="POST" enctype="multipart/form-data"
                          @submit="uploading = true">
                        @csrf

                        {{-- Campos requeridos --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="block text-[10px] font-bold text-blue-800 uppercase tracking-wide mb-1">
                                    Nivel <span class="text-red-500">*</span>
                                </label>
                                <select name="nivel" required
                                        class="w-full border border-blue-200 rounded-xl px-3 py-2 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    <option value="">Seleccionar…</option>
                                    <option value="INICIAL">Inicial</option>
                                    <option value="PRIMARIA" selected>Primaria</option>
                                    <option value="SECUNDARIA">Secundaria</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-blue-800 uppercase tracking-wide mb-1">
                                    Período <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="periodo" value="{{ date('Y') }}" required
                                       min="2020" max="2099"
                                       class="w-full border border-blue-200 rounded-xl px-3 py-2 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-blue-800 uppercase tracking-wide mb-1">Sede</label>
                                <select name="sede_id"
                                        class="w-full border border-blue-200 rounded-xl px-3 py-2 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sin sede / sesión activa</option>
                                    @foreach(\App\Models\Sede::where('activo',true)->orderBy('nombre')->get() as $s)
                                        <option value="{{ $s->id }}" {{ session('sede_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-blue-800 uppercase tracking-wide mb-1">Monto matr. / Pensión</label>
                                <div class="flex gap-1">
                                    <input type="number" name="monto_matricula" placeholder="100" step="0.01"
                                           class="w-1/2 border border-blue-200 rounded-xl px-2 py-2 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <input type="number" name="pension_mensual" placeholder="240" step="0.01"
                                           class="w-1/2 border border-blue-200 rounded-xl px-2 py-2 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <p class="text-[9px] text-blue-400 mt-0.5">Si están en el Excel se usan los del archivo</p>
                            </div>
                        </div>

                        {{-- Zona drag & drop --}}
                        <label for="archivo_excel"
                               class="block w-full border-2 border-dashed rounded-xl p-5 text-center cursor-pointer mb-3 transition"
                               :class="dragging ? 'border-blue-500 bg-blue-100' : 'border-blue-300 bg-white/60 hover:border-blue-400 hover:bg-blue-50'"
                               @dragover.prevent="dragging = true"
                               @dragleave.prevent="dragging = false"
                               @drop.prevent="dragging = false; archivo = $event.dataTransfer.files[0]; $refs.fileInput.files = $event.dataTransfer.files">
                            <div x-show="!archivo">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-blue-400 mb-1 block"></i>
                                <p class="text-sm font-semibold text-blue-700">Arrastra el archivo aquí</p>
                                <p class="text-xs text-blue-400 mt-0.5">o haz clic para seleccionar</p>
                            </div>
                            <div x-show="archivo" x-cloak>
                                <i class="fa-solid fa-file-excel text-3xl text-teal-500 mb-1 block"></i>
                                <p class="text-sm font-bold text-slate-800 truncate" x-text="archivo?.name"></p>
                                <p class="text-xs text-slate-400" x-text="archivo ? (archivo.size/1024).toFixed(0) + ' KB' : ''"></p>
                            </div>
                            <input type="file" id="archivo_excel" name="archivo" x-ref="fileInput"
                                   accept=".xlsx,.xls" required class="hidden"
                                   @change="archivo = $event.target.files[0]">
                        </label>

                        <div class="bg-white/70 rounded-xl p-3 border border-blue-100 mb-3">
                            <p class="text-[10px] text-slate-500 leading-relaxed">
                                <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
                                Alumno/apoderado <strong>nuevo</strong> → se crea automáticamente.
                                <strong>Ya existe</strong> → se actualiza nivel y grado.
                                Matrícula duplicada del mismo año → se omite.
                            </p>
                        </div>

                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 font-bold py-3 rounded-xl transition text-sm shadow-sm"
                                :class="archivo ? 'bg-blue-700 hover:bg-blue-800 text-white shadow-blue-500/20' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                :disabled="!archivo || uploading">
                            <template x-if="!uploading">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-upload"></i> Importar Matrículas
                                </span>
                            </template>
                            <template x-if="uploading">
                                <span class="flex items-center gap-2">
                                    <i class="fa-solid fa-spinner animate-spin"></i> Procesando…
                                </span>
                            </template>
                        </button>
                    </form>
                </div>

            </div>{{-- /grid --}}
        </div>{{-- /body --}}
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     FILTROS
     ══════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-5 fade-up-d2">
    <form action="{{ route('matriculas.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">

        <div class="flex-1 min-w-44">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Buscar</label>
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Código, alumno, DNI..."
                       class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition">
            </div>
        </div>

        <div class="min-w-28">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Período</label>
            <select name="periodo" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                <option value="">Todos</option>
                @foreach($periodos as $p)
                    <option value="{{ $p }}" {{ request('periodo') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-36">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sede</label>
            <select name="sede_id" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                <option value="">Todas</option>
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}" {{ request('sede_id') == $sede->id ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-32">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nivel</label>
            <select name="nivel" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                <option value="">Todos</option>
                <option value="INICIAL"    {{ request('nivel') === 'INICIAL'    ? 'selected' : '' }}>Inicial</option>
                <option value="PRIMARIA"   {{ request('nivel') === 'PRIMARIA'   ? 'selected' : '' }}>Primaria</option>
                <option value="SECUNDARIA" {{ request('nivel') === 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm shadow-blue-500/20">
                <i class="fa-solid fa-filter mr-1"></i>Filtrar
            </button>
            <a href="{{ route('matriculas.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-sm transition">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>
</div>

{{-- ══════════════════════════════════════════════════════
     TABLA
     ══════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden fade-up-d3">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Código</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Alumno</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Período</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Nivel / Grado</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Situación</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Pago Matrícula</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($matriculas as $mat)
                    @php
                        $nivelBorder = match($mat->nivel_academico) {
                            'INICIAL'    => 'border-l-4 border-l-pink-400',
                            'PRIMARIA'   => 'border-l-4 border-l-blue-500',
                            'SECUNDARIA' => 'border-l-4 border-l-violet-500',
                            default      => '',
                        };
                        $nivelBadge = match($mat->nivel_academico) {
                            'INICIAL'    => 'bg-pink-500 text-white',
                            'PRIMARIA'   => 'bg-blue-600 text-white',
                            'SECUNDARIA' => 'bg-violet-600 text-white',
                            default      => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition {{ $nivelBorder }}">
                        <td class="px-4 py-3">
                            <a href="{{ route('matriculas.show', $mat) }}"
                               class="font-mono text-xs text-blue-700 hover:underline font-bold">
                                {{ $mat->codigo_matricula }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('alumnos.show', $mat->alumno) }}"
                               class="font-semibold text-slate-800 hover:text-blue-700 leading-tight block">
                                {{ $mat->alumno->apellidos }}, {{ $mat->alumno->nombres }}
                            </a>
                            <span class="text-slate-400 text-xs font-mono">{{ $mat->alumno->dni }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-black text-slate-700">{{ $mat->periodo }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $nivelBadge }}">
                                {{ $mat->nivel_academico }}
                            </span>
                            <p class="text-slate-500 text-xs mt-1 font-medium">{{ $mat->grado_seccion }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-xs">{{ $mat->situacion }}</td>
                        <td class="px-4 py-3">
                            @if($mat->pagoMatricula)
                                @php
                                    $ep = $mat->pagoMatricula->estado_pago;
                                    $epClass = match($ep) {
                                        'PAGADO'    => 'bg-green-500 text-white',
                                        'PENDIENTE' => 'bg-amber-400 text-white',
                                        'VENCIDO'   => 'bg-red-500 text-white',
                                        'PARCIAL'   => 'bg-blue-500 text-white',
                                        default     => 'bg-slate-200 text-slate-600',
                                    };
                                @endphp
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold {{ $epClass }}">{{ $ep }}</span>
                                <p class="text-slate-400 text-xs mt-0.5">S/ {{ number_format($mat->pagoMatricula->monto_matricula, 2) }}</p>
                            @else
                                <span class="text-slate-300 text-xs">Sin registro</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($mat->estado === 'activo')
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold bg-green-500 text-white">Activo</span>
                            @else
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold bg-slate-200 text-slate-500">{{ ucfirst($mat->estado) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i class="fa-solid fa-file-circle-xmark text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 font-semibold">No se encontraron matrículas</p>
                                <a href="{{ route('matriculas.create') }}" class="text-blue-700 hover:underline text-sm font-medium">
                                    Registrar primera matrícula →
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($matriculas->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                Mostrando {{ $matriculas->firstItem() }}–{{ $matriculas->lastItem() }} de {{ $matriculas->total() }}
            </p>
            {{ $matriculas->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
// Abrir la sección de importación automáticamente si hubo error de importación
@if(session('error'))
document.addEventListener('DOMContentLoaded', () => {
    const comp = document.querySelector('[x-data*="expanded"]');
    if (comp) comp.__x.$data.expanded = true;
});
@endif
</script>
@endpush

@endsection
