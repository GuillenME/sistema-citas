<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promocion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'title',
        'description',
        'discount',
        'image',
        'start_date',
        'end_date',
        'published'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'published' => 'boolean',
        'discount' => 'decimal:2'
    ];

    /**
     * Relación muchos a muchos con servicios
     */
    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'promotion_service', 'promotion_id', 'service_id')
            ->withTimestamps();
    }

    /**
     * Calcular el precio con descuento para un servicio
     */
    public function calcularPrecioConDescuento(float $precioServicio): float
    {
        $descuento = ($precioServicio * $this->discount) / 100;
        return round($precioServicio - $descuento, 2);
    }

    /**
     * Verificar si la promoción está activa
     */
    public function estaActiva(): bool
    {
        $hoy = now()->toDateString();
        return $this->published 
            && $this->start_date <= $hoy 
            && $this->end_date >= $hoy;
    }
}

