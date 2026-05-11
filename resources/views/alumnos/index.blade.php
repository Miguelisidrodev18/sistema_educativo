@extends('layouts.app')

@section('title', 'Alumnos')
@section('page-title', 'Gestión de Alumnos')

@section('content')

<!-- Header actions -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-slate-700 text-sm">{{ $alumnos->total() }} alumno(s) encontrado(s)</h2>
    </div>
    <a href="{{ route('alumnos.create') }}"
       class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg px-4 py-2 text-sm font-semibold transition">
        <i class="fa-solid fa-user-plus"></i> Nuevo Alumno
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="{{ route('alumnos.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">

        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Buscar</label>
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Nombre, apellidos o DNI..."
                       class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
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

        <div class="min-w-36">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nivel</label>
            <select name="nivel" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="INICIAL" {{ request('nivel') === 'INICIAL' ? 'selected' : '' }}>Inicial</option>
                <option value="PRIMARIA" {{ request('nivel') === 'PRIMARIA' ? 'selected' : '' }}>Primaria</option>
                <option value="SECUNDARIA" {{ request('nivel') === 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
            </select>
        </div>

        <div class="min-w-28">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Estado</label>
            <select name="activo" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-filter mr-1"></i>Filtrar
            </button>
            <a href="{{ route('alumnos.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Alumno</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">DNI</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nivel / Grado</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Sede</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Descuento</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($alumnos as $alumno)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($alumno->foto_path)
                                    <img src="{{ Storage::url($alumno->foto_path) }}" alt=""
                                         class="w-9 h-9 rounded-full object-cover border-2 border-slate-200">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-700 text-xs font-bold">
                                            {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidos, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</p>
                                    <p class="text-slate-400 text-xs">{{ $alumno->sexo ?? 'N/D' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600 font-mono text-xs">{{ $alumno->dni }}</td>
                        <td class="px-4 py-3">
                            @if($alumno->nivel_academico)
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full
                                    {{ $alumno->nivel_academico === 'INICIAL' ? 'bg-pink-100 text-pink-700' : ($alumno->nivel_academico === 'PRIMARIA' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                    {{ $alumno->nivel_academico }}
                                </span>
                                <span class="text-slate-500 text-xs ml-1">{{ $alumno->grado_seccion }}</span>
                            @else
                                <span class="text-slate-400 text-xs">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">{{ $alumno->sede?->nombre ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($alumno->tipo_descuento !== 'ninguno' && $alumno->monto_descuento > 0)
                                <span class="text-xs text-emerald-700 font-medium">
                                    S/ {{ number_format($alumno->monto_descuento, 2) }}
                                </span>
                                <br><span class="text-xs text-slate-400 capitalize">{{ $alumno->tipo_descuento }}</span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($alumno->activo)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-circle text-[8px]"></i> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded-full">
                                    <i class="fa-solid fa-circle text-[8px]"></i> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('alumnos.show', $alumno) }}" title="Ver detalle"
                                   class="p-1.5 text-slate-400 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('alumnos.edit', $alumno) }}" title="Editar"
                                   class="p-1.5 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition">
                                    <i class="fa-solid fa-pen text-sm"></i>
                                </a>
                                <a href="{{ route('pagos.alumno', $alumno) }}" title="Pagos"
                                   class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                    <i class="fa-solid fa-money-bill text-sm"></i>
                                </a>
                                <a href="{{ route('alumnos.ticket-pdf', $alumno) }}" title="Ticket PDF" target="_blank"
                                   class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <i class="fa-solid fa-file-pdf text-sm"></i>
                                </a>
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
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fa-solid fa-users-slash text-4xl text-slate-300"></i>
                                <p class="text-slate-500 font-medium">No se encontraron alumnos</p>
                                <a href="{{ route('alumnos.create') }}" class="text-blue-700 hover:underline text-sm">
                                    Registrar primer alumno
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($alumnos->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $alumnos->links() }}
        </div>
    @endif
</div>

@endsection
