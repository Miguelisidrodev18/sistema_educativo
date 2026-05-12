<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MatriculaGradoSheetExport implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private string $grado,
        private string $nivel,
    ) {}

    public function title(): string
    {
        return $this->grado;
    }

    /** Cabeceras exactamente como las usa el colegio */
    private function headers(): array
    {
        return [
            'N°',
            'DNI ALUMNO',
            'APELLIDOS',
            'NOMBRES',
            'FECHA NAC. (DD/MM/YYYY)',
            'SEXO (M/F)',
            'CIUDAD',
            'DIRECCIÓN',
            'INST. PROCEDENCIA',
            'REPITE (SI/NO)',
            'SITUACIÓN',
            'DNI APODERADO',
            'APELLIDOS APODERADO',
            'NOMBRES APODERADO',
            'PARENTESCO',
            'TELÉFONO',
            'EMAIL',
            'OCUPACIÓN',
            'ESTADO CIVIL',
            'MONTO MATRÍCULA',
            'PENSIÓN MENSUAL',
        ];
    }

    public function array(): array
    {
        $rows   = [$this->headers()];
        // 30 filas vacías listas para llenar
        for ($i = 1; $i <= 30; $i++) {
            $rows[] = array_merge([$i], array_fill(0, 20, ''));
        }
        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A'=>5,  'B'=>13, 'C'=>22, 'D'=>20,
            'E'=>20, 'F'=>8,  'G'=>13, 'H'=>24,
            'I'=>22, 'J'=>10, 'K'=>18,
            'L'=>13, 'M'=>22, 'N'=>20,
            'O'=>12, 'P'=>13, 'Q'=>24,
            'R'=>18, 'S'=>13, 'T'=>14, 'U'=>14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = 'U';

        // ── Fila 1: cabeceras ──
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold'=>true,'color'=>['rgb'=>'FFFFFF'],'size'=>9],
            'fill'      => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'1e3a5f']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => ['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'FFFFFF']],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Color por sección
        // Alumno A-K → azul oscuro
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1e3a5f');
        // Apoderado L-S → teal
        $sheet->getStyle('L1:S1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0d9488');
        // Pagos T-U → morado
        $sheet->getStyle('T1:U1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('7c3aed');

        // ── Filas de datos 2-31 ──
        $sheet->getStyle("A2:{$lastCol}31")->applyFromArray([
            'font'      => ['size'=>9],
            'alignment' => ['vertical'=>Alignment::VERTICAL_CENTER],
            'borders'   => [
                'allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'e2e8f0']],
            ],
        ]);

        // Filas alternas: blanco y gris muy claro
        for ($i = 2; $i <= 31; $i++) {
            $color = ($i % 2 === 0) ? 'f8fafc' : 'FFFFFF';
            $sheet->getStyle("A{$i}:{$lastCol}{$i}")
                  ->getFill()->setFillType(Fill::FILL_SOLID)
                  ->getStartColor()->setRGB($color);
        }

        // Columna N° centrada
        $sheet->getStyle("A1:A31")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Columna SEXO centrada
        $sheet->getStyle("F1:F31")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Columna REPITE centrada
        $sheet->getStyle("J1:J31")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Congelar fila 1
        $sheet->freezePane('B2');

        // Título de la hoja arriba del grado
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold'=>true,'size'=>9,'color'=>['rgb'=>'93c5fd']],
        ]);

        return [];
    }
}
