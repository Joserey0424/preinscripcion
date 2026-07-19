<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $fillable = [
        'documento',
        'nombre',
        'cargo',
        'area',
        'jefe',
        'estado'
    ];
}