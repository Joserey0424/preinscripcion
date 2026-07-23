<?php

namespace App\Exports;

use App\Models\Fecha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FechaSheetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{

    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter(
            $sheet->calculateWorksheetDimension()
        );

        return [

            1 => [

                'font' => [

                    'bold' => true

                ]

            ]

        ];
    }

    protected Fecha $fecha;

    public function title(): string
    {
        return mb_substr($this->fecha->descripcion, 0, 31);
    }

    public function __construct(Fecha $fecha)
    {
        $this->fecha = $fecha;
    }

    public function collection()
    {
        return $this->fecha

            ->asignaciones()

            ->where('activo', true)

            ->with('importacion')

            ->get()

            ->map(function ($a) {

                return [

                    'Documento' => $a->documento,

                    'Nombre' => $a->nombre,

                    'Fecha' => $this->fecha->descripcion,

                    'Líder' => $a->importacion->lider_nombre,

                    'Fecha importación' =>
                    $a->importacion->created_at
                        ->format('d/m/Y H:i')

                ];
            });
    }

    public function headings(): array
    {
        return [

            'Documento',

            'Nombre',

            'Fecha',

            'Líder',

            'Fecha importación'

        ];
    }
}
