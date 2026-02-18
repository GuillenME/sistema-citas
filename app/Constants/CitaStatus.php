<?php

namespace App\Constants;

/**
 * Constantes para los estados de las citas
 * 
 * Evita el uso de strings mágicos en el código
 */
class CitaStatus
{
    public const PENDIENTE_ANTICIPO = 'pendiente_anticipo';
    public const CONFIRMADA = 'confirmada';
    public const CANCELADA = 'cancelada';
    public const COMPLETADA = 'completada';
    public const NO_ASISTIO = 'no_asistio';

    /**
     * Obtener todos los estados disponibles
     */
    public static function all(): array
    {
        return [
            self::PENDIENTE_ANTICIPO,
            self::CONFIRMADA,
            self::CANCELADA,
            self::COMPLETADA,
            self::NO_ASISTIO,
        ];
    }

    /**
     * Verificar si un estado es válido
     */
    public static function isValid(string $status): bool
    {
        return in_array($status, self::all());
    }
}
