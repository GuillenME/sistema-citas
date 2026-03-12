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
        'payment_deadline',
        'payment_attempts',
        'notes',
        'service_price',
        'deposit_amount',
        'final_payment',
        'total_paid',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'payment_deadline' => 'datetime',
        'payment_attempts' => 'integer',
        'service_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'final_payment' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function service()
    {
        return $this->belongsTo(Servicio::class, 'service_id');
    }

    public function employee()
    {
        return $this->belongsTo(Empleado::class, 'employee_id');
    }

    public function estados()
    {
        return $this->hasMany(CitaEstado::class, 'appointment_id');
    }

    public function anticipoRegistrado(): float
    {
        $deposito = $this->deposit_amount;

        if ($deposito !== null && (float) $deposito > 0) {
            return (float) $deposito;
        }

        $notas = (string) ($this->notes ?? '');
        if (preg_match('/Anticipo recibido en (?:recepcion|recepción):\s*\$?([\d,]+(?:\.\d{1,2})?)/iu', $notas, $matches) === 1) {
            return (float) str_replace(',', '', $matches[1]);
        }

        if (preg_match('/Anticipo recibido por administrador:\s*\$?([\d,]+(?:\.\d{1,2})?)/iu', $notas, $matches) === 1) {
            return (float) str_replace(',', '', $matches[1]);
        }

        return 0.0;
    }

    public function precioRegistrado(): float
    {
        if ($this->service_price !== null) {
            return (float) $this->service_price;
        }

        return (float) ($this->service?->price ?? 0);
    }

    // public function staff()
    // {
    //     return $this->belongsTo(Staff::class, 'staff_id');
    // }
}
