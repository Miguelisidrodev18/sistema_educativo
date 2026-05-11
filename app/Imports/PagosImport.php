<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\PagoPension;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class PagosImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    /**
     * Expected columns: dni, mes_pagado, anio, monto, metodo_pago, numero_recibo, fecha_pago
     */
    public function model(array $row)
    {
        $alumno = Alumno::where('dni', trim($row['dni'] ?? ''))->first();

        if (!$alumno) {
            return null;
        }

        return PagoPension::firstOrCreate(
            [
                'alumno_id'  => $alumno->id,
                'mes_pagado' => ucfirst(strtolower(trim($row['mes_pagado'] ?? ''))),
                'anio'       => intval($row['anio'] ?? date('Y')),
            ],
            [
                'monto'         => floatval($row['monto'] ?? 0),
                'metodo_pago'   => strtoupper(trim($row['metodo_pago'] ?? 'EFECTIVO')),
                'numero_recibo' => trim($row['numero_recibo'] ?? ''),
                'fecha_pago'    => \Carbon\Carbon::parse($row['fecha_pago'] ?? now())->format('Y-m-d H:i:s'),
                'sede_id'       => $alumno->sede_id,
            ]
        );
    }
}
