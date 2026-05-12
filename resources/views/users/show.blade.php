@extends('layouts.app')

@section('title', 'Perfil: ' . $user->name)
@section('page-title', 'Perfil de Usuario')

@section('content')

@php
$badges = [
    'administrador' => ['bg-blue-100 text-blue-700',   'fa-shield-halved'],
    'auxiliar'      => ['bg-teal-100 text-teal-700',   'fa-clipboard-list'],
    'docente'       => ['bg-violet-100 text-violet-700','fa-chalkboard-user'],
    'estudiante'    => ['bg-amber-100 text-amber-700',  'fa-graduation-cap'],
];
[$badgeClass, $badgeIcon] = $badges[$user->user_type] ?? ['bg-slate-100 text-slate-600','fa-user'];

$gradosPorNivel = [
    'inicial'    => ['3 AÑOS','4 AÑOS','5 AÑOS'],
    'primaria'   => ['PRIMERO','SEGUNDO','TERCERO','CUARTO','QUINTO','SEXTO'],
    'secundaria' => ['PRIMERO A','PRIMERO B','SEGUNDO','TERCERO','CUARTO','QUINTO'],
];
$materias = [
    'Matemáticas','Comunicación','Ciencias Naturales','Historia','Geografía',
    'Arte y Cultura','Educación Física','Inglés','Computación','Religión',
    'Personal Social','Formación Ciudadana','Educación para el Trabajo',
    'Tutoría','Química','Física','Biología','Literatura',
];

$nivelColors = [
    'inicial'   => 'bg-amber-100 text-amber-700 border-amber-200',
    'primaria'  => 'bg-teal-100 text-teal-700 border-teal-200',
    'secundaria'=> 'bg-indigo-100 text-indigo-700 border-indigo-200',
];
@endphp

<!-- Botón volver -->
<div class="mb-4">
    <a href="{{ route('users.index') }}"
       class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-700 text-sm font-medium transition-colors">
        <i class="fa-solid fa-arrow-left text-xs"></i> Volver a Usuarios
    </a>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ══ COLUMNA IZQUIERDA: perfil ══ --}}
    <div class="lg:col-span-1 space-y-4">

        <!-- Tarjeta perfil -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div style="background: linear-gradient(135deg,#1e3a5f,#2563eb,#4f46e5);" class="h-20 relative">
                <div class="absolute -bottom-8 left-5">
                    <div class="w-16 h-16 rounded-2xl
                        @switch($user->user_type)
                            @case('administrador') bg-blue-600 @break
                            @case('auxiliar')      bg-teal-600 @break
                            @case('docente')       bg-violet-600 @break
                            @default               bg-slate-500
                        @endswitch
                        flex items-center justify-center shadow-lg border-4 border-white text-white text-2xl font-black">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
            </div>
            <div class="pt-10 pb-5 px-5">
                <div class="flex items-start justify-between gap-2 flex-wrap">
                    <div>
                        <h2 class="text-slate-800 font-bold text-base leading-tight">{{ $user->name }}</h2>
                        <p class="text-slate-500 text-xs mt-0.5">{{ $user->email }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                        <i class="fa-solid {{ $badgeIcon }}"></i> {{ ucfirst($user->user_type) }}
                    </span>
                </div>

                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="fa-solid fa-id-card w-4 text-slate-400"></i>
                        <span class="font-mono">{{ $user->dni ?? 'Sin DNI' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="fa-solid fa-location-dot w-4 text-slate-400"></i>
                        <span>{{ $user->sede?->nombre ?? 'Sin sede asignada' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle w-4 text-slate-400"></i>
                        @if($user->activo)
                            <span class="text-green-600 font-medium">Activo</span>
                        @else
                            <span class="text-slate-400">Inactivo</span>
                        @endif
                    </div>
                </div>

                @if($user->qr_code_path && Storage::disk('public')->exists($user->qr_code_path))
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-3">
                    <img src="{{ Storage::url($user->qr_code_path) }}"
                         class="w-14 h-14 rounded-xl border border-slate-200 bg-white p-1" alt="QR">
                    <div>
                        <p class="text-xs font-semibold text-slate-700">Código QR</p>
                        <p class="text-[11px] text-slate-400">USR:{{ $user->id }}</p>
                        <a href="{{ Storage::url($user->qr_code_path) }}" target="_blank"
                           class="text-[11px] text-blue-600 hover:underline">Ver completo</a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Acceso rápido para docente/auxiliar -->
        @if(in_array($user->user_type, ['docente','auxiliar']))
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Acceso rápido</p>
            <div class="space-y-2">
                <a href="{{ route('asistencias.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 transition group">
                    <div class="w-8 h-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clipboard-check text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-tight">Registrar Asistencia</p>
                        <p class="text-[11px] text-blue-500 leading-tight">Alumnos de mis aulas</p>
                    </div>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                </a>
                <a href="{{ route('asistencias.qr') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 transition group">
                    <div class="w-8 h-8 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-qrcode text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-tight">Lector QR</p>
                        <p class="text-[11px] text-purple-500 leading-tight">Escanear código QR</p>
                    </div>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-60"></i>
                </a>
            </div>
        </div>
        @endif

    </div>

    {{-- ══ COLUMNA DERECHA: asignaciones ══ --}}
    <div class="lg:col-span-2 space-y-4">

        <!-- Asignaciones actuales -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chalkboard text-blue-600"></i>
                    Aulas y Cursos a cargo
                </h3>
                <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                    {{ $user->asignaciones->count() }}
                </span>
            </div>

            @if($user->asignaciones->isNotEmpty())
            <div class="divide-y divide-slate-100">
                @foreach($user->asignaciones as $asig)
                @php $colorClass = $nivelColors[$asig->nivel] ?? 'bg-slate-100 text-slate-600 border-slate-200'; @endphp
                <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition group">
                    <!-- Nivel badge -->
                    <span class="shrink-0 text-xs font-bold px-2 py-1 rounded-lg border {{ $colorClass }}">
                        {{ $asig->nivel_label }}
                    </span>
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-slate-800 font-semibold text-sm truncate">{{ $asig->grado_seccion }}</p>
                        <div class="flex items-center gap-2 flex-wrap mt-0.5">
                            @if($asig->materia)
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <i class="fa-solid fa-book-open text-[10px]"></i> {{ $asig->materia }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">Tutor / Encargado de aula</span>
                            @endif
                            @if($asig->sede)
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-[10px]"></i> {{ $asig->sede->nombre }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <!-- Botón asistencia rápida -->
                    <a href="{{ route('asistencias.index') }}?nivel={{ $asig->nivel }}&grado={{ urlencode($asig->grado_seccion) }}"
                       class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 transition shrink-0"
                       title="Registrar asistencia de este grupo">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span class="hidden md:inline">Asistencia</span>
                    </a>
                    <!-- Eliminar -->
                    <form action="{{ route('users.asignaciones.destroy', [$user, $asig]) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar esta asignación?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:bg-red-50 hover:text-red-500 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-10 text-center">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-chalkboard text-slate-400 text-xl"></i>
                </div>
                <p class="text-slate-400 text-sm">Sin asignaciones todavía</p>
                <p class="text-slate-300 text-xs mt-1">Agregue aulas o cursos abajo</p>
            </div>
            @endif
        </div>

        <!-- Formulario agregar asignación -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200"
             x-data="{
                nivel: '',
                grados: {
                    inicial:    ['3 AÑOS','4 AÑOS','5 AÑOS'],
                    primaria:   ['PRIMERO','SEGUNDO','TERCERO','CUARTO','QUINTO','SEXTO'],
                    secundaria: ['PRIMERO A','PRIMERO B','SEGUNDO','TERCERO','CUARTO','QUINTO']
                }
             }">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-green-600"></i>
                    Agregar asignación
                </h3>
            </div>
            <form action="{{ route('users.asignaciones.store', $user) }}" method="POST" class="p-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Sede -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Sede <span class="text-slate-400 font-normal">(opcional)</span>
                        </label>
                        <select name="sede_id"
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">— Todas las sedes —</option>
                            @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}"
                                    {{ $user->sede_id == $sede->id ? 'selected' : '' }}>
                                    {{ $sede->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nivel -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Nivel <span class="text-red-500">*</span>
                        </label>
                        <select name="nivel" x-model="nivel" required
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">— Seleccionar —</option>
                            <option value="inicial">Inicial</option>
                            <option value="primaria">Primaria</option>
                            <option value="secundaria">Secundaria</option>
                        </select>
                    </div>

                    <!-- Grado -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Grado / Aula <span class="text-red-500">*</span>
                        </label>
                        <select name="grado_seccion" required
                                :disabled="!nivel"
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white disabled:opacity-50">
                            <option value="">— Primero elige nivel —</option>
                            <template x-if="nivel">
                                <template x-for="g in grados[nivel]" :key="g">
                                    <option :value="g" x-text="g"></option>
                                </template>
                            </template>
                        </select>
                    </div>

                    <!-- Materia -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Materia / Curso <span class="text-slate-400 font-normal">(opcional para tutores)</span>
                        </label>
                        <select name="materia"
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">— Tutor / Encargado de aula —</option>
                            @foreach($materias as $mat)
                                <option value="{{ $mat }}">{{ $mat }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-plus"></i> Agregar asignación
                    </button>
                </div>
            </form>
        </div>

        <!-- Resumen por nivel (si tiene asignaciones) -->
        @if($user->asignaciones->isNotEmpty())
        @php $porNivel = $user->asignaciones->groupBy('nivel'); @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Resumen</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach(['inicial','primaria','secundaria'] as $niv)
                @if(isset($porNivel[$niv]))
                @php $colorClass = $nivelColors[$niv]; @endphp
                <div class="rounded-xl border p-3 {{ $colorClass }}">
                    <p class="text-[11px] font-bold uppercase tracking-wider opacity-70">{{ ucfirst($niv) }}</p>
                    <p class="text-xl font-black mt-0.5">{{ $porNivel[$niv]->count() }}</p>
                    <p class="text-[11px] opacity-70 mt-0.5">
                        {{ $porNivel[$niv]->count() === 1 ? 'grupo' : 'grupos' }}
                    </p>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>

@endsection
