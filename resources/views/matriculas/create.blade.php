@extends('layouts.app')

@section('title', 'Nueva Matrícula')
@section('page-title', 'Registrar Matrícula')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('matriculas.index') }}" class="text-slate-500 hover:text-blue-700 text-sm flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Volver a Matrículas
        </a>
    </div>

    <form action="{{ route('matriculas.store') }}" method="POST">
        @csrf

        <div class="space-y-6">

            <!-- Datos de Matrícula -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-slate-800 font-semibold text-base mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-blue-700"></i> Datos de Matrícula
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Alumno <span class="text-red-500">*</span></label>
                        <select name="alumno_id" required
                                class="w-full border @error('alumno_id') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar alumno...</option>
                            @foreach($alumnos as $al)
                                <option value="{{ $al->id }}" {{ (old('alumno_id') == $al->id || request('alumno_id') == $al->id) ? 'selected' : '' }}>
                                    {{ $al->apellidos }}, {{ $al->nombres }} &mdash; DNI: {{ $al->dni }}
                                </option>
                            @endforeach
                        </select>
                        @error('alumno_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Período <span class="text-red-500">*</span></label>
                        <input type="number" name="periodo" value="{{ old('periodo', date('Y')) }}" min="2020" max="2099"
                               class="w-full border @error('periodo') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('periodo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sede</label>
                        <select name="sede_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sin sede</option>
                            @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nivel Académico <span class="text-red-500">*</span></label>
                        <select name="nivel_academico" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar</option>
                            <option value="INICIAL"    {{ old('nivel_academico') === 'INICIAL'    ? 'selected' : '' }}>Inicial</option>
                            <option value="PRIMARIA"   {{ old('nivel_academico') === 'PRIMARIA'   ? 'selected' : '' }}>Primaria</option>
                            <option value="SECUNDARIA" {{ old('nivel_academico') === 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Grado / Sección <span class="text-red-500">*</span></label>
                        <input type="text" name="grado_seccion" value="{{ old('grado_seccion') }}" maxlength="50"
                               placeholder="Ej: 3ro A"
                               class="w-full border @error('grado_seccion') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Situación <span class="text-red-500">*</span></label>
                        <select name="situacion" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="ALUMNO NUEVO" {{ old('situacion', 'ALUMNO NUEVO') === 'ALUMNO NUEVO' ? 'selected' : '' }}>Alumno Nuevo</option>
                            <option value="TRASLADADO"   {{ old('situacion') === 'TRASLADADO'   ? 'selected' : '' }}>Trasladado</option>
                            <option value="REPITENTE"    {{ old('situacion') === 'REPITENTE'    ? 'selected' : '' }}>Repitente</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Modalidad de Pago <span class="text-red-500">*</span></label>
                        <select name="modalidad_pago" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="mensual"  {{ old('modalidad_pago', 'mensual') === 'mensual'  ? 'selected' : '' }}>Mensual</option>
                            <option value="contado"  {{ old('modalidad_pago') === 'contado'  ? 'selected' : '' }}>Contado</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Montos -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-slate-800 font-semibold text-base mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill text-green-600"></i> Montos de Pago
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Monto Matrícula (S/)</label>
                        <input type="number" name="monto_matricula" value="{{ old('monto_matricula', 0) }}" step="0.01" min="0"
                               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pensión Mensual (S/)</label>
                        <input type="number" name="pension_mensual" value="{{ old('pension_mensual', 0) }}" step="0.01" min="0"
                               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">N° de Pensiones</label>
                        <input type="number" name="numero_pensiones" value="{{ old('numero_pensiones', 10) }}" min="1" max="12"
                               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Registrar Matrícula
                </button>
                <a href="{{ route('matriculas.index') }}"
                   class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </a>
            </div>

        </div>
    </form>
</div>

@endsection
