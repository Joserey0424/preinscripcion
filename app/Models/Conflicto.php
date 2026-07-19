<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conflicto extends Model
{
    protected $fillable = [
        'asignacion_id',
        'tipo',
        'descripcion',
        'resuelto'
    ];

    protected $casts = [
        'resuelto' => 'boolean',
    ];

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }
}