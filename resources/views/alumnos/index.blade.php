@extends('layouts.app')

@section('title', 'Alumnos')
@section('page-title', 'Gestión de Alumnos')

@section('content')

<div x-data="{
    selected: [],
    toggleAll(checked, ids) { this.selected = checked ? ids : []; },
    openCarnets() {
        if (!this.selected.length) { alert('Selecciona al menos un alumno.'); return; }
        window.open('{{ route('alumnos.carnets') }}?ids=' + this.selected.join(','), '_blank');
    },

    /* ── Modal de fecha de emisión ── */
    modalFecha: false,
    fechaModal: '',
    alumnoIdModal: null,
    constanciaUrlBase: '',
    ticketUrlBase: '',

    abrirModalFecha(alumnoId, constanciaUrl, ticketUrl) {
        this.alumnoIdModal    = alumnoId;
        this.constanciaUrlBase = constanciaUrl;
        this.ticketUrlBase    = ticketUrl;
        // Recuperar fecha guardada para este alumno (localStorage)
        const guardada = localStorage.getItem('fecha_emision_' + alumnoId);
        this.fechaModal = guardada || new Date().toISOString().split('T')[0];
        this.modalFecha = true;
        this.$nextTick(() => this.$refs.fechaInput?.focus());
    },
    guardarFecha() {
        if (this.alumnoIdModal && this.fechaModal) {
            localStorage.setItem('fecha_emision_' + this.alumnoIdModal, this.fechaModal);
        }
    },
    abrirConstancia() {
        this.guardarFecha();
        window.open(this.constanciaUrlBase + '?fecha=' + this.fechaModal, '_blank');
        this.modalFecha = false;
    },
    abrirTicket() {
        this.guardarFecha();
        window.open(this.ticketUrlBase + '?fecha=' + this.fechaModal, '_blank');
        this.modalFecha = false;
    }
}">

{{-- ── MODAL FECHA DE EMISIÓN ── --}}
<div x-show="modalFecha" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="modalFecha = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="modalFecha = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.stop>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-calendar-days text-blue-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Fecha de emisión</h3>
                <p class="text-slate-400 text-xs">Se usará en el ticket y la constancia</p>
            </div>
            <button @click="modalFecha = false" class="ml-auto text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="mb-5">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                Fecha de emisión del documento
            </label>
            <input type="date" x-model="fechaModal" x-ref="fechaInput"
                   class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition font-semibold">
            <p class="text-xs text-slate-400 mt-1.5">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Se recuerda la última fecha usada para este alumno
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button @click="abrirConstancia()"
                    class="flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 rounded-xl transition text-sm shadow-sm shadow-blue-500/20">
                <i class="fa-solid fa-file-lines text-xs"></i>
                Constancia
            </button>
            <button @click="abrirTicket()"
                    class="flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl transition text-sm shadow-sm">
                <i class="fa-solid fa-receipt text-xs"></i>
                Ticket PDF
            </button>
        </div>
        <button @click="modalFecha = false"
                class="w-full mt-2 text-slate-500 hover:text-slate-700 text-sm py-2 rounded-xl hover:bg-slate-50 transition">
            Cancelar
        </button>
    </div>
</div>

<!-- Header actions -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 fade-up">
    <div>
        <h2 class="text-slate-500 text-sm">{{ $alumnos->total() }} alumno(s) encontrado(s)</h2>
    </div>
    <div class="flex flex-wrap gap-2">
        <button @click="openCarnets()"
                class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-blue-400 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-xl px-4 py-2 text-sm font-semibold transition shadow-sm"
                title="Imprimir carnets de los alumnos seleccionados">
            <i class="fa-solid fa-id-card"></i>
            Carnets
            <span x-show="selected.length > 0" x-cloak
                  class="bg-blue-600 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 leading-none"
                  x-text="selected.length"></span>
        </button>
        <form action="{{ route('alumnos.generar-qr') }}" method="POST">
            @csrf
            <button type="submit"
                    onclick="return confirm('¿Generar QR para todos los alumnos que no tienen uno?')"
                    class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl px-4 py-2 text-sm font-semibold transition shadow-sm">
                <i class="fa-solid fa-qrcode"></i> Generar QRs
            </button>
        </form>
        <a href="{{ route('alumnos.create') }}"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white rounded-xl px-4 py-2 text-sm font-semibold transition shadow-sm shadow-blue-500/20">
            <i class="fa-solid fa-user-plus"></i> Nuevo Alumno
        </a>
    </div>
</div>

<!-- ── CHIPS DE NIVEL / GRADO ── -->
@php
    $nivelesConfig = [
        'INICIAL'    => ['color'=>'pink',  'icon'=>'fa-child-reaching', 'label'=>'Inicial',
                         'grados'=>['3 Años','4 Años','5 Años']],
        'PRIMARIA'   => ['color'=>'blue',  'icon'=>'fa-book-open',      'label'=>'Primaria',
                         'grados'=>['1er Grado','2do Grado','3er Grado','4to Grado','5to Grado','6to Grado']],
        'SECUNDARIA' => ['color'=>'purple','icon'=>'fa-graduation-cap', 'label'=>'Secundaria',
                         'grados'=>['1A','1B','2do Grado','3er Grado','4to Grado','5to Grado']],
    ];
    $nivelActual = request('nivel');
    $gradoActual = request('grado_seccion');
    $colorMap    = ['pink'=>'text-pink-700 bg-pink-100 border-pink-300 hover:bg-pink-200',
                    'blue'=>'text-blue-700 bg-blue-100 border-blue-300 hover:bg-blue-200',
                    'purple'=>'text-purple-700 bg-purple-100 border-purple-300 hover:bg-purple-200'];
    $activeMap   = ['pink'=>'bg-pink-600 text-white border-pink-600',
                    'blue'=>'bg-blue-700 text-white border-blue-700',
                    'purple'=>'bg-purple-700 text-white border-purple-700'];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-3 fade-up-d1">
    <div class="flex items-center gap-2 mb-3 flex-wrap">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wide shrink-0">Filtrar por nivel y grado:</span>

        {{-- Chip "Todos" --}}
        <a href="{{ route('alumnos.index', array_filter(['buscar'=>request('buscar'),'activo'=>request('activo')])) }}"
           class="inline-flex items-center gap-1 px-3 py-1 rounded-full border text-xs font-semibold transition
               {{ !$nivelActual ? 'bg-slate-700 text-white border-slate-700' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
            <i class="fa-solid fa-layer-group text-[9px]"></i> Todos
            @if(!$nivelActual)
                <span class="bg-white/25 text-white text-[9px] px-1 rounded-full">{{ $alumnos->total() }}</span>
            @endif
        </a>
    </div>

    @foreach($nivelesConfig as $nivelKey => $cfg)
    @php
        $cnt        = $gradoConteos->get($nivelKey);
        $totalNivel = $cnt ? $cnt->sum('total') : 0;
        $colorBase  = $colorMap[$cfg['color']];
        $colorActive= $activeMap[$cfg['color']];
        $esteNivel  = $nivelActual === $nivelKey;
    @endphp
    @if($totalNivel > 0)
    <div class="flex items-start gap-2 flex-wrap mb-2 last:mb-0">
        {{-- Chip del nivel --}}
        <a href="{{ route('alumnos.index', array_filter(['nivel'=>$nivelKey,'buscar'=>request('buscar'),'activo'=>request('activo')])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-bold transition shrink-0
               {{ $esteNivel && !$gradoActual ? $colorActive : $colorBase }}">
            <i class="fa-solid {{ $cfg['icon'] }} text-[10px]"></i>
            {{ $cfg['label'] }}
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded-full
                {{ $esteNivel && !$gradoActual ? 'bg-white/25 text-white' : 'bg-white/80 text-slate-600' }}">
                {{ $totalNivel }}
            </span>
        </a>

        {{-- Chips de grados --}}
        <div class="flex flex-wrap gap-1">
            @foreach($cfg['grados'] as $grado)
            @php
                $cntGrado = $cnt ? $cnt->firstWhere('grado_seccion', $grado)?->total : 0;
                $esteGrado = $esteNivel && $gradoActual === $grado;
            @endphp
            @if($cntGrado > 0)
            <a href="{{ route('alumnos.index', array_filter(['nivel'=>$nivelKey,'grado_seccion'=>$grado,'buscar'=>request('buscar'),'activo'=>request('activo')])) }}"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border text-[11px] font-semibold transition
                   {{ $esteGrado ? $colorActive : $colorBase }}">
                {{ $grado }}
                <span class="text-[9px] font-bold">{{ $cntGrado }}</span>
            </a>
            @endif
            @endforeach
        </div>
    </div>
    @endif
    @endforeach
</div>

<!-- Filters (buscar + estado + limpiar) -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 px-4 py-3 mb-5 fade-up-d2">
    <form action="{{ route('alumnos.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        @if($nivelActual)<input type="hidden" name="nivel" value="{{ $nivelActual }}">@endif
        @if($gradoActual)<input type="hidden" name="grado_seccion" value="{{ $gradoActual }}">@endif

        <div class="flex-1 min-w-44">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Buscar</label>
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Nombre, apellidos o DNI..."
                       class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition">
            </div>
        </div>

        <div class="min-w-28">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Estado</label>
            <select name="activo" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                <option value="">Todos</option>
                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm shadow-blue-500/20">
                <i class="fa-solid fa-search mr-1"></i>Buscar
            </button>
            <a href="{{ route('alumnos.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-sm transition" title="Limpiar filtros">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

        @if($nivelActual || $gradoActual)
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
            <i class="fa-solid fa-filter text-blue-500 text-[10px]"></i>
            Filtrando:
            @if($nivelActual)<strong>{{ $nivelActual }}</strong>@endif
            @if($gradoActual)<span class="mx-1">→</span><strong>{{ $gradoActual }}</strong>@endif
        </div>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden fade-up-d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="px-4 py-3 w-10">
                        @php $pageIds = $alumnos->pluck('id')->toArray(); @endphp
                        <input type="checkbox"
                               @change="toggleAll($event.target.checked, {{ json_encode($pageIds) }})"
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Alumno</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">DNI</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Nivel / Grado</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Sede</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Descuento</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Estado</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($alumnos as $alumno)
                    <tr class="hover:bg-blue-50/30 transition group">
                        <td class="px-4 py-3">
                            <input type="checkbox" :value="{{ $alumno->id }}"
                                   x-model="selected"
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($alumno->foto_path)
                                    <img src="{{ Storage::url($alumno->foto_path) }}" alt=""
                                         class="w-9 h-9 rounded-full object-cover border-2 border-slate-200 shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center shrink-0 border border-blue-200/50">
                                        <span class="text-blue-700 text-xs font-bold">
                                            {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidos, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-800 leading-tight">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</p>
                                    <p class="text-slate-400 text-xs">{{ $alumno->sexo ?? 'N/D' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $alumno->dni }}</td>
                        <td class="px-4 py-3">
                            @if($alumno->nivel_academico)
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg
                                    {{ $alumno->nivel_academico === 'INICIAL' ? 'bg-pink-500 text-white' : ($alumno->nivel_academico === 'PRIMARIA' ? 'bg-blue-600 text-white' : 'bg-violet-600 text-white') }}">
                                    {{ $alumno->nivel_academico }}
                                </span>
                                <p class="text-slate-500 text-xs mt-0.5 font-medium">{{ $alumno->grado_seccion }}</p>
                            @else
                                <span class="text-slate-300 text-xs">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($alumno->sede)
                                <span class="inline-flex items-center gap-1 text-xs bg-slate-700 text-white px-2.5 py-1 rounded-lg font-semibold">
                                    <i class="fa-solid fa-building-columns text-[9px]"></i>
                                    {{ $alumno->sede->nombre }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($alumno->tipo_descuento !== 'ninguno' && $alumno->monto_descuento > 0)
                                <span class="text-xs text-emerald-700 font-semibold">S/ {{ number_format($alumno->monto_descuento, 2) }}</span>
                                <br><span class="text-xs text-slate-400 capitalize">{{ $alumno->tipo_descuento }}</span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($alumno->activo)
                                <span class="inline-flex items-center gap-1.5 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-green-200">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-red-200">
                                    <i class="fa-solid fa-circle-xmark text-[10px]"></i> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-70 group-hover:opacity-100 transition">
                                <a href="{{ route('alumnos.show', $alumno) }}" title="Ver detalle"
                                   class="p-1.5 text-slate-400 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('alumnos.edit', $alumno) }}" title="Editar"
                                   class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                    <i class="fa-solid fa-pen text-sm"></i>
                                </a>
                                <a href="{{ route('pagos.alumno', $alumno) }}" title="Pagos"
                                   class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                    <i class="fa-solid fa-money-bill text-sm"></i>
                                </a>
                                <a href="{{ route('alumnos.carnets') }}?ids={{ $alumno->id }}" target="_blank" title="Imprimir carnet"
                                   class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <i class="fa-solid fa-id-card text-sm"></i>
                                </a>
                                <button type="button"
                                        @click="abrirModalFecha({{ $alumno->id }}, '{{ route('alumnos.constancia', $alumno) }}', '{{ route('alumnos.ticket-pdf', $alumno) }}')"
                                        title="Ticket / Constancia"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa-solid fa-receipt text-sm"></i>
                                </button>
                                {{-- Crear cuenta alumno --}}
                                <form action="{{ route('alumnos.crear-cuenta', $alumno) }}" method="POST"
                                      onsubmit="return confirm('¿Crear cuenta de acceso para {{ addslashes($alumno->nombres) }}? La contraseña inicial será su DNI.')">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $alumno->user ? 'Ya tiene cuenta' : 'Crear cuenta de acceso' }}"
                                            class="p-1.5 rounded-lg transition
                                                {{ $alumno->user ? 'text-green-500 bg-green-50 cursor-default' : 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' }}">
                                        <i class="fa-solid {{ $alumno->user ? 'fa-user-check' : 'fa-user-plus' }} text-sm"></i>
                                    </button>
                                </form>
                                <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este alumno? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Eliminar"
                                            class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i class="fa-solid fa-users-slash text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 font-semibold">No se encontraron alumnos</p>
                                <a href="{{ route('alumnos.create') }}" class="text-blue-700 hover:underline text-sm font-medium">
                                    Registrar primer alumno →
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($alumnos->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                Mostrando {{ $alumnos->firstItem() }}–{{ $alumnos->lastItem() }} de {{ $alumnos->total() }}
            </p>
            {{ $alumnos->links() }}
        </div>
    @endif
</div>

</div>{{-- x-data --}}
@endsection
