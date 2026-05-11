@extends('layouts.app')

@section('title', 'Nuevo Alumno')
@section('page-title', 'Registrar Nuevo Alumno')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('alumnos.index') }}" class="text-slate-500 hover:text-blue-700 text-sm flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left"></i> Volver a Alumnos
        </a>
    </div>

    <form action="{{ route('alumnos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Personal Data -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Datos Personales -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-slate-800 font-semibold text-base mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-user text-blue-700"></i> Datos Personales
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">DNI / Documento <span class="text-red-500">*</span></label>
                            <input type="text" name="dni" value="{{ old('dni') }}" maxlength="15"
                                   class="w-full border @error('dni') border-red-400 bg-red-50 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: 73001234" required>
                            @error('dni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nombres <span class="text-red-500">*</span></label>
                            <input type="text" name="nombres" value="{{ old('nombres') }}" maxlength="100"
                                   class="w-full border @error('nombres') border-red-400 bg-red-50 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('nombres') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Apellidos <span class="text-red-500">*</span></label>
                            <input type="text" name="apellidos" value="{{ old('apellidos') }}" maxlength="100"
                                   class="w-full border @error('apellidos') border-red-400 bg-red-50 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('apellidos') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Sexo</label>
                            <select name="sexo" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar</option>
                                <option value="MASCULINO" {{ old('sexo') === 'MASCULINO' ? 'selected' : '' }}>Masculino</option>
                                <option value="FEMENINO"  {{ old('sexo') === 'FEMENINO'  ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Ciudad</label>
                            <input type="text" name="ciudad" value="{{ old('ciudad', 'Arequipa') }}" maxlength="80"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" maxlength="200"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Urb. / Jr. / Av...">
                        </div>

                    </div>
                </div>

                <!-- Datos Académicos -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-slate-800 font-semibold text-base mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-book text-blue-700"></i> Datos Académicos
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nivel Académico</label>
                            <select name="nivel_academico" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar</option>
                                <option value="INICIAL"    {{ old('nivel_academico') === 'INICIAL'    ? 'selected' : '' }}>Inicial</option>
                                <option value="PRIMARIA"   {{ old('nivel_academico') === 'PRIMARIA'   ? 'selected' : '' }}>Primaria</option>
                                <option value="SECUNDARIA" {{ old('nivel_academico') === 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Grado / Sección</label>
                            <input type="text" name="grado_seccion" value="{{ old('grado_seccion') }}" maxlength="50"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: 3ro A">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="repitencia" name="repitencia" value="1"
                                   {{ old('repitencia') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-700 border-slate-300 rounded focus:ring-blue-500">
                            <label for="repitencia" class="text-sm font-medium text-slate-700">Es repitente</label>
                        </div>
                    </div>
                </div>

                <!-- Descuento -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-slate-800 font-semibold text-base mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-tag text-green-600"></i> Descuento
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tipo</label>
                            <select name="tipo_descuento" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="ninguno" {{ old('tipo_descuento', 'ninguno') === 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                                <option value="hermanos" {{ old('tipo_descuento') === 'hermanos' ? 'selected' : '' }}>Hermanos</option>
                                <option value="beca"     {{ old('tipo_descuento') === 'beca'     ? 'selected' : '' }}>Beca</option>
                                <option value="otro"     {{ old('tipo_descuento') === 'otro'     ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Monto (S/)</label>
                            <input type="number" name="monto_descuento" value="{{ old('monto_descuento', 0) }}" step="0.01" min="0"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Descripción</label>
                            <input type="text" name="descripcion_descuento" value="{{ old('descripcion_descuento') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Side panel -->
            <div class="space-y-6">

                <!-- Photo -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6"
                     x-data="{ preview: null }">
                    <h3 class="text-slate-800 font-semibold text-base mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-camera text-blue-700"></i> Foto
                    </h3>
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-24 h-24 rounded-full bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden">
                            <img x-show="preview" :src="preview" class="w-full h-full object-cover rounded-full">
                            <i x-show="!preview" class="fa-solid fa-user text-slate-400 text-3xl"></i>
                        </div>
                        <label class="cursor-pointer bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fa-solid fa-upload mr-1"></i> Subir foto
                            <input type="file" name="foto" accept="image/*" class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="text-slate-400 text-xs text-center">JPG, PNG. Máx 2MB</p>
                    </div>
                </div>

                <!-- Apoderado -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-slate-800 font-semibold text-base mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-person text-blue-700"></i> Apoderado
                    </h3>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Seleccionar</label>
                        <select name="apoderado_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sin apoderado</option>
                            @foreach($apoderados as $ap)
                                <option value="{{ $ap->id }}" {{ old('apoderado_id') == $ap->id ? 'selected' : '' }}>
                                    {{ $ap->apellidos }}, {{ $ap->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex flex-col gap-2">
                    <button type="submit"
                            class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Alumno
                    </button>
                    <a href="{{ route('alumnos.index') }}"
                       class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection
