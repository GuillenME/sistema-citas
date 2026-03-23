<?php

return [
    // Porcentaje de anticipo requerido para confirmar la cita (0-100)
    'porcentaje_anticipo' => env('CITAS_PORCENTAJE_ANTICIPO', 50),

    // Datos bancarios para el anticipo
    'banco' => [
        'nombre' => env('BANCO_NOMBRE', 'BBVA'),
        'cuenta' => env('BANCO_CUENTA', '1234567890'),
        'clabe' => env('BANCO_CLABE', '012345678901234567'),
        'beneficiario' => env('BANCO_BENEFICIARIO', 'Barberia & Spa'),
    ],

    // Configuracion de horarios
    'horarios' => [
        'dias_semana' => [
            [540, 900],   // 9:00 - 15:00
            [960, 1080],  // 16:00 - 18:00
        ],
        'sabados' => [
            [540, 960],   // 9:00 - 16:00
        ],
        'intervalo_minutos' => 15,
        'hora_minima_adelantada' => 60,
        'descanso_entre_servicios' => env('CITAS_DESCANSO_ENTRE_SERVICIOS', 10),

        // Bloque de comida global (se excluye de disponibilidad)
        'comida_inicio' => env('CITAS_COMIDA_INICIO', '15:00'),
        'comida_fin' => env('CITAS_COMIDA_FIN', '16:00'),
    ],
];
