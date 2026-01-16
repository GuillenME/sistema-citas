<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
</head>

<body>

    <h1>Panel del Administrador</h1>

    <ul>
        <li>
            <a href="{{ route('admin.citas.index') }}">
                Gestionar citas
            </a>
        </li>

        <li>
            <a href="{{ route('admin.servicios.index') }}">
                Gestionar servicios
            </a>
        </li>
        <li>
            <a href="{{ route('admin.promociones.index') }}">
                Gestionar promociones
            </a>
        </li>

    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>

</body>

</html>
