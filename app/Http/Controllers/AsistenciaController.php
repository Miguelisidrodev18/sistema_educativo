<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\AsistenciaDocente;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', today()->toDateString());
        $query = Alumno::with(['sede', 'asistencias' => fn($q) => $q->whereDate('fecha', $fecha)])
            ->where('activo', true);

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel_academico', $request->nivel);
        }

        if ($request->filled('grado_seccion')) {
            $query->where('grado_seccion', $request->grado_seccion);
        }

        $alumnos = $query->orderBy('apellidos')->get();
        $sedes   = Sede::where('activo', true)->get();

        $stats = [
            'total'      => $alumnos->count(),
            'presentes'  => $alumnos->filter(fn($a) => $a->asistencias->first()?->estado === 'PRESENTE')->count(),
            'ausentes'   => $alumnos->filter(fn($a) => $a->asistencias->first()?->estado === 'AUSENTE')->count(),
            'tardanza'   => $alumnos->filter(fn($a) => $a->asistencias->first()?->estado === 'TARDANZA')->count(),
            'sin_marcar' => $alumnos->filter(fn($a) => $a->asistencias->isEmpty())->count(),
        ];

        return view('asistencias.index', compact('alumnos', 'sedes', 'fecha', 'stats'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'fecha'     => 'required|date',
            'estado'    => 'required|in:PRESENTE,AUSENTE,TARDANZA,JUSTIFICADO',
        ]);

        Asistencia::updateOrCreate(
            [
                'alumno_id' => $request->alumno_id,
                'fecha'     => $request->fecha,
            ],
            [
                'estado'        => $request->estado,
                'hora_registro' => now()->toTimeString(),
                'sede_id'       => Alumno::find($request->alumno_id)->sede_id,
            ]
        );

        return back()->with('success', 'Asistencia registrada.');
    }

    public function registrarMasivo(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'asistencias' => 'required|array',
        ]);

        foreach ($request->asistencias as $alumnoId => $estado) {
            if (in_array($estado, ['PRESENTE', 'AUSENTE', 'TARDANZA', 'JUSTIFICADO'])) {
                $alumno = Alumno::find($alumnoId);
                if ($alumno) {
                    Asistencia::updateOrCreate(
                        ['alumno_id' => $alumnoId, 'fecha' => $request->fecha],
                        ['estado' => $estado, 'hora_registro' => now()->toTimeString(), 'sede_id' => $alumno->sede_id]
                    );
                }
            }
        }

        return back()->with('success', 'Asistencias registradas correctamente.');
    }

    public function docentes(Request $request)
    {
        $fecha = $request->get('fecha', today()->toDateString());
        $query = User::with(['sede', 'asistenciasDocente' => fn($q) => $q->whereDate('fecha', $fecha)])
            ->where('activo', true)
            ->whereIn('user_type', ['docente', 'auxiliar']);

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        $docentes = $query->orderBy('name')->get();
        $sedes    = Sede::where('activo', true)->get();

        $stats = [
            'total'      => $docentes->count(),
            'presentes'  => $docentes->filter(fn($d) => $d->asistenciasDocente->first()?->estado === 'PRESENTE')->count(),
            'ausentes'   => $docentes->filter(fn($d) => $d->asistenciasDocente->first()?->estado === 'AUSENTE')->count(),
            'tardanza'   => $docentes->filter(fn($d) => $d->asistenciasDocente->first()?->estado === 'TARDANZA')->count(),
            'sin_marcar' => $docentes->filter(fn($d) => $d->asistenciasDocente->isEmpty())->count(),
        ];

        return view('asistencias.docentes', compact('docentes', 'sedes', 'fecha', 'stats'));
    }

    public function registrarDocente(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha'   => 'required|date',
            'estado'  => 'required|in:PRESENTE,AUSENTE,TARDANZA,JUSTIFICADO',
        ]);

        AsistenciaDocente::updateOrCreate(
            ['user_id' => $request->user_id, 'fecha' => $request->fecha],
            [
                'estado'        => $request->estado,
                'hora_registro' => now()->toTimeString(),
                'sede_id'       => User::find($request->user_id)->sede_id,
            ]
        );

        return back()->with('success', 'Asistencia de docente registrada.');
    }

    public function registrarMasivoDocentes(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'asistencias' => 'required|array',
        ]);

        foreach ($request->asistencias as $userId => $estado) {
            if (in_array($estado, ['PRESENTE', 'AUSENTE', 'TARDANZA', 'JUSTIFICADO'])) {
                $user = User::find($userId);
                if ($user) {
                    AsistenciaDocente::updateOrCreate(
                        ['user_id' => $userId, 'fecha' => $request->fecha],
                        ['estado' => $estado, 'hora_registro' => now()->toTimeString(), 'sede_id' => $user->sede_id]
                    );
                }
            }
        }

        return back()->with('success', 'Asistencias de docentes registradas correctamente.');
    }

    public function qr()
    {
        return view('asistencias.qr');
    }

    public function registrarQr(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
        ]);

        $alumno = Alumno::find($request->alumno_id);

        Asistencia::updateOrCreate(
            ['alumno_id' => $alumno->id, 'fecha' => today()],
            ['estado' => 'PRESENTE', 'hora_registro' => now()->toTimeString(), 'sede_id' => $alumno->sede_id]
        );

        return response()->json([
            'success' => true,
            'mensaje' => "Asistencia registrada para {$alumno->nombres} {$alumno->apellidos}",
            'alumno'  => $alumno->nombre_completo,
            'hora'    => now()->format('H:i:s'),
        ]);
    }
}
