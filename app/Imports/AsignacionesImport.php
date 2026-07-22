<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class AsignacionesImport implements ToCollection
{
    public array $datos = [];

    public function collection(Collection $rows)
    {

        //  * Leer colaboradores desde la fila 7 del Excel */
        foreach ($rows->slice(6) as $index => $row) {

            // Solo columnas A, B y C
            $nombre = preg_replace('/\s+/', ' ', trim((string) ($row[0] ?? '')));
            $nombre = Str::title(Str::lower($nombre));
            $documento = trim((string) ($row[1] ?? ''));
            $fecha = trim((string) ($row[2] ?? ''));

            /*
         * Ignorar filas vacías
         */
            if (
                empty($nombre) &&
                empty($documento) &&
                empty($fecha)
            ) {
                continue;
            }

            /*
         * Ignorar filas con puntos
         */
            if (
                $nombre === '.' ||
                $documento === '.' ||
                $fecha === '.'
            ) {
                continue;
            }

            $this->datos[] = [
                'fila'       => $index + 7,
                'nombre'     => $nombre,
                'documento'  => $documento,
                'fecha'      => $fecha,
            ];
        }
    }
}
