<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaEstado extends Model
{
    use HasFactory;
    protected $table = 'appointment_states';

    protected $fillable = [
        'appointment_id',
        'status',
        'user_id',
        'change_date'
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Cita::class, 'appointment_id');
    }   

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
