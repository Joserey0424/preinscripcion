<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
    protected $table = 'fechas';

    protected $fillable = [

        'descripcion',

        'cupo_maximo',

        'activa'

    ];

    protected $casts = [

        'activa' => 'boolean'

    ];

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    /*
     * Cantidad de personas asignadas
     */
    public function getOcupadosAttribute()
    {
        return $this->asignaciones()

            ->where('activo', true)

            ->count();
    }

    /*
     * Cupos disponibles
     */
    public function getDisponiblesAttribute()
    {
        return $this->cupo_maximo - $this->ocupados;
    }

    /*
     * Porcentaje de ocupación
     */
    public function getPorcentajeAttribute()
    {
        if ($this->cupo_maximo == 0) {

            return 0;

        }

        return round(

            ($this->ocupados / $this->cupo_maximo) * 100

        );

    }
}