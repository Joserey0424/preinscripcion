<?php

namespace App\Http\Controllers;

use App\Models\Fecha;
use Illuminate\Http\Request;


class FechaController extends Controller
{
    public function index()
    {
        $fechas = Fecha::orderBy('descripcion')->get();

        return view(

            'fechas.index',

            compact('fechas')

        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|max:255',
            'cupo_maximo' => 'required|integer|min:1'
        ]);

        Fecha::create([
            'descripcion' => $request->descripcion,
            'cupo_maximo' => $request->cupo_maximo,
            'activa' => true
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function cambiarEstado(Fecha $fecha)
    {
        $fecha->update([
            'activa' => !$fecha->activa
        ]);

        return response()->json([
            'success' => true,
            'estado' => $fecha->activa
        ]);
    }

    public function actualizarCupo(Request $request, Fecha $fecha)
    {
        $request->validate([
            'cupo_maximo' => 'required|integer|min:1'
        ]);

        $fecha->update([
            'cupo_maximo' => $request->cupo_maximo
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy(Fecha $fecha)
    {
        if ($fecha->asignaciones()->exists()) {

            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar una fecha que tiene asignaciones.'
            ], 422);
        }

        $fecha->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
