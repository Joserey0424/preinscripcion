<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Fecha;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AsignacionController extends Controller
{
    /**
     * Listado principal
     */
    public function index(Request $request)
    {
        $query = Asignacion::with([
            'fecha',
            'importacion'
        ])
            ->where('activo', true);

        // Filtro inicial por fecha (cuando carga la página)
        if ($request->filled('fecha')) {

            $query->where('fecha_id', $request->fecha);
        }

        $asignaciones = $query
            ->latest()
            ->paginate(20);

        $fechas = Fecha::where('activa', true)
            ->orderBy('descripcion')
            ->get();

        return view('asignaciones.index', compact(
            'asignaciones',
            'fechas'
        ));
    }

    /**
     * Buscador AJAX
     */
    public function buscar(Request $request)
    {
        $texto = trim($request->buscar);
        $fecha = $request->fecha;

        $query = Asignacion::with([
            'fecha',
            'importacion'
        ])
            ->where('activo', true);

        if (!empty($texto)) {

            $query->where(function ($q) use ($texto) {

                $q->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('documento', 'like', "%{$texto}%");
            });
        }

        if (!empty($fecha)) {

            $query->where('fecha_id', $fecha);
        }

        return response()->json(

            $query
                ->orderBy('nombre')
                ->limit(50)
                ->get()

        );
    }

    /**
     * Editar asignación
     */
    public function update(Request $request, Asignacion $asignacion)
    {
        $request->validate([

            'nombre' => 'required|max:255',

            'documento' => 'required|max:30',

            'fecha_id' => 'required|exists:fechas,id'

        ]);

        /*
         * Validar documento duplicado
         */

        $duplicado = Asignacion::where('documento', $request->documento)
            ->where('activo', true)
            ->where('id', '!=', $asignacion->id)
            ->exists();

        if ($duplicado) {

            return response()->json([

                'success' => false,

                'message' => 'Ya existe una asignación activa para este documento.'

            ], 422);
        }

    
        $nombre = preg_replace('/\s+/', ' ', trim($request->nombre));
        $nombre = Str::title(Str::lower($nombre));

        $asignacion->update([

            'nombre' => $nombre,

            'documento' => trim($request->documento),

            'fecha_id' => $request->fecha_id

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Asignación actualizada correctamente.'

        ]);
    }

    /**
     * Desactivar asignación
     */
    public function destroy(Asignacion $asignacion)
    {
        $asignacion->update([

            'activo' => false

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Asignación eliminada correctamente.'

        ]);
    }
}
