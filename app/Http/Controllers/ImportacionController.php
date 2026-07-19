<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarExcelRequest;
use App\Services\ImportacionService;
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
}
