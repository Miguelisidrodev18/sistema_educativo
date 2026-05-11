<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::with(['sede', 'apoderado']);

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

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        $alumnos = $query->orderBy('apellidos')->paginate(20)->withQueryString();
        $sedes   = Sede::where('activo', true)->get();

        return view('alumnos.index', compact('alumnos', 'sedes'));
    }

    public function create()
    {
        $sedes      = Sede::where('activo', true)->get();
        $apoderados = Apoderado::orderBy('apellidos')->get();
        return view('alumnos.create', compact('sedes', 'apoderados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dni'                   => 'required|string|max:15|unique:alumnos,dni',
            'nombres'               => 'required|string|max:100',
            'apellidos'             => 'required|string|max:100',
            'fecha_nacimiento'      => 'nullable|date',
            'sexo'                  => 'nullable|in:MASCULINO,FEMENINO',
            'ciudad'                => 'nullable|string|max:80',
            'direccion'             => 'nullable|string|max:200',
            'nivel_academico'       => 'nullable|in:INICIAL,PRIMARIA,SECUNDARIA',
            'grado_seccion'         => 'nullable|string|max:50',
            'repitencia'            => 'boolean',
            'apoderado_id'          => 'nullable|exists:apoderados,id',
            'sede_id'               => 'nullable|exists:sedes,id',
            'tipo_descuento'        => 'nullable|in:ninguno,hermanos,beca,otro',
            'monto_descuento'       => 'nullable|numeric|min:0',
            'descripcion_descuento' => 'nullable|string|max:255',
            'foto'                  => 'nullable|image|max:2048',
        ]);

        $data['repitencia']    = $request->boolean('repitencia');
        $data['tipo_descuento'] = $data['tipo_descuento'] ?? 'ninguno';
        $data['monto_descuento'] = $data['monto_descuento'] ?? 0;

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('fotos_alumnos', 'public');
        }

        $alumno = Alumno::create($data);

        // Generate QR code
        $this->generarQR($alumno);

        return redirect()->route('alumnos.show', $alumno)
            ->with('success', 'Alumno registrado correctamente.');
    }

    public function show(Alumno $alumno)
    {
        $alumno->load(['sede', 'apoderado', 'matriculas.pagoMatricula', 'pagosPension']);
        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        $sedes      = Sede::where('activo', true)->get();
        $apoderados = Apoderado::orderBy('apellidos')->get();
        return view('alumnos.edit', compact('alumno', 'sedes', 'apoderados'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'dni'                   => 'required|string|max:15|unique:alumnos,dni,' . $alumno->id,
            'nombres'               => 'required|string|max:100',
            'apellidos'             => 'required|string|max:100',
            'fecha_nacimiento'      => 'nullable|date',
            'sexo'                  => 'nullable|in:MASCULINO,FEMENINO',
            'ciudad'                => 'nullable|string|max:80',
            'direccion'             => 'nullable|string|max:200',
            'nivel_academico'       => 'nullable|in:INICIAL,PRIMARIA,SECUNDARIA',
            'grado_seccion'         => 'nullable|string|max:50',
            'repitencia'            => 'boolean',
            'apoderado_id'          => 'nullable|exists:apoderados,id',
            'sede_id'               => 'nullable|exists:sedes,id',
            'tipo_descuento'        => 'nullable|in:ninguno,hermanos,beca,otro',
            'monto_descuento'       => 'nullable|numeric|min:0',
            'descripcion_descuento' => 'nullable|string|max:255',
            'activo'                => 'boolean',
            'foto'                  => 'nullable|image|max:2048',
        ]);

        $data['repitencia'] = $request->boolean('repitencia');
        $data['activo']     = $request->boolean('activo');

        if ($request->hasFile('foto')) {
            if ($alumno->foto_path) {
                Storage::disk('public')->delete($alumno->foto_path);
            }
            $data['foto_path'] = $request->file('foto')->store('fotos_alumnos', 'public');
        }

        $alumno->update($data);

        return redirect()->route('alumnos.show', $alumno)
            ->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy(Alumno $alumno)
    {
        if ($alumno->foto_path) {
            Storage::disk('public')->delete($alumno->foto_path);
        }
        if ($alumno->qr_code_path) {
            Storage::disk('public')->delete($alumno->qr_code_path);
        }
        $alumno->delete();
        return redirect()->route('alumnos.index')
            ->with('success', 'Alumno eliminado correctamente.');
    }

    public function ticketPdf(Alumno $alumno)
    {
        $alumno->load(['sede', 'apoderado', 'matriculas.pagoMatricula']);
        $pdf = Pdf::loadView('pdf.ticket', compact('alumno'))
            ->setPaper('a5', 'portrait');
        return $pdf->stream("ticket_{$alumno->dni}.pdf");
    }

    public function constanciaPdf(Alumno $alumno)
    {
        $alumno->load(['sede', 'apoderado', 'matriculas']);
        $pdf = Pdf::loadView('pdf.constancia', compact('alumno'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream("constancia_{$alumno->dni}.pdf");
    }

    private function generarQR(Alumno $alumno): void
    {
        try {
            $qrContent = route('alumnos.show', $alumno->id);
            $qrDir     = storage_path('app/public/qr_codes');
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0755, true);
            }
            $filename = "qr_{$alumno->dni}.svg";
            $path     = "{$qrDir}/{$filename}";
            QrCode::format('svg')->size(200)->generate($qrContent, $path);
            $alumno->update(['qr_code_path' => "qr_codes/{$filename}"]);
        } catch (\Exception $e) {
            // QR generation failure is non-critical
        }
    }
}
