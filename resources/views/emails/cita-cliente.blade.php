<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#cfd6e1;padding:28px 12px;font-family:Arial,Helvetica,sans-serif;">
    <tr>
        <td align="center">
            <table role="presentation" width="560" cellpadding="0" cellspacing="0" border="0" style="width:560px;max-width:560px;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 8px 20px rgba(20,28,45,.18);">
                <tr>
                    <td style="background:#232936;padding:10px 14px;">
                        <span style="display:inline-block;width:10px;height:10px;background:#f04b4b;border-radius:50%;margin-right:6px;"></span>
                        <span style="display:inline-block;width:10px;height:10px;background:#f0cf6a;border-radius:50%;margin-right:6px;"></span>
                        <span style="display:inline-block;width:10px;height:10px;background:#f3f3f3;border-radius:50%;"></span>
                    </td>
                </tr>
                <tr>
                    <td style="background:#e8dccd;color:#222a36;padding:12px 18px;font-weight:700;letter-spacing:.4px;">NEW MESSAGE</td>
                </tr>
                <tr>
                    <td style="padding:14px 18px 0;color:#2c3547;font-size:14px;">
                        <div style="padding:4px 0;border-bottom:1px solid #ececec;"><strong>To:</strong> {{ $nombre }}</div>
                        <div style="padding:8px 0;border-bottom:1px solid #ececec;">
                            <strong>Subject:</strong>
                            @if($tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
                                Tu cita fue confirmada
                            @else
                                Tu cita fue cancelada
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:18px;color:#2e3648;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 10px;">Hola {{ $nombre }},</p>

                        @if($tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
                            <p style="margin:0 0 10px;">Tu cita fue confirmada y ya tiene empleado asignado.</p>
                        @else
                            <p style="margin:0 0 10px;">Tu cita fue cancelada por administracion.</p>
                        @endif

                        <p style="margin:0 0 8px;"><strong>Servicio:</strong> {{ $servicio }}</p>
                        <p style="margin:0 0 8px;"><strong>Fecha:</strong> {{ $fecha }}</p>
                        <p style="margin:0 0 8px;"><strong>Hora:</strong> {{ $hora }}</p>

                        @if($tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
                            <p style="margin:0 0 10px;"><strong>Empleado:</strong> {{ $empleado }}</p>
                        @else
                            <p style="margin:0 0 10px;"><strong>Motivo:</strong> {{ $motivo }}</p>
                        @endif

                        @if($tipo === \App\Notifications\CitaClienteNotification::CONFIRMADA_CON_EMPLEADO)
                            <p style="margin:0;">Gracias por confiar en nosotros.</p>
                        @else
                            <p style="margin:0;">Si deseas, puedes reagendar desde tu cuenta.</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="background:#e8dccd;padding:12px 18px;color:#202737;font-size:13px;">
                        Barberia & Spa | Sistema de citas
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
