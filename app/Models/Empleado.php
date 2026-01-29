<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'name',
        'phone',
        'specialty',
        'active',
    ];

    public function appointments()
    {
        return $this->hasMany(Cita::class, 'employee_id');
    }
}
