<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fecha;

class FechaSeeder extends Seeder
{
    public function run(): void
    {
        $fechas = [

            [
                'descripcion' => 'Lunes 10 de agosto - 7:15 a.m. a 11:00 a.m.',
                'cupo_maximo' => 1000,
                'activa' => true,
            ],

            [
                'descripcion' => 'Martes 11 de agosto 7:15 a.m. a 11:00 a.m.',
                'cupo_maximo' => 1000,
                'activa' => true,
            ],

            [
                'descripcion' => 'Miércoles 12 de agosto - mañana 7:15 a.m. a 11:00 a.m.',
                'cupo_maximo' => 1000,
                'activa' => true,
            ],

            [
                'descripcion' => 'Miércoles 12 de agosto - tarde 1:00 p.m. a 4:30 p.m.',
                'cupo_maximo' => 1000,
                'activa' => true,
            ],

        ];

        foreach ($fechas as $fecha) {

            Fecha::updateOrCreate(

                [
                    'descripcion' => $fecha['descripcion']
                ],

                $fecha

            );

        }
    }
}