<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'nombre',
        'telefono',
        'especialidad',
        'activo',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'empleado_id');
    }
}
