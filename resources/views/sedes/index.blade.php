@extends('layouts.app')

@section('title', 'Sedes')
@section('page-title', 'Gestión de Sedes')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ══ Lista de sedes ══ -->
    <div class="lg:col-span-2 fade-up">
        <div class="flex items-center justify-between mb-5">
            <p class="text-slate-500 text-sm">{{ $sedes->count() }} sede(s) registrada(s)</p>
        </div>

        <div class="space-y-3">
            @forelse($sedes as $sede)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center gap-4 transition hover:shadow-md"
                 x-data="{ editando: false }">

                <!-- Ícono sede -->
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm
                    {{ $sede->activo ? 'bg-blue-100' : 'bg-slate-100' }}">
                    <i class="fa-solid fa-building-columns text-xl {{ $sede->activo ? 'text-blue-600' : 'text-slate-400' }}"></i>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div x-show="!editando">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-slate-800">{{ $sede->nombre }}</h3>
                            @if($sede->activo)
                                <span class="text-[11px] font-semibold bg-green-100 text-green-700 px-2 py-0.5 rounded-full border border-green-200">Activa</span>
                            @else
                                <span class="text-[11px] font-semibold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Inactiva</span>
                            @endif
                            @if(session('sede_id') == $sede->id)
                                <span class="text-[11px] font-bold bg-blue-600 text-white px-2 py-0.5 rounded-full">
                                    <i class="fa-solid fa-check text-[9px]"></i> Seleccionada
                                </span>
                            @endif
                        </div>
                        @if($sede->direccion)
                            <p class="text-slate-400 text-xs mt-0.5">
                                <i class="fa-solid fa-location-dot mr-1"></i>{{ $sede->direccion }}
                            </p>
                        @endif
                        <div class="flex gap-4 mt-2">
                            <span class="text-xs text-slate-500">
                                <i class="fa-solid fa-users text-blue-400 mr-1"></i>
                                <strong>{{ $sede->alumnos_count }}</strong> alumnos
                            </span>
                            <span class="text-xs text-slate-500">
                                <i class="fa-solid fa-file-signature text-indigo-400 mr-1"></i>
                                <strong>{{ $sede->matriculas_count }}</strong> matrículas
                            </span>
                            @if($sede->telefono)
                            <span class="text-xs text-slate-500">
                                <i class="fa-solid fa-phone text-teal-400 mr-1"></i>{{ $sede->telefono }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Formulario editar (inline) -->
                    <div x-show="editando" x-cloak>
                        <form action="{{ route('sedes.update', $sede) }}" method="POST" class="flex flex-wrap gap-2 items-end">
                            @csrf @method('PUT')
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Nombre</label>
                                <input type="text" name="nombre" value="{{ $sede->nombre }}" required
                                       class="border border-slate-200 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Dirección</label>
                                <input type="text" name="direccion" value="{{ $sede->direccion }}"
                                       class="border border-slate-200 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Teléfono</label>
                                <input type="text" name="telefono" value="{{ $sede->telefono }}"
                                       class="border border-slate-200 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-32">
                            </div>
                            <div class="flex items-center gap-1.5">
                                <input type="checkbox" name="activo" id="activo_{{ $sede->id }}" value="1"
                                       {{ $sede->activo ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                                <label for="activo_{{ $sede->id }}" class="text-xs text-slate-600">Activa</label>
                            </div>
                            <button type="submit" class="bg-blue-700 text-white px-3 py-1.5 rounded-xl text-xs font-bold transition hover:bg-blue-800">
                                <i class="fa-solid fa-save mr-1"></i>Guardar
                            </button>
                            <button type="button" @click="editando=false" class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-xl text-xs font-semibold transition hover:bg-slate-200">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex flex-col gap-2 shrink-0" x-show="!editando">
                    @if(session('sede_id') != $sede->id)
                        <form action="{{ route('sedes.seleccionar', $sede) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-xl transition flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i> Seleccionar
                            </button>
                        </form>
                    @endif
                    <button @click="editando=true"
                            class="text-xs font-semibold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-xl transition flex items-center gap-1.5">
                        <i class="fa-solid fa-pen"></i> Editar
                    </button>
                    <form action="{{ route('sedes.toggle', $sede) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full text-xs font-semibold px-3 py-1.5 rounded-xl transition flex items-center gap-1.5
                                {{ $sede->activo ? 'bg-red-50 hover:bg-red-100 text-red-600 border border-red-200' : 'bg-green-50 hover:bg-green-100 text-green-700 border border-green-200' }}">
                            <i class="fa-solid {{ $sede->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            {{ $sede->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </div>
            </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-building-columns text-3xl text-slate-300"></i>
                    </div>
                    <p class="text-slate-500 font-semibold">No hay sedes registradas</p>
                    <p class="text-slate-400 text-sm mt-1">Crea la primera sede usando el formulario</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ══ Formulario nueva sede ══ -->
    <div class="fade-up-d1">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sticky top-20">
            <h3 class="text-slate-800 font-bold text-base mb-5 flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-plus text-blue-600 text-sm"></i>
                </div>
                Nueva Sede
            </h3>

            <form action="{{ route('sedes.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" required
                           placeholder="Ej: Sede Central, Sede Norte..."
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition"
                           value="{{ old('nombre') }}">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Dirección</label>
                    <input type="text" name="direccion"
                           placeholder="Av. Principal 123, Arequipa"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition"
                           value="{{ old('direccion') }}">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Teléfono</label>
                    <input type="text" name="telefono"
                           placeholder="054-123456"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 focus:bg-white transition"
                           value="{{ old('telefono') }}">
                </div>

                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 rounded-xl transition shadow-sm shadow-blue-500/20 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-building-columns"></i>
                    Crear Sede
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
