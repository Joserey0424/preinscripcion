<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignacion extends Model
{
    protected $fillable = [
        'importacion_id',
        'fecha_id',
        'documento',
        'nombre',
        'fila_excel',
        'estado',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function fecha(): BelongsTo
    {
        return $this->belongsTo(Fecha::class);
    }

    public function importacion(): BelongsTo
    {
        return $this->belongsTo(Importacion::class);
    }

    public function conflictos(): HasMany
    {
        return $this->hasMany(Conflicto::class);
    }
}