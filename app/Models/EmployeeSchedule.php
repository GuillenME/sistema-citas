<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    protected $fillable = [
        'employee_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];


    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'employee_id');
    }
}
