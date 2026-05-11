<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\PagoMatricula;
use App\Models\Sede;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class MatriculasImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    /**
     * Expected columns: dni_alumno, periodo, nivel_academico, grado_seccion,
     *                   situacion, modalidad_pago, sede, monto_matricula, pension_mensual
     */
    public function model(array $row)
    {
        $alumno = Alumno::where('dni', trim($row['dni_alumno'] ?? ''))->first();

        if (!$alumno) {
            return null;
        }

        $sede = null;
        if (!empty($row['sede'])) {
            $sede = Sede::where('nombre', 'like', '%' . trim($row['sede']) . '%')->first();
        }

        $periodo = intval($row['periodo'] ?? date('Y'));

        // Check if already exists
        $exists = Matricula::where('alumno_id', $alumno->id)->where('periodo', $periodo)->first();
        if ($exists) {
            return null;
        }

        $codigo = 'MAT-' . $periodo . '-' . str_pad(
            Matricula::whereYear('created_at', $periodo)->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $matricula = Matricula::create([
            'codigo_matricula' => $codigo,
            'alumno_id'        => $alumno->id,
            'periodo'          => $periodo,
            'nivel_academico'  => strtoupper(trim($row['nivel_academico'] ?? 'PRIMARIA')),
            'grado_seccion'    => trim($row['grado_seccion'] ?? ''),
            'situacion'        => strtoupper(trim($row['situacion'] ?? 'ALUMNO NUEVO')),
            'modalidad_pago'   => strtolower(trim($row['modalidad_pago'] ?? 'mensual')),
            'sede_id'          => $sede?->id ?? $alumno->sede_id,
        ]);

        PagoMatricula::create([
            'matricula_id'    => $matricula->id,
            'monto_matricula' => floatval($row['monto_matricula'] ?? 0),
            'pension_mensual' => floatval($row['pension_mensual'] ?? 0),
            'estado_pago'     => 'PENDIENTE',
        ]);

        return $matricula;
    }
}
