<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Importacion extends Model
{
    protected $fillable = [
    'archivo_original',
    'archivo_guardado',

    'lider_nombre',
    'lider_documento',

    'cantidad_registros',
    'cantidad_importados',
    'cantidad_conflictos',

    'estado',

    'observaciones'
];

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class);
    }
}