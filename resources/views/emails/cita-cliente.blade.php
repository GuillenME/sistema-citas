<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificacion de cita</title>
</head>
<body style="margin:0;padding:0;background:#f3eee8;font-family:Arial,Helvetica,sans-serif;color:#1f1f1f;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;background:#f3eee8;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#1a2238;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="padding:20px 24px;background:linear-gradient(90deg,#2b1a10 0%,#5f3a2b 100%);color:#f5e9d6;">
                            <h1 style="margin:0;font-size:22px;line-height:1.2;">Barberia &amp; Spa</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:.9;">Notificacion de cita</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;color:#f5efe6;">
                            <p style="margin:0 0 16px;font-size:20px;font-weight:700;">Hola, {{ $nombre }}.</p>

                            @if($tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
                                <p style="margin:0 0 14px;font-size:16px;">Tu cita fue confirmada y ya tiene empleado asignado.</p>
                            @elseif($tipo === \App\Notifications\CitaClienteNotification::REASIGNADA_DE_EMPLEADO)
                                <p style="margin:0 0 14px;font-size:16px;">Tu cita fue reasignada a otro empleado por disponibilidad operativa.</p>
                            @else
                                <p style="margin:0 0 14px;font-size:16px;">Tu cita fue cancelada por administracion.</p>
                            @endif

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 20px;background:#11192b;border:1px solid #3a2a1d;border-radius:12px;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <p style="margin:0 0 8px;color:#f2c464;"><strong>Servicio:</strong> <span style="color:#f5efe6;font-weight:400;">{{ $servicio }}</span></p>
                                        <p style="margin:0 0 8px;color:#f2c464;"><strong>Fecha:</strong> <span style="color:#f5efe6;font-weight:400;">{{ $fecha }}</span></p>
                                        <p style="margin:0 0 8px;color:#f2c464;"><strong>Hora:</strong> <span style="color:#f5efe6;font-weight:400;">{{ $hora }}</span></p>
                                        @if(
                                            $tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO ||
                                            $tipo === \App\Notifications\CitaClienteNotification::REASIGNADA_DE_EMPLEADO
                                        )
                                            <p style="margin:0;color:#f2c464;"><strong>Empleado:</strong> <span style="color:#f5efe6;font-weight:400;">{{ $empleado }}</span></p>
                                        @else
                                            <p style="margin:0;color:#f2c464;"><strong>Motivo:</strong> <span style="color:#f5efe6;font-weight:400;">{{ $motivo }}</span></p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            @if($tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
                                <p style="margin:0 0 14px;font-size:15px;">Gracias por confiar en nosotros.</p>
                            @elseif($tipo === \App\Notifications\CitaClienteNotification::REASIGNADA_DE_EMPLEADO)
                                <p style="margin:0 0 14px;font-size:15px;">Tu horario no cambia; solo se actualizo el empleado asignado.</p>
                            @else
                                <p style="margin:0 0 14px;font-size:15px;">Si deseas, puedes reagendar desde tu cuenta.</p>
                            @endif

                            <p style="margin:0;font-size:15px;color:#f2c464;"><strong>Atentamente, Barberia &amp; Spa</strong></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
