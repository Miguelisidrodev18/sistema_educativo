<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $sedeId = $request->sede_id ?: session('sede_id');
        $query  = Alumno::with(['sede', 'apoderado', 'user']);

        if ($sedeId) {
            $query->where('sede_id', $sedeId);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('nivel')) {
            $query->where('nivel_academico', $request->nivel);
        }

        if ($request->filled('grado_seccion')) {
            $query->where('grado_seccion', $request->grado_seccion);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        $alumnos = $query->orderBy('apellidos')->paginate(20)->withQueryString();
        $sedes   = Sede::where('activo', true)->get();

        // Conteo de alumnos por nivel y grado (respetando el filtro de sede)
        $gradosQ = Alumno::where('activo', true)->whereNotNull('nivel_academico');
        if ($sedeId) $gradosQ->where('sede_id', $sedeId);
        $gradoConteos = $gradosQ
            ->selectRaw('nivel_academico, grado_seccion, COUNT(*) as total')
            ->groupBy('nivel_academico', 'grado_seccion')
            ->get()
            ->groupBy('nivel_academico');

        return view('alumnos.index', compact('alumnos', 'sedes', 'sedeId', 'gradoConteos'));
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

    public function constanciaHtml(Alumno $alumno, Request $request)
    {
        $alumno->load(['sede', 'apoderado', 'matriculas.pagoMatricula']);

        $fechaEmision = $this->parsarFechaEmision($request->get('fecha'));

        $telLimpio = preg_replace('/\D/', '', $alumno->apoderado?->telefono ?? '');
        if (strlen($telLimpio) === 9) $telLimpio = '51' . $telLimpio;
        $waMsg = "✅ *CONSTANCIA DE MATRÍCULA*\n"
               . "━━━━━━━━━━━━━━━━━\n"
               . "📋 *Alumno:* " . strtoupper($alumno->apellidos . ', ' . $alumno->nombres) . "\n"
               . "🪪 *DNI:* " . $alumno->dni . "\n"
               . "📚 *Nivel:* " . ($alumno->nivel_academico ?? '—') . "\n"
               . "🎓 *Grado:* " . ($alumno->grado_seccion ?? '—') . "\n"
               . "📅 *Periodo:* " . date('Y') . "\n"
               . "👤 *Apoderado:* " . strtoupper($alumno->apoderado?->nombre_completo ?? '—') . "\n"
               . "📆 *Fecha:* " . $fechaEmision . "\n"
               . "━━━━━━━━━━━━━━━━━\n"
               . "Colegio Pre JEDSON - Arequipa";
        $waUrl = $telLimpio ? 'https://wa.me/' . $telLimpio . '?text=' . urlencode($waMsg) : '';

        return view('alumnos.constancia', compact('alumno', 'fechaEmision', 'waUrl'));
    }

    public function ticketPdf(Alumno $alumno, Request $request)
    {
        $alumno->load(['sede', 'apoderado', 'matriculas.pagoMatricula']);
        $fechaEmision = $this->parsarFechaEmision($request->get('fecha'));
        $pdf = Pdf::loadView('pdf.ticket', compact('alumno', 'fechaEmision'))
            ->setPaper('a5', 'portrait');
        return $pdf->stream("ticket_{$alumno->dni}.pdf");
    }

    private function parsarFechaEmision(?string $fecha): string
    {
        $meses = ['','enero','febrero','marzo','abril','mayo','junio',
                  'julio','agosto','septiembre','octubre','noviembre','diciembre'];
        if ($fecha && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $d = \Carbon\Carbon::parse($fecha);
            return $d->day . ' de ' . $meses[$d->month] . ' de ' . $d->year;
        }
        return now()->day . ' de ' . $meses[now()->month] . ' de ' . now()->year;
    }

    public function constanciaPdf(Alumno $alumno)
    {
        $alumno->load(['sede', 'apoderado', 'matriculas']);
        $pdf = Pdf::loadView('pdf.constancia', compact('alumno'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream("constancia_{$alumno->dni}.pdf");
    }

    public function carnets(Request $request)
    {
        $ids = array_filter(array_map('intval', explode(',', $request->get('ids', ''))));

        if (empty($ids)) {
            abort(400, 'No se especificaron alumnos.');
        }

        $alumnos = Alumno::with(['sede'])
            ->whereIn('id', $ids)
            ->orderBy('apellidos')
            ->get();

        return view('alumnos.carnets', compact('alumnos'));
    }

    public function crearCuenta(Alumno $alumno)
    {
        if ($alumno->user) {
            return back()->with('info', "Este alumno ya tiene cuenta: {$alumno->user->email}");
        }

        $email = $alumno->dni . '@estudiante.jedson.edu.pe';

        $user = User::create([
            'name'                 => $alumno->nombres . ' ' . $alumno->apellidos,
            'dni'                  => $alumno->dni,
            'email'                => $email,
            'password'             => Hash::make($alumno->dni),
            'user_type'            => 'estudiante',
            'sede_id'              => $alumno->sede_id,
            'alumno_id'            => $alumno->id,
            'activo'               => true,
            'must_change_password' => true,
        ]);
        $user->assignRole('estudiante');

        return back()->with('success', "Cuenta creada. El alumno inicia sesión con DNI y contraseña: {$alumno->dni}");
    }

    public function generarQrMasivo()
    {
        $sinQr = Alumno::whereNull('qr_code_path')->orWhere('qr_code_path', '')->get();
        $count = 0;
        foreach ($sinQr as $alumno) {
            $this->generarQR($alumno);
            $count++;
        }
        return back()->with('success', "QR generados para {$count} alumno(s).");
    }

    private function generarQR(Alumno $alumno): void
    {
        try {
            // Formato unificado: ALU:{id} — compatible con lector de asistencias
            $qrContent = "ALU:{$alumno->id}";
            $qrDir     = storage_path('app/public/qr_codes');
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0755, true);
            }
            $filename = "qr_alu_{$alumno->id}.svg";
            $path     = "{$qrDir}/{$filename}";
            QrCode::format('svg')->size(200)->generate($qrContent, $path);
            $alumno->update(['qr_code_path' => "qr_codes/{$filename}"]);
        } catch (\Exception $e) {
            // non-critical
        }
    }
}
