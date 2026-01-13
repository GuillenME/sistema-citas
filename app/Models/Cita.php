<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'personal_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'observaciones'
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function estados()
    {
        return $this->hasMany(CitaEstado::class, 'cita_id');
    }
}

