<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Barberia</title>
</head>

<body style="margin:0;padding:0;background:#0f0a07;font-family:Arial,sans-serif;color:#f4efe8;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#0f0a07;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#1b120d;border:1px solid #3d2b1f;border-radius:18px;overflow:hidden;">
                    <tr>
                        <td style="padding:32px 32px 20px;background:linear-gradient(135deg,#24160d,#120b08);">
                            <p style="margin:0 0 10px;color:#f2c27d;font-size:12px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;">Barberia & Spa</p>
                            <h1 style="margin:0;color:#ffffff;font-size:32px;line-height:1.15;">Tu cuenta fue creada con exito</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px 34px;">
                            <p style="margin:0 0 14px;font-size:16px;line-height:1.7;">Hola {{ $nombre }}, te damos la bienvenida. Un administrador registro tu cuenta para que puedas acceder al sistema de citas.</p>
                            <p style="margin:0 0 14px;font-size:16px;line-height:1.7;">Tu cuenta quedo asociada al correo <strong style="color:#ffffff;">{{ $email }}</strong>.</p>
                            <p style="margin:0 0 24px;font-size:16px;line-height:1.7;">Por seguridad, no enviamos contraseñas por correo. Usa el siguiente boton para definir tu acceso:</p>
                            <p style="margin:0 0 26px;">
                                <a href="{{ $url }}" style="display:inline-block;background:#e28b1e;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:700;letter-spacing:0.04em;">Definir contraseña</a>
                            </p>
                            <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#d7cabd;">Si el boton no funciona, copia y pega este enlace en tu navegador:</p>
                            <p style="margin:0;font-size:13px;line-height:1.7;color:#f2c27d;word-break:break-all;">{{ $url }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
