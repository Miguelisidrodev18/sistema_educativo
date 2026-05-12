<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EstudianteController extends Controller
{
    private function alumno()
    {
        return auth()->user()->alumno;
    }

    public function dashboard()
    {
        $user   = auth()->user()->load('alumno.sede', 'alumno.matriculas', 'alumno.pagosPension', 'alumno.asistencias');
        $alumno = $user->alumno;

        $stats = [];
        if ($alumno) {
            $totalDias    = $alumno->asistencias()->whereYear('fecha', date('Y'))->count();
            $presentes    = $alumno->asistencias()->whereYear('fecha', date('Y'))->where('estado', 'PRESENTE')->count();
            $pctAsistencia = $totalDias > 0 ? round($presentes / $totalDias * 100) : 0;

            $matricula     = $alumno->matriculas()->where('periodo', date('Y'))->latest()->first();
            $pagosMes      = $alumno->pagosPension()->whereMonth('fecha_pago', now()->month)->count();

            $stats = compact('totalDias', 'presentes', 'pctAsistencia', 'matricula', 'pagosMes');
        }

        return view('estudiante.dashboard', compact('user', 'alumno', 'stats'));
    }

    public function perfil()
    {
        $user   = auth()->user()->load('alumno.apoderado', 'alumno.sede');
        $alumno = $user->alumno;
        return view('estudiante.perfil', compact('user', 'alumno'));
    }

    public function actualizarPerfil(Request $request)
    {
        $alumno = $this->alumno();
        abort_if(!$alumno, 404);

        $data = $request->validate([
            'ciudad'    => 'nullable|string|max:60',
            'direccion' => 'nullable|string|max:120',
            'foto'      => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('foto')) {
            if ($alumno->foto_path) {
                Storage::disk('public')->delete($alumno->foto_path);
            }
            $data['foto_path'] = $request->file('foto')->store('fotos', 'public');
        }
        unset($data['foto']);

        $alumno->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function pagos()
    {
        $alumno = $this->alumno();
        abort_if(!$alumno, 404);

        $alumno->load('pagosPension', 'matriculas', 'sede');
        $matricula = $alumno->matriculas()->where('periodo', date('Y'))->latest()->first();

        $cfg = \App\Helpers\ConfiguracionColegio::get();
        $mesInicio = $cfg['mes_inicio'] ?? 1;
        $mesFin    = $cfg['mes_fin'] ?? 12;
        $mesesActivos = \App\Helpers\ConfiguracionColegio::mesesActivos();

        $pagosPorMes = $alumno->pagosPension()
            ->whereYear('fecha_pago', date('Y'))
            ->get()
            ->keyBy(fn($p) => (int) date('n', strtotime($p->fecha_pago)));

        return view('estudiante.pagos', compact('alumno', 'matricula', 'mesesActivos', 'pagosPorMes', 'mesInicio'));
    }

    public function asistencias()
    {
        $alumno = $this->alumno();
        abort_if(!$alumno, 404);

        $mes  = request('mes', date('n'));
        $anio = request('anio', date('Y'));

        $registros = $alumno->asistencias()
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha')
            ->get();

        $resumen = [
            'presente'    => $registros->where('estado', 'PRESENTE')->count(),
            'tardanza'    => $registros->where('estado', 'TARDANZA')->count(),
            'ausente'     => $registros->where('estado', 'AUSENTE')->count(),
            'justificado' => $registros->where('estado', 'JUSTIFICADO')->count(),
        ];
        $total = $registros->count();
        $pct   = $total > 0 ? round(($resumen['presente'] + $resumen['justificado']) / $total * 100) : 0;

        return view('estudiante.asistencias', compact('alumno', 'registros', 'resumen', 'total', 'pct', 'mes', 'anio'));
    }

    public function matricula()
    {
        $alumno = $this->alumno();
        abort_if(!$alumno, 404);

        $alumno->load('matriculas', 'apoderado', 'sede');
        $matricula = $alumno->matriculas()->where('periodo', date('Y'))->latest()->first();

        return view('estudiante.matricula', compact('alumno', 'matricula'));
    }

    // ── Cambio de contraseña ─────────────────────────────
    public function showCambiarPassword()
    {
        return view('auth.cambiar-password');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = auth()->user();
        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        $next = $user->isEstudiante()
            ? route('estudiante.dashboard')
            : route('dashboard');

        return redirect($next)->with('success', '¡Contraseña actualizada! Bienvenido al sistema.');
    }
}
