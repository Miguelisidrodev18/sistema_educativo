<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\PagoPension;
use App\Models\Sede;
use App\Imports\PagosImport;
use App\Imports\PagosBancarioImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $anio  = $request->get('anio', date('Y'));
        $query = Alumno::with(['sede', 'pagosPension' => fn($q) => $q->where('anio', $anio)])
            ->where('activo', true);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel_academico', $request->nivel);
        }

        $alumnos = $query->orderBy('apellidos')->paginate(20)->withQueryString();
        $sedes   = Sede::where('activo', true)->get();
        $meses   = PagoPension::MESES;

        // Stats
        $totalAlumnos   = Alumno::where('activo', true)->count();
        $pagadosHoy     = PagoPension::whereDate('fecha_pago', today())->count();
        $totalRecaudado = PagoPension::where('anio', $anio)->sum('monto');

        return view('pagos.index', compact(
            'alumnos', 'sedes', 'meses', 'anio',
            'totalAlumnos', 'pagadosHoy', 'totalRecaudado'
        ));
    }

    public function alumno(Alumno $alumno, Request $request)
    {
        $anio  = $request->get('anio', date('Y'));
        $pagos = PagoPension::where('alumno_id', $alumno->id)
            ->where('anio', $anio)
            ->get()
            ->keyBy('mes_pagado');

        $meses = PagoPension::MESES;
        $alumno->load(['sede', 'apoderado', 'matriculas.pagoMatricula']);

        return view('pagos.alumno', compact('alumno', 'pagos', 'meses', 'anio'));
    }

    public function registrarPension(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'mes_pagado'    => 'required|string',
            'anio'          => 'required|integer',
            'monto'         => 'required|numeric|min:0',
            'metodo_pago'   => 'required|string|max:30',
            'numero_recibo' => 'nullable|string|max:60',
            'fecha_pago'    => 'required|date',
        ]);

        $data['alumno_id'] = $alumno->id;
        $data['sede_id']   = $alumno->sede_id;

        PagoPension::updateOrCreate(
            [
                'alumno_id'  => $alumno->id,
                'mes_pagado' => $data['mes_pagado'],
                'anio'       => $data['anio'],
            ],
            $data
        );

        return back()->with('success', "Pago de {$data['mes_pagado']} {$data['anio']} registrado correctamente.");
    }

    public function importar(Request $request)
    {
        $request->validate(['archivo' => 'required|mimes:xlsx,xls|max:10240']);

        try {
            $import = new PagosBancarioImport();
            Excel::import($import, $request->file('archivo'));

            $msg = "{$import->registrados} pago(s) registrado(s)";
            if ($import->actualizados)  $msg .= ", {$import->actualizados} monto(s) actualizado(s)";
            if ($import->duplicados)    $msg .= ", {$import->duplicados} duplicado(s) omitido(s)";
            if ($import->noEncontrados) $msg .= ", {$import->noEncontrados} alumno(s) no encontrado(s)";

            return response()->json([
                'success'      => true,
                'message'      => $msg,
                'registrados'  => $import->registrados,
                'actualizados' => $import->actualizados,
                'detalle'      => $import->detalle,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
