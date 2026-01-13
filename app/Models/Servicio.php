<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_minutos',
        'precio',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];
}
