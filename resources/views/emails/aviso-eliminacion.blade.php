<!DOCTYPE html>
<html>
<body>
    <h2>Hola {{ $usuario->nombre }}</h2>

    <p>
        Tu cuenta ha estado desactivada por varios días.
    </p>

    <p>
        ⏳ Si no reactivas tu cuenta en los próximos <strong>5 días</strong>,
        será eliminada automáticamente del sistema.
    </p>

    <p>
        Para conservar tu información, solo inicia sesión nuevamente.
    </p>

    <p style="color:#dc2626;font-weight:bold;">
        Este es un aviso automático.
    </p>
</body>
</html>
