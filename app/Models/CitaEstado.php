<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaEstado extends Model
{
    use HasFactory;
    protected $table = 'cita_estados';

    protected $fillable = [
        'cita_id',
        'estado',
        'usuairio_id',
        'fecha_cambio'
    ];

    public $timestamps = false;

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }   

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuairio_id');
    }
}
