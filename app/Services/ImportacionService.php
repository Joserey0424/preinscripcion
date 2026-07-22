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
        // Leer encabezado del archivo
        $spreadsheet = IOFactory::load($archivo->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $lider = [
            'nombre' => trim((string) $sheet->getCell('B3')->getValue()),
            'identificacion' => trim((string) $sheet->getCell('B4')->getValue()),
        ];

        // Leer colaboradores
        $import = new AsignacionesImport();
        Excel::import($import, $archivo);

        $registros = [];
        $documentos = [];

        $erroresArchivo = $this->validarLider($lider);

        $registros = [];

        $documentos = [];

        foreach ($import->datos as $fila) {

            $this->validarFila(

                $fila,

                $documentos

            );

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

            $nombreLider = preg_replace(
                '/\s+/',
                ' ',
                trim($preview['lider']['nombre'])
            );

            $nombreLider = Str::title(
                Str::lower($nombreLider)
            );
            
            $importacion = Importacion::create([

                'archivo_original'      => 'Pendiente',
                'archivo_guardado'      => 'Pendiente',


                'lider_nombre'          => $nombreLider,
                'lider_documento'       => $preview['lider']['identificacion'],

                'cantidad_registros'    => count($preview['registros']),
                'cantidad_importados'   => collect($preview['registros'])->where('importar', true)->count(),
                'cantidad_conflictos'   => collect($preview['registros'])->where('importar', false)->count(),

                'estado'                => 'FINALIZADA',

                'observaciones'         => 'Importación realizada desde vista previa.'

            ]);

            foreach ($preview['registros'] as $fila) {

                if (!$fila['importar']) {
                    continue;
                }

                $fecha = Fecha::where(
                    'descripcion',
                    trim($fila['fecha'])
                )->first();

                if (!$fecha) {
                    continue;
                }

                Asignacion::create([

                    'importacion_id' => $importacion->id,

                    'fecha_id' => $fecha->id,

                    'documento' => $fila['documento'],

                    'nombre' => $fila['nombre'],

                    'fila_excel' => $fila['fila'],

                    'estado' => 'OK',

                    'activo' => true

                ]);
            }
        });

        session()->forget('preview_importacion');
    }
}
