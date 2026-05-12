<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MatriculaPlantillaExport implements WithMultipleSheets
{
    public function __construct(private string $nivel = 'primaria') {}

    public function sheets(): array
    {
        $gradosPrimaria   = ['PRIMERO','SEGUNDO','TERCERO','CUARTO','QUINTO','SEXTO'];
        $gradosSecundaria = ['PRIMERO A','PRIMERO B','SEGUNDO','TERCERO','CUARTO','QUINTO'];
        $gradosInicial    = ['3 AÑOS','4 AÑOS','5 AÑOS'];

        $grados = match($this->nivel) {
            'secundaria' => $gradosSecundaria,
            'inicial'    => $gradosInicial,
            default      => $gradosPrimaria,
        };

        return array_map(
            fn($g) => new MatriculaGradoSheetExport($g, strtoupper($this->nivel)),
            $grados
        );
    }
}
