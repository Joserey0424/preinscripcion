<?php

namespace App\Console\Commands;

use App\Models\Asignacion;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class NormalizarNombres extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nombres:normalizar {--dry-run : Solo mostrar los cambios sin guardar}';

    /**
     * The console command description.
     */
    protected $description = 'Normaliza los nombres de las asignaciones existentes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $actualizados = 0;

        Asignacion::orderBy('id')
            ->chunkById(500, function ($asignaciones) use (&$actualizados, $dryRun) {

                foreach ($asignaciones as $asignacion) {

                    $original = $asignacion->nombre;

                    $nuevo = preg_replace('/\s+/', ' ', trim($original));
                    $nuevo = Str::title(Str::lower($nuevo));

                    if ($original === $nuevo) {
                        continue;
                    }

                    $this->line("{$asignacion->id}: '{$original}' → '{$nuevo}'");

                    if (! $dryRun) {
                        $asignacion->update([
                            'nombre' => $nuevo
                        ]);
                    }

                    $actualizados++;
                }
            });

        if ($dryRun) {
            $this->newLine();
            $this->info("Modo simulación. Se actualizarían {$actualizados} registros.");
        } else {
            $this->newLine();
            $this->info("Proceso finalizado. Se actualizaron {$actualizados} registros.");
        }

        return self::SUCCESS;
    }
}