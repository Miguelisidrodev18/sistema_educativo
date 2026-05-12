<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Matricula;
use App\Models\PagoMatricula;
use App\Models\Sede;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Procesa UNA hoja del Excel.
 * El nombre de la hoja se convierte en grado_seccion.
 * Encabezados reales del colegio (en español).
 */
class MatriculasSheetImport implements ToCollection, WithHeadingRow
{
    public int    $procesados = 0;
    public int    $omitidos   = 0;
    public array  $errores    = [];

    public function __construct(
        private string  $nivel,
        private string  $gradoSeccion,
        private ?int    $sedeId,
        private int     $periodo,
        private float   $montoMatDefault,
        private float   $pensionDefault,
    ) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $arr = $row->toArray();

            // Saltar filas vacías o sin DNI
            $dniAl = trim($this->col($arr, ['dni alumno', 'dni_alumno', 'dni']));
            if (empty($dniAl)) continue;

            try {
                $this->procesarFila($arr, $dniAl);
                $this->procesados++;
            } catch (\Throwable $e) {
                $this->errores[] = "DNI {$dniAl}: " . $e->getMessage();
                $this->omitidos++;
            }
        }
    }

    private function procesarFila(array $row, string $dniAl): void
    {
        $nombres   = trim($this->col($row, ['nombres', 'nombres_alumno']));
        $apellidos = trim($this->col($row, ['apellidos', 'apellidos_alumno']));
        if (empty($nombres) || empty($apellidos)) return;

        // Fecha nacimiento: acepta DD/MM/YYYY o YYYY-MM-DD
        $fnacRaw = trim($this->col($row, ['fecha nac', 'fecha_nac', 'fecha nacimiento', 'fecha_nacimiento_alumno', 'fecha nac dd mm yyyy sexo m f']));
        $fnac    = $this->parseFecha($fnacRaw, $this->nivel, $this->gradoSeccion);

        // Sexo: MASCULINO→M, FEMENINO→F
        $sexoRaw = strtoupper(trim($this->col($row, ['sexo', 'sexo m f', 'sexo_alumno'])));
        $sexo    = str_starts_with($sexoRaw, 'F') ? 'FEMENINO' : 'MASCULINO';

        $ciudad    = trim($this->col($row, ['ciudad', 'ciudad_alumno'])) ?: 'Arequipa';
        $direccion = trim($this->col($row, ['direccion', 'dirección', 'direccion_alumno'])) ?: 'Sin registro';
        $instProc  = trim($this->col($row, ['inst procedencia', 'inst. procedencia', 'institucion_procedencia']));
        $repitRaw  = strtoupper(trim($this->col($row, ['repite', 'repitencia', 'repite si no'])));
        $repitente = $repitRaw === 'SI';
        $situacion = strtoupper(trim($this->col($row, ['situacion', 'situación', 'situacion_alumno']))) ?: 'ALUMNO NUEVO';
        if (!in_array($situacion, ['ALUMNO NUEVO','REPITENTE','TRASLADADO'])) $situacion = 'ALUMNO NUEVO';

        // Apoderado
        $dniApo    = trim($this->col($row, ['dni apoderado', 'dni_apoderado']));
        $nomApo    = trim($this->col($row, ['nombres apoderado', 'nombres_apoderado'])) ?: 'Por Completar';
        $apeApo    = trim($this->col($row, ['apellidos apoderado', 'apellidos_apoderado'])) ?: $apellidos;
        $telApo    = trim($this->col($row, ['telefono', 'teléfono', 'telefono_apoderado'])) ?: '999999999';
        $emailApo  = trim($this->col($row, ['email', 'email_apoderado']));
        $parentesco= trim($this->col($row, ['parentesco', 'tipo_relacion', 'tipo relacion'])) ?: 'Madre';
        $ocupApo   = trim($this->col($row, ['ocupacion', 'ocupación', 'ocupacion_apoderado'])) ?: 'Sin especificar';

        // Si no tiene DNI apoderado, derivar del alumno
        if (!preg_match('/^\d{7,8}$/', $dniApo)) {
            $dniApo = strlen($dniAl) === 8
                ? substr($dniAl, 0, 7) . '0'
                : str_pad(abs(crc32($apellidos . $telApo)) % 99999999, 8, '0', STR_PAD_LEFT);
        }
        $dniApo = str_pad($dniApo, 8, '0', STR_PAD_LEFT);

        // Montos
        $montoMat = floatval($this->col($row, ['monto matricula', 'monto matrícula', 'monto_matricula'])) ?: $this->montoMatDefault;
        $pension  = floatval($this->col($row, ['pension mensual', 'pensión mensual', 'monto_mensual', 'pension_mensual'])) ?: $this->pensionDefault;

        DB::transaction(function () use (
            $dniAl,$nombres,$apellidos,$fnac,$sexo,$ciudad,$direccion,$instProc,$repitente,$situacion,
            $dniApo,$nomApo,$apeApo,$telApo,$emailApo,$parentesco,$ocupApo,
            $montoMat,$pension
        ) {
            // 1. Apoderado
            $apoderado = Apoderado::firstOrCreate(
                ['dni' => $dniApo],
                ['nombres'=>$nomApo,'apellidos'=>$apeApo,'telefono'=>$telApo,
                 'email'=>$emailApo ?: null,'parentesco'=>$parentesco,'direccion'=>'Sin registro']
            );

            // 2. Alumno
            $alumno = Alumno::where('dni', $dniAl)->first();
            if (!$alumno) {
                $alumno = Alumno::create([
                    'dni'                 => $dniAl,
                    'nombres'             => $nombres,
                    'apellidos'           => $apellidos,
                    'fecha_nacimiento'    => $fnac,
                    'sexo'                => $sexo,
                    'ciudad'              => $ciudad,
                    'direccion'           => $direccion,
                    'nivel_academico'     => $this->nivel,
                    'grado_seccion'       => $this->gradoSeccion,
                    'repitencia'          => $repitente,
                    'apoderado_id'        => $apoderado->id,
                    'sede_id'             => $this->sedeId,
                    'tipo_descuento'      => 'ninguno',
                    'monto_descuento'     => 0,
                    'activo'              => true,
                ]);
            } else {
                $alumno->update([
                    'nivel_academico' => $this->nivel,
                    'grado_seccion'   => $this->gradoSeccion,
                    'apoderado_id'    => $apoderado->id,
                    'sede_id'         => $this->sedeId ?? $alumno->sede_id,
                ]);
            }

            // 3. Matrícula (no duplicar mismo año)
            if (Matricula::where('alumno_id', $alumno->id)->where('periodo', $this->periodo)->exists()) {
                $this->omitidos++;
                return;
            }

            $codigo = 'MAT-' . $this->periodo . '-' . str_pad(
                Matricula::where('periodo', $this->periodo)->count() + 1, 4, '0', STR_PAD_LEFT
            );

            $matricula = Matricula::create([
                'codigo_matricula' => $codigo,
                'alumno_id'        => $alumno->id,
                'periodo'          => $this->periodo,
                'nivel_academico'  => $this->nivel,
                'grado_seccion'    => $this->gradoSeccion,
                'situacion'        => $situacion,
                'modalidad_pago'   => 'mensual',
                'sede_id'          => $this->sedeId ?? $alumno->sede_id,
            ]);

            PagoMatricula::create([
                'matricula_id'     => $matricula->id,
                'monto_matricula'  => $montoMat,
                'pension_mensual'  => $pension,
                'numero_pensiones' => 10,
                'estado_pago'      => 'PENDIENTE',
            ]);
        });
    }

    // Busca en el array con múltiples posibles nombres de cabecera (normalized)
    private function col(array $row, array $keys): string
    {
        $normalized = [];
        foreach ($row as $k => $v) {
            $normalized[strtolower(trim(preg_replace('/\s+/', ' ', $k)))] = $v;
        }
        foreach ($keys as $key) {
            $k = strtolower(trim($key));
            if (isset($normalized[$k]) && $normalized[$k] !== null) {
                return (string) $normalized[$k];
            }
            // Búsqueda parcial
            foreach ($normalized as $nk => $nv) {
                if (str_contains($nk, $k) && $nv !== null) {
                    return (string) $nv;
                }
            }
        }
        return '';
    }

    private function parseFecha(string $raw, string $nivel, string $grado): string
    {
        $raw = trim($raw);
        // DD/MM/YYYY
        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $raw, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        // YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return $raw;
        }
        // Excel serial date (número entero)
        if (is_numeric($raw) && $raw > 1000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$raw)->format('Y-m-d');
            } catch (\Throwable $e) {}
        }
        // Estimar por nivel/grado
        return $this->estimarFecha($nivel, $grado);
    }

    private function estimarFecha(string $nivel, string $grado): string
    {
        $anio = (int) date('Y');
        $g = strtoupper($grado);
        $num = 1;
        foreach ([
            'PRIMERO'=>1,'1RO'=>1,'1ER'=>1,'1A'=>1,'1B'=>1,
            'SEGUNDO'=>2,'2DO'=>2,'TERCERO'=>3,'3ER'=>3,
            'CUARTO'=>4,'4TO'=>4,'QUINTO'=>5,'5TO'=>5,'SEXTO'=>6,'6TO'=>6,
        ] as $k=>$v) {
            if (str_contains($g, $k)) { $num=$v; break; }
        }
        if ($nivel === 'INICIAL')     return ($anio - 3 - $num) . '-06-15';
        if ($nivel === 'SECUNDARIA')  return ($anio - 11 - $num) . '-06-15';
        return ($anio - 5 - $num) . '-06-15';
    }
}
