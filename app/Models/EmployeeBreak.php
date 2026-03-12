<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBreak extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'is_all_day',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
        'is_all_day' => 'boolean',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'employee_id');
    }
}
