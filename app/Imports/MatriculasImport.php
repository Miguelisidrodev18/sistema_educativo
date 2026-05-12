<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Matricula;
use App\Models\PagoMatricula;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Importador multi-hoja.
 * Cada hoja del Excel = un grado_seccion.
 * El nivel (INICIAL/PRIMARIA/SECUNDARIA) viene del formulario.
 *
 * Encabezados que acepta (case-insensitive, búsqueda parcial):
 *   DNI ALUMNO, APELLIDOS, NOMBRES, FECHA NAC, SEXO, CIUDAD, DIRECCIÓN,
 *   INST. PROCEDENCIA, REPITE, SITUACIÓN,
 *   DNI APODERADO, APELLIDOS APODERADO, NOMBRES APODERADO, PARENTESCO,
 *   TELÉFONO, EMAIL, OCUPACIÓN, ESTADO CIVIL,
 *   MONTO MATRÍCULA, PENSIÓN MENSUAL
 */
class MatriculasImport
{
    public int   $procesados = 0;
    public int   $omitidos   = 0;
    public array $errores    = [];

    public function __construct(
        private string $nivel,
        private ?int   $sedeId,
        private int    $periodo,
        private float  $montoMatDefault,
        private float  $pensionDefault,
    ) {}

    public function import(string $filePath): void
    {
        $spreadsheet = IOFactory::load($filePath);

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $grado = $this->normalizarGrado(trim($sheetName));
            // formatData=false → fechas como serial numérico, strings sin alterar
            $rows  = $sheet->toArray(null, false, false, false);

            if (empty($rows) || count($rows) < 2) continue;

            // Fila 1 = cabeceras (normalizar: sin acentos, minúsculas)
            $headers = array_map(fn($h) => $this->norm((string)$h), $rows[0]);

            // Procesar desde fila 2
            foreach (array_slice($rows, 1) as $row) {
                $data = array_combine($headers, array_pad($row, count($headers), null));
                $dniAl = trim((string)($this->col($data, ['dni alumno', 'dni_alumno', 'dni']) ?? ''));
                if (empty($dniAl) || !is_numeric($dniAl)) continue;

                try {
                    $this->procesarFila($data, $dniAl, $grado);
                    $this->procesados++;
                } catch (\Throwable $e) {
                    $this->errores[] = "{$grado} | DNI {$dniAl}: " . $e->getMessage();
                    $this->omitidos++;
                }
            }
        }
    }

    private function procesarFila(array $row, string $dniAl, string $grado): void
    {
        $nombres   = trim((string)($this->col($row, ['nombres', 'nombres_alumno']) ?? ''));
        $apellidos = trim((string)($this->col($row, ['apellidos', 'apellidos_alumno']) ?? ''));
        if (empty($nombres) || empty($apellidos)) {
            $this->omitidos++;
            return;
        }

        $fnacRaw = (string)($this->col($row, ['fecha nac', 'fecha nacimiento', 'fecha_nacimiento_alumno']) ?? '');
        $fnac    = $this->parseFecha(trim($fnacRaw));

        $sexoRaw = strtoupper(trim((string)($this->col($row, ['sexo']) ?? 'M')));
        $sexo    = str_starts_with($sexoRaw, 'F') ? 'FEMENINO' : 'MASCULINO';

        $ciudad    = trim((string)($this->col($row, ['ciudad']) ?? '')) ?: 'Arequipa';
        $direccion = trim((string)($this->col($row, ['direccion', 'dirección']) ?? '')) ?: 'Sin registro';
        $instProc  = trim((string)($this->col($row, ['inst', 'procedencia']) ?? ''));

        $repitRaw  = strtoupper(trim((string)($this->col($row, ['repite', 'repitencia']) ?? 'NO')));
        $repitente = $repitRaw === 'SI';

        $situRaw  = strtoupper(trim((string)($this->col($row, ['situacion', 'situación']) ?? ''))) ?: 'ALUMNO NUEVO';
        $situacion = in_array($situRaw, ['ALUMNO NUEVO','REPITENTE','TRASLADADO']) ? $situRaw : 'ALUMNO NUEVO';

        // Apoderado
        $dniApo    = trim((string)($this->col($row, ['dni apoderado', 'dni_apoderado']) ?? ''));
        $nomApo    = trim((string)($this->col($row, ['nombres apoderado', 'nombres_apoderado']) ?? '')) ?: 'Por Completar';
        $apeApo    = trim((string)($this->col($row, ['apellidos apoderado', 'apellidos_apoderado']) ?? '')) ?: $apellidos;
        $telApo    = $this->parseTelefono($this->col($row, ['telefono', 'teléfono']) ?? '') ?: '999999999';
        $emailApo  = trim((string)($this->col($row, ['email']) ?? ''));
        $parentesco= trim((string)($this->col($row, ['parentesco', 'tipo relacion', 'tipo_relacion']) ?? '')) ?: 'Madre';
        $ocupApo   = trim((string)($this->col($row, ['ocupacion', 'ocupación']) ?? '')) ?: 'Sin especificar';

        if (!preg_match('/^\d{7,8}$/', $dniApo)) {
            $dniApo = strlen($dniAl) === 8
                ? substr($dniAl, 0, 7) . '0'
                : str_pad(abs(crc32($apellidos . $telApo)) % 99999999, 8, '0', STR_PAD_LEFT);
        }
        $dniApo = str_pad($dniApo, 8, '0', STR_PAD_LEFT);

        // Montos — parseMonto() limpia "S/ 300.00", "300,00", etc.
        $montoMat = $this->parseMonto($this->col($row, ['monto matricula', 'monto matri', 'monto_matricula'])) ?: $this->montoMatDefault;
        $pension  = $this->parseMonto($this->col($row, ['pension mensual', 'pension mens', 'pension_mensual', 'monto mensual', 'monto_mensual'])) ?: $this->pensionDefault;

        DB::transaction(function () use (
            $dniAl,$nombres,$apellidos,$fnac,$sexo,$ciudad,$direccion,$instProc,$repitente,$situacion,$grado,
            $dniApo,$nomApo,$apeApo,$telApo,$emailApo,$parentesco,$ocupApo,$montoMat,$pension
        ) {
            // 1. Apoderado — crea o actualiza teléfono/nombres si ya existe
            $apoderado = Apoderado::updateOrCreate(
                ['dni' => $dniApo],
                ['nombres'=>$nomApo,'apellidos'=>$apeApo,'telefono'=>$telApo,
                 'email'=>$emailApo ?: null,'parentesco'=>$parentesco,'direccion'=>'Sin registro']
            );

            // 2. Alumno
            $alumno = Alumno::where('dni', $dniAl)->first();
            if (!$alumno) {
                $alumno = Alumno::create([
                    'dni'             => $dniAl,
                    'nombres'         => $nombres,
                    'apellidos'       => $apellidos,
                    'fecha_nacimiento'=> $fnac,
                    'sexo'            => $sexo,
                    'ciudad'          => $ciudad,
                    'direccion'       => $direccion,
                    'nivel_academico' => $this->nivel,
                    'grado_seccion'   => $grado,
                    'repitencia'      => $repitente,
                    'apoderado_id'    => $apoderado->id,
                    'sede_id'         => $this->sedeId,
                    'tipo_descuento'  => 'ninguno',
                    'monto_descuento' => 0,
                    'activo'          => true,
                ]);
            } else {
                // Actualiza también fecha y sede (corrige datos malos de imports anteriores)
                $alumno->update([
                    'fecha_nacimiento' => $fnac,
                    'sexo'             => $sexo,
                    'nivel_academico'  => $this->nivel,
                    'grado_seccion'    => $grado,
                    'apoderado_id'     => $apoderado->id,
                    'sede_id'          => $this->sedeId ?? $alumno->sede_id,
                ]);
            }

            // 3. Matrícula — si ya existe, corregir montos y marcar PAGADO
            $matExiste = Matricula::where('alumno_id', $alumno->id)->where('periodo', $this->periodo)->first();
            if ($matExiste) {
                $matExiste->pagoMatricula?->update([
                    'monto_matricula' => $montoMat,
                    'pension_mensual' => $pension,
                    'estado_pago'     => 'PAGADO',
                ]);
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
                'grado_seccion'    => $grado,
                'situacion'        => $situacion,
                'modalidad_pago'   => 'mensual',
                'sede_id'          => $this->sedeId ?? $alumno->sede_id,
            ]);

            // Importación masiva = alumnos que ya pagaron
            PagoMatricula::create([
                'matricula_id'     => $matricula->id,
                'monto_matricula'  => $montoMat,
                'pension_mensual'  => $pension,
                'numero_pensiones' => 10,
                'estado_pago'      => 'PAGADO',
            ]);
        });
    }

    /** Busca en el array con múltiples posibles cabeceras (sin acentos, búsqueda parcial) */
    private function col(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            $k = $this->norm($key);
            foreach ($row as $rk => $rv) {
                $rkn = $this->norm((string)$rk);
                if ($rkn === $k || str_contains($rkn, $k)) return $rv;
            }
        }
        return null;
    }

    /**
     * Extrae el primer número de teléfono válido de una celda.
     * Maneja: "931084741", "931084741 o 987288805", "931-084-741", "931 084 741 / 987", etc.
     */
    private function parseTelefono(mixed $val): string
    {
        $raw = trim((string) $val);
        if (empty($raw)) return '';
        // Si es puramente numérico (int/float de Excel)
        if (is_numeric($raw)) return substr(preg_replace('/\D/', '', $raw), 0, 15);
        // Separar por delimitadores comunes: "o", "/", "-", ",", "y"
        $parts = preg_split('/\s*(\/|,|\s+o\s+|\s+y\s+)\s*/i', $raw);
        foreach ($parts as $part) {
            $digits = preg_replace('/\D/', '', $part);
            if (strlen($digits) >= 7) return substr($digits, 0, 15);
        }
        // Último recurso: tomar solo dígitos del string completo
        $digits = preg_replace('/\D/', '', $raw);
        return substr($digits, 0, 15);
    }

    /**
     * Extrae el valor numérico de una celda de monto.
     * Maneja: 300, 300.00, "S/ 300.00", "S/300", "300,00", etc.
     */
    private function parseMonto(mixed $val): float
    {
        if (is_null($val) || $val === '') return 0.0;
        if (is_int($val) || is_float($val)) return (float) $val;
        // Limpiar: quitar "S/", "$", espacios, y convertir coma decimal a punto
        $clean = preg_replace('/[^\d,.]/', '', str_replace(',', '.', (string) $val));
        // Si hay varios puntos (ej: "1.300.00"), dejar solo el último
        if (substr_count($clean, '.') > 1) {
            $clean = str_replace('.', '', substr($clean, 0, strrpos($clean, '.'))) . '.' . substr($clean, strrpos($clean, '.') + 1);
        }
        return (float) $clean;
    }

    /** Normaliza: minúsculas, sin acentos, espacios simples */
    private function norm(string $s): string
    {
        $s = mb_strtolower(trim($s));
        $s = strtr($s, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
                         'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u']);
        return preg_replace('/\s+/', ' ', $s);
    }

    /** Parsea fecha en DD/MM/YYYY, YYYY-MM-DD o número serial de Excel */
    private function parseFecha(string $raw): string
    {
        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $raw, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return $raw;
        }
        if (is_numeric($raw) && (float)$raw > 1000) {
            try {
                return ExcelDate::excelToDateTimeObject((float)$raw)->format('Y-m-d');
            } catch (\Throwable) {}
        }
        return date('Y') . '-06-15';
    }

    /** Normaliza el nombre de la hoja al formato de grado esperado */
    private function normalizarGrado(string $nombre): string
    {
        $map = [
            'PRIMERO A'  => '1A',       'PRIMERO B'  => '1B',
            'PRIMERO'    => '1er Grado','SEGUNDO'    => '2do Grado',
            'TERCERO'    => '3er Grado','CUARTO'     => '4to Grado',
            'QUINTO'     => '5to Grado','SEXTO'      => '6to Grado',
            '1A'         => '1A',       '1B'         => '1B',
            '1ER'        => '1er Grado','2DO'        => '2do Grado',
            '3ER'        => '3er Grado','4TO'        => '4to Grado',
            '5TO'        => '5to Grado','6TO'        => '6to Grado',
            '3 AÑOS'     => '3 Años',   '4 AÑOS'     => '4 Años',
            '5 AÑOS'     => '5 Años',
        ];
        $upper = strtoupper($nombre);
        return $map[$upper] ?? $nombre;
    }
}
