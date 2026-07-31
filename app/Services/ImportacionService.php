<?php

namespace App\Services;

use App\Imports\AsignacionesImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Importacion;
use App\Models\Asignacion;
use App\Models\Fecha;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ImportacionService
{

    private function validarLider(array $lider): array
    {
        $errores = [];

        if (empty($lider['nombre'])) {
            $errores[] = 'El nombre del líder es obligatorio.';
        }

        if (empty($lider['identificacion'])) {
            $errores[] = 'La identificación del líder es obligatoria.';
        }

        if (
            !empty($lider['identificacion']) &&
            !ctype_digit($lider['identificacion'])
        ) {
            $errores[] = 'La identificación del líder debe ser numérica.';
        }

        return $errores;
    }

    private function validarFila(array &$fila, array &$documentos): void
    {
        $errores = [];

        /*
     * Nombre
     */

        if (empty($fila['nombre'])) {

            $errores[] = 'Nombre obligatorio';
        }

        /*
     * Documento
     */

        if (empty($fila['documento'])) {

            $errores[] = 'Documento obligatorio';
        }

        /*
     * Documento numérico
     */

        if (
            !empty($fila['documento']) &&
            !ctype_digit($fila['documento'])
        ) {

            $errores[] = 'Documento inválido';
        }

        /*
     * Fecha
     */

        if (empty($fila['fecha'])) {

            $errores[] = 'Fecha obligatoria';
        }

        /*
     * Duplicado en Excel
     */

        if (!empty($fila['documento'])) {

            if (isset($documentos[$fila['documento']])) {

                $errores[] = 'Documento repetido en el archivo';
            } else {

                $documentos[$fila['documento']] = true;
            }
        }

        /*
     * Existe en BD
     */

        if (
            !empty($fila['documento']) &&
            Asignacion::where('documento', $fila['documento'])
            ->where('activo', true)
            ->exists()
        ) {

            $errores[] = 'El colaborador ya fue cargado anteriormente';
        }

        /*
     * Fecha válida
     */

        $fecha = Fecha::where(

            'descripcion',

            trim($fila['fecha'])

        )

            ->where('activa', true)

            ->first();

        if (!$fecha) {

            $errores[] = 'La fecha no existe';
        } else {

            $fila['fecha_id'] = $fecha->id;
        }

        $fila['errores'] = $errores;

        $fila['estado'] = empty($errores)

            ? 'OK'

            : 'ERROR';

        $fila['importar'] = empty($errores);
    }


    public function analizar($archivo)
    {
        // Leer encabezado
        $spreadsheet = IOFactory::load($archivo->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $lider = [
            'nombre' => trim((string) $sheet->getCell('B3')->getValue()),
            'identificacion' => trim((string) $sheet->getCell('B4')->getValue()),
        ];

        // Leer Excel
        $import = new AsignacionesImport();
        Excel::import($import, $archivo);

        $erroresArchivo = $this->validarLider($lider);

        $documentos = [];
        $registros = [];

        /*
    |--------------------------------------------------------------------------
    | Fechas disponibles
    |--------------------------------------------------------------------------
    */

        $fechas = Fecha::where('activa', true)
            ->get()
            ->keyBy('id');

        /*
    |--------------------------------------------------------------------------
    | Ocupación actual
    |--------------------------------------------------------------------------
    */

        $ocupados = Fecha::withCount([
            'asignaciones as ocupados' => function ($q) {
                $q->where('activo', true);
            }
        ])
            ->get()
            ->pluck('ocupados', 'id')
            ->toArray();

        /*
    |--------------------------------------------------------------------------
    | Validar filas
    |--------------------------------------------------------------------------
    */

        foreach ($import->datos as $fila) {

            $this->validarFila($fila, $documentos);

            /*
        |--------------------------------------------------------------------------
        | Si ya tiene errores no seguimos
        |--------------------------------------------------------------------------
        */

            if (!$fila['importar']) {

                $registros[] = $fila;
                continue;
            }

            $idFecha = $fila['fecha_id'];

            $fecha = $fechas->get($idFecha);

            if (!$fecha) {

                $fila['errores'][] = 'La fecha no existe.';
                $fila['estado'] = 'ERROR';
                $fila['importar'] = false;

                $registros[] = $fila;
                continue;
            }

            /*
        |--------------------------------------------------------------------------
        | Simular cupos
        |--------------------------------------------------------------------------
        */

            if ($ocupados[$idFecha] >= $fecha->cupo_maximo) {

                $fila['errores'][] = 'La sesión ya no tiene cupos disponibles.';
                $fila['estado'] = 'ERROR';
                $fila['importar'] = false;
            } else {

                $ocupados[$idFecha]++;
            }

            $registros[] = $fila;
        }

        return [

            'lider' => $lider,

            'erroresArchivo' => $erroresArchivo,

            'registros' => $registros

        ];
    }

    
    public function importar()
    {
        $preview = session('preview_importacion');

        if (!$preview) {
            throw new \Exception('No existe una importación pendiente.');
        }

        DB::transaction(function () use ($preview) {

            $nombreLider = Str::title(
                Str::lower(
                    preg_replace('/\s+/', ' ', trim($preview['lider']['nombre']))
                )
            );

            $fechas = Fecha::where('activa', true)
                ->get()
                ->keyBy(fn($f) => trim($f->descripcion));

            $ocupacion = Fecha::withCount([
                'asignaciones as ocupados' => function ($q) {
                    $q->where('activo', true);
                }
            ])->get()->keyBy('id');

            $resultados = [];

            $importacion = Importacion::create([
                'archivo_original'    => 'Pendiente',
                'archivo_guardado'    => 'Pendiente',
                'lider_nombre'        => $nombreLider,
                'lider_documento'     => $preview['lider']['identificacion'],
                'cantidad_registros'  => count($preview['registros']),
                'cantidad_importados' => 0,
                'cantidad_conflictos' => 0,
                'estado'              => 'FINALIZADA',
                'observaciones'       => 'Importación realizada desde vista previa.'
            ]);

            foreach ($preview['registros'] as $fila) {

                if (!$fila['importar']) {
                    $resultados[] = [
                        'ok' => false,
                        'documento' => $fila['documento'],
                        'nombre' => $fila['nombre'],
                        'motivo' => implode(', ', $fila['errores'] ?? [])
                    ];
                    continue;
                }

                $fecha = $fechas[trim($fila['fecha'])] ?? null;

                if (!$fecha) {
                    $resultados[] = [
                        'ok' => false,
                        'documento' => $fila['documento'],
                        'nombre' => $fila['nombre'],
                        'motivo' => 'La fecha no existe.'
                    ];
                    continue;
                }


                if ($ocupacion[$fecha->id]->ocupados >= $fecha->cupo_maximo) {

                    $resultados[] = [
                        'ok' => false,
                        'documento' => $fila['documento'],
                        'nombre' => $fila['nombre'],
                        'motivo' => 'No hay cupos disponibles para la sesión.'
                    ];

                    continue;
                }

                Asignacion::create([
                    'importacion_id' => $importacion->id,
                    'fecha_id'       => $fecha->id,
                    'documento'      => $fila['documento'],
                    'nombre'         => $fila['nombre'],
                    'fila_excel'     => $fila['fila'],
                    'estado'         => 'OK',
                    'activo'         => true
                ]);

                $ocupacion[$fecha->id]->ocupados++;

                $resultados[] = [
                    'ok' => true,
                    'documento' => $fila['documento'],
                    'nombre' => $fila['nombre'],
                    'motivo' => null
                ];
            }

            $importacion->update([
                'cantidad_importados' => collect($resultados)->where('ok', true)->count(),
                'cantidad_conflictos' => collect($resultados)->where('ok', false)->count(),
            ]);

            session([
                'resultado_importacion' => $resultados
            ]);
        });

        session()->forget('preview_importacion');
    }
}
