<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarExcelRequest;
use App\Services\ImportacionService;
use App\Models\Importacion;

use Illuminate\Http\Request;

class ImportacionController extends Controller
{
    protected ImportacionService $service;

    public function __construct(ImportacionService $service)
    {
        $this->service = $service;
    }

    /**
     * Mostrar formulario
     */

    public function index()
    {
        return view('importaciones.index');
    }

    public function historial(Request $request)
    {
        $buscar = $request->buscar;

        $importaciones = Importacion::query()

            ->when($buscar, function ($q) use ($buscar) {

                $q->where('lider_nombre', 'like', "%{$buscar}%")
                    ->orWhere('lider_documento', 'like', "%{$buscar}%")
                    ->orWhere('archivo_original', 'like', "%{$buscar}%");
            })

            ->latest()

            ->paginate(9)

            ->withQueryString();

        return view(
            'importaciones.historial',
            compact('importaciones', 'buscar')
        );
    }

    /**
     * Analizar Excel
     */
    public function preview(ImportarExcelRequest $request)
    {
        $resultado = $this->service->analizar(
            $request->file('archivo')
        );

        session([
            'preview_importacion' => $resultado
        ]);

        return view(
            'importaciones.preview',
            compact('resultado')
        );
    }

    /**
     * Confirmar importación
     */
    public function importar(Request $request)
    {
        $this->service->importar($request);

        return redirect()
            ->route('importaciones.index')
            ->with('success', 'Archivo importado correctamente.');
    }

    public function destroy(Importacion $importacion)
    {
        if ($importacion->estado === 'ELIMINADA') {

            return response()->json([
                'success' => false
            ]);
        }

        $importacion->update([
            'estado' => 'REVERTIDA',
            'cantidad_importados' => 0
        ]);

        $importacion
            ->asignaciones()
            ->update([
                'activo' => false
            ]);

        return response()->json([
            'success' => true
        ]);
    }
}
