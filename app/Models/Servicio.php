<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servicio extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'price',
        'image',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * Relación muchos a muchos con promociones
     */
    public function promociones(): BelongsToMany
    {
        return $this->belongsToMany(Promocion::class, 'promotion_service', 'service_id', 'promotion_id')
            ->withTimestamps();
    }

    /**
     * Obtener la promoción activa para este servicio
     */
    public function promocionActiva(): ?Promocion
    {
        return $this->promociones()
            ->where('published', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->first();
    }

    /**
     * Calcular el precio con descuento si hay promoción activa
     */
    public function precioConDescuento(): float
    {
        $promocion = $this->promocionActiva();
        if ($promocion) {
            return $promocion->calcularPrecioConDescuento($this->price);
        }
        return $this->price;
    }

    /**
     * Calcular el monto de anticipo requerido
     * @return float
     */
    public function calcularAnticipo(): float
    {
        $precioFinal = $this->precioConDescuento();
        $porcentajeAnticipo = config('citas.porcentaje_anticipo', 50);
        return $precioFinal * ($porcentajeAnticipo / 100);
    }

    /**
     * Calcular el monto restante a pagar después de la cita
     * @return float
     */
    public function calcularRestante(): float
    {
        $precioFinal = $this->precioConDescuento();
        return $precioFinal - $this->calcularAnticipo();
    }
}
