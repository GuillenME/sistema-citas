<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'client_id',
        'service_id',
        'employee_id',
        'staff_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'receipt',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function client()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'service_id');
    }

    public function employee()
    {
        return $this->belongsTo(Empleado::class, 'employee_id');
    }

    // public function staff()
    // {
    //     return $this->belongsTo(Staff::class, 'staff_id');
    // }
}
