<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Servicio;

class Empleado extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'name',
        'phone',
        'specialty',
        'active',
    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'employee_service', 'employee_id', 'service_id')
            ->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Cita::class, 'employee_id');
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'employee_id');
    }

    public function breaks()
    {
        return $this->hasMany(EmployeeBreak::class, 'employee_id');
    }
}
