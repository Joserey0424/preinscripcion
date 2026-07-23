<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Fecha;
use App\Models\Importacion;
use App\Exports\ConsolidadoExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{

    public function exportarConsolidado()
    {
        return Excel::download(
            new ConsolidadoExport(),
            'Consolidado_Reinduccion.xlsx'
        );
    }

    public function index()
    {
        $fechas = Fecha::withCount([
            'asignaciones as ocupados' => function ($query) {

                $query->where('activo', true);
            }
        ])->get();

        foreach ($fechas as $fecha) {

            $fecha->disponibles = max(
                0,
                $fecha->cupo_maximo - $fecha->ocupados
            );

            $fecha->porcentaje = $fecha->cupo_maximo > 0
                ? round(($fecha->ocupados / $fecha->cupo_maximo) * 100)
                : 0;
        }

        return view('dashboard.index', [

            'fechas' => $fechas,

            'totalImportaciones' => Importacion::count(),

            'totalAsignaciones' => Asignacion::where('activo', true)->count(),

            'totalFechas' => Fecha::count()

        ]);
    }
}
