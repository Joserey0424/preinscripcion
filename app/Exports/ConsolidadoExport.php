<?php

namespace App\Exports;

use App\Models\Fecha;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ConsolidadoExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new GeneralSheetExport();

        foreach (Fecha::where('activa', true)->get() as $fecha) {

            $sheets[] = new FechaSheetExport($fecha);

        }

        return $sheets;
    }
}