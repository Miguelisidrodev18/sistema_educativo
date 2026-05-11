<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\PagoMatricula;
use App\Models\PagoPension;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PagosBancarioImport implements ToCollection, WithHeadingRow
{
    public int $registrados   = 0;
    public int $actualizados  = 0;
    public int $duplicados    = 0;
    public int $noEncontrados = 0;
    public array $detalle     = [];

    private array $meses = [
        '01'=>'Enero',  '02'=>'Febrero', '03'=>'Marzo',
        '04'=>'Abril',  '05'=>'Mayo',    '06'=>'Junio',
        '07'=>'Julio',  '08'=>'Agosto',  '09'=>'Septiembre',
        '10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre',
    ];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $i => $row) {
            $row = $row->toArray();

            // Detectar DNI en múltiples variantes de columna
            $dni = $this->getCol($row, ['codigo','dni_alumno','dni','nro_dni']);
            $dni = trim(str_replace(' ', '', (string)$dni));
            if (!$dni || !is_numeric($dni)) continue;

            $alumno = Alumno::where('dni', $dni)->first();
            if (!$alumno) {
                $this->detalle[] = "❌ DNI {$dni} no encontrado";
                $this->noEncontrados++;
                continue;
            }

            $nombre = "{$alumno->apellidos}, {$alumno->nombres}";

            // ── Actualizar monto matrícula y/o pensión si vienen en el Excel ──
            $montoMat = floatval($this->getCol($row, ['monto_matricula','monto_mat','matricula']));
            $pension  = floatval($this->getCol($row, ['pension_mensual','pension','pensión_mensual','pension_men']));

            if ($montoMat > 0 || $pension > 0) {
                $matricula = Matricula::where('alumno_id', $alumno->id)->latest('id')->first();
                if ($matricula && $matricula->pagoMatricula) {
                    $upd = [];
                    if ($montoMat > 0) $upd['monto_matricula'] = $montoMat;
                    if ($pension  > 0) $upd['pension_mensual']  = $pension;
                    $matricula->pagoMatricula->update($upd);
                    $this->actualizados++;
                    $msg = "💰 {$dni} ({$nombre}):";
                    if ($montoMat > 0) $msg .= " mat=S/".number_format($montoMat, 2);
                    if ($pension  > 0) $msg .= " pension=S/".number_format($pension, 2);
                    $this->detalle[] = $msg;
                }
            }

            // ── Registrar pago mensual si viene periodo YYYYMM ──
            $periodo = trim((string)$this->getCol($row, ['periodo']));
            $total   = floatval($this->getCol($row, ['total']));

            if (strlen($periodo) === 6 && is_numeric($periodo) && $total > 0) {
                $anio   = (int)substr($periodo, 0, 4);
                $mesNum = substr($periodo, 4, 2);
                $mesNom = $this->meses[$mesNum] ?? null;
                if (!$mesNom) continue;

                $mora  = floatval($this->getCol($row, ['mora']));
                $monto = round($total + $mora, 2);

                $existe = PagoPension::where('alumno_id', $alumno->id)
                    ->where('mes_pagado', $mesNom)->where('anio', $anio)->first();

                if ($existe) {
                    $this->detalle[]  = "⚠️ {$dni}: {$mesNom} {$anio} ya registrado — omitido";
                    $this->duplicados++;
                    continue;
                }

                $recibo = trim((string)$this->getCol($row, ['cod_operac','cod_operación','num_operacion']));
                if (!$recibo) $recibo = 'EXCEL-' . strtoupper($mesNom) . $anio;

                $fechaStr = trim((string)$this->getCol($row, ['fecha_trx','fecha']));
                try {
                    $fechaPago = $fechaStr ? \Carbon\Carbon::parse($fechaStr) : now();
                } catch (\Exception $e) {
                    $fechaPago = now();
                }

                PagoPension::create([
                    'alumno_id'     => $alumno->id,
                    'mes_pagado'    => $mesNom,
                    'anio'          => $anio,
                    'monto'         => $monto,
                    'metodo_pago'   => 'BANCO',
                    'numero_recibo' => $recibo,
                    'fecha_pago'    => $fechaPago,
                    'sede_id'       => $alumno->sede_id,
                ]);

                $this->registrados++;
                $this->detalle[] = "✅ {$dni} ({$nombre}): {$mesNom} {$anio} → S/".number_format($monto, 2)
                    . ($mora > 0 ? " (mora S/".number_format($mora, 2).")" : "");
            }
        }
    }

    private function getCol(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            // Normalizar key del Excel: minúsculas, tildes removidas, espacios→guion
            $normalized = $this->normalizar($key);
            foreach ($row as $col => $val) {
                if ($this->normalizar((string)$col) === $normalized) return $val;
                // partial match
                if (str_contains($this->normalizar((string)$col), $normalized)) return $val;
            }
        }
        return null;
    }

    private function normalizar(string $s): string
    {
        $s = mb_strtolower(trim($s), 'UTF-8');
        return strtr($s, [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
            ' '=>'_','.'=> '','/'=> '',
        ]);
    }
}
