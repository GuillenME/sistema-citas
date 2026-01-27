<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Anticipos para Citas
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar el porcentaje de anticipo requerido para
    | confirmar una cita y el porcentaje restante que se paga después.
    |
    */

    // Porcentaje de anticipo requerido para confirmar la cita (0-100)
    'porcentaje_anticipo' => env('CITAS_PORCENTAJE_ANTICIPO', 50),

    // Porcentaje restante que se paga después de la cita (se calcula automáticamente)
    // El porcentaje restante es: 100 - porcentaje_anticipo

    /*
    |--------------------------------------------------------------------------
    | Configuración de Datos Bancarios
    |--------------------------------------------------------------------------
    |
    | Información bancaria para realizar los anticipos de las citas.
    |
    */
    'banco' => [
        'nombre' => env('BANCO_NOMBRE', 'BBVA'),
        'cuenta' => env('BANCO_CUENTA', '1234567890'),
        'clabe' => env('BANCO_CLABE', '012345678901234567'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Horarios
    |--------------------------------------------------------------------------
    |
    | Configuración de horarios disponibles para las citas.
    |
    */
    'horarios' => [
        // Horarios en minutos desde medianoche (0 = 00:00)
        'dias_semana' => [
            [480, 900],   // 8:00 - 15:00
            [960, 1200],  // 16:00 - 20:00
        ],
        'sabados' => [
            [480, 900],   // 8:00 - 15:00
        ],
        'intervalo_minutos' => 15, // Intervalo entre citas
        'hora_minima_adelantada' => 60, // Minutos de anticipación mínima para agendar
    ],
];
