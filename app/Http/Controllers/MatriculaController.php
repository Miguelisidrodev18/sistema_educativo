<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\PagoMatricula;
use App\Models\Sede;
use App\Imports\MatriculasImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $query = Matricula::with(['alumno', 'sede', 'pagoMatricula']);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo_matricula', 'like', "%{$buscar}%")
                  ->orWhereHas('alumno', fn($a) => $a
                      ->where('nombres', 'like', "%{$buscar}%")
                      ->orWhere('apellidos', 'like', "%{$buscar}%")
                      ->orWhere('dni', 'like', "%{$buscar}%")
                  );
            });
        }

        if ($request->filled('periodo')) {
            $query->where('periodo', $request->periodo);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel_academico', $request->nivel);
        }

        $matriculas = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $sedes      = Sede::where('activo', true)->get();
        $periodos   = Matricula::distinct()->pluck('periodo')->sortDesc();

        return view('matriculas.index', compact('matriculas', 'sedes', 'periodos'));
    }

    public function create()
    {
        $alumnos = Alumno::where('activo', true)->orderBy('apellidos')->get();
        $sedes   = Sede::where('activo', true)->get();
        return view('matriculas.create', compact('alumnos', 'sedes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumno_id'        => 'required|exists:alumnos,id',
            'periodo'          => 'required|integer|min:2020|max:2099',
            'nivel_academico'  => 'required|in:INICIAL,PRIMARIA,SECUNDARIA',
            'grado_seccion'    => 'required|string|max:50',
            'situacion'        => 'required|in:ALUMNO NUEVO,TRASLADADO,REPITENTE',
            'modalidad_pago'   => 'required|in:contado,mensual',
            'sede_id'          => 'nullable|exists:sedes,id',
            'monto_matricula'  => 'nullable|numeric|min:0',
            'pension_mensual'  => 'nullable|numeric|min:0',
            'numero_pensiones' => 'nullable|integer|min:1|max:12',
        ]);

        // Generate unique code
        $data['codigo_matricula'] = 'MAT-' . $data['periodo'] . '-' . str_pad(
            Matricula::whereYear('created_at', $data['periodo'])->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $matricula = Matricula::create($data);

        // Create payment record
        PagoMatricula::create([
            'matricula_id'     => $matricula->id,
            'monto_matricula'  => $request->monto_matricula ?? 0,
            'pension_mensual'  => $request->pension_mensual ?? 0,
            'numero_pensiones' => $request->numero_pensiones ?? 10,
            'estado_pago'      => 'PENDIENTE',
        ]);

        return redirect()->route('matriculas.index')
            ->with('success', "Matrícula {$data['codigo_matricula']} registrada correctamente.");
    }

    public function show(Matricula $matricula)
    {
        $matricula->load(['alumno', 'sede', 'pagoMatricula']);
        return view('matriculas.show', compact('matricula'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new MatriculasImport, $request->file('archivo'));
            return back()->with('success', 'Matrículas importadas correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}
