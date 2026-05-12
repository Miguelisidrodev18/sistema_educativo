@extends('layouts.app')

@section('title', 'Usuarios del Sistema')
@section('page-title', 'Usuarios del Sistema')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-slate-500 text-sm mt-0.5">Administra las cuentas de acceso al sistema</p>
    </div>
    <div class="flex gap-2">
        <form action="{{ route('users.generar-qr-todos') }}" method="POST">
            @csrf
            <button type="submit"
                    onclick="return confirm('¿Generar QR para todos los docentes y auxiliares activos?')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fa-solid fa-qrcode"></i> Generar QRs
            </button>
        </form>
        <a href="{{ route('users.carnets') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <i class="fa-solid fa-id-card"></i> Imprimir Carnets
        </a>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<!-- Tabla -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-left">
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">DNI</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Correo</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Perfil</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sede</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors {{ !$user->activo ? 'opacity-60' : '' }}">

                    <!-- Nombre + avatar -->
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0
                                @switch($user->user_type)
                                    @case('administrador') bg-blue-600 @break
                                    @case('auxiliar')      bg-teal-600 @break
                                    @case('docente')       bg-violet-600 @break
                                    @default               bg-slate-400
                                @endswitch">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-slate-800">{{ $user->name }}</span>
                        </div>
                    </td>

                    <!-- DNI -->
                    <td class="px-5 py-4 text-slate-600 font-mono">{{ $user->dni ?? '—' }}</td>

                    <!-- Email -->
                    <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>

                    <!-- Perfil badge -->
                    <td class="px-5 py-4">
                        @php
                            $badges = [
                                'administrador' => 'bg-blue-100 text-blue-700',
                                'auxiliar'      => 'bg-teal-100 text-teal-700',
                                'docente'       => 'bg-violet-100 text-violet-700',
                                'estudiante'    => 'bg-amber-100 text-amber-700',
                            ];
                            $icons = [
                                'administrador' => 'fa-shield-halved',
                                'auxiliar'      => 'fa-clipboard-list',
                                'docente'       => 'fa-chalkboard-user',
                                'estudiante'    => 'fa-graduation-cap',
                            ];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badges[$user->user_type] ?? 'bg-slate-100 text-slate-600' }}">
                            <i class="fa-solid {{ $icons[$user->user_type] ?? 'fa-user' }}"></i>
                            {{ ucfirst($user->user_type) }}
                        </span>
                    </td>

                    <!-- Sede -->
                    <td class="px-5 py-4 text-slate-600">{{ $user->sede?->nombre ?? '—' }}</td>

                    <!-- Estado -->
                    <td class="px-5 py-4">
                        @if($user->activo)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactivo
                            </span>
                        @endif
                    </td>

                    <!-- Acciones -->
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- QR --}}
                            <form action="{{ route('users.generar-qr', $user) }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $user->qr_code_path ? 'Regenerar QR' : 'Generar QR' }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition
                                            {{ $user->qr_code_path ? 'bg-teal-50 hover:bg-teal-100 text-teal-700' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                                    <i class="fa-solid fa-qrcode"></i>
                                    {{ $user->qr_code_path ? 'QR ✓' : 'QR' }}
                                </button>
                            </form>
                            {{-- Ver QR --}}
                            @if($user->qr_code_path && Storage::disk('public')->exists($user->qr_code_path))
                                <a href="{{ Storage::url($user->qr_code_path) }}" target="_blank"
                                   title="Ver QR"
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endif
                            {{-- Toggle activo --}}
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.toggle', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
                                            {{ $user->activo ? 'bg-red-50 hover:bg-red-100 text-red-600' : 'bg-green-50 hover:bg-green-100 text-green-600' }}"
                                        onclick="return confirm('¿{{ $user->activo ? 'Desactivar' : 'Activar' }} a {{ $user->name }}?')">
                                    <i class="fa-solid {{ $user->activo ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                </button>
                            </form>
                            @else
                                <span class="text-xs text-slate-400 italic">Tú</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-users-slash text-3xl mb-2 block"></i>
                        No hay usuarios registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->count())
    <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 text-xs text-slate-500">
        {{ $users->count() }} usuario(s) en total
    </div>
    @endif
</div>

@endsection
