<?php

namespace App\Exports;

use App\Models\Asignacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class GeneralSheetExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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

    public function title(): string
    {
        return 'General';
    }

    public function collection()
    {
        return Asignacion::query()

            ->where('activo', true)

            ->with([
                'fecha',
                'importacion'
            ])

            ->get()

            ->map(function ($a) {

                return [

                    'Documento' => $a->documento,

                    'Nombre' => $a->nombre,

                    'Fecha' => $a->fecha->descripcion,

                    'Documento líder' => $a->importacion->lider_documento,

                    'Nombre Líder' => $a->importacion->lider_nombre,

                    'Fecha importación' =>
                    $a->importacion->created_at
                        ->format('d/m/Y H:i'),

                ];
            });
    }

    public function headings(): array
    {
        return [

            'Documento',

            'Nombre',

            'Fecha',

            'Documento lider',

            'Nombre Líder',

            'Fecha importación'

        ];
    }
}
