<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }

        table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #1f2937;
            color: #fff;
        }
    </style>
</head>

<body>

    <h1>Mis citas</h1>

    <table>
        <tr>
            <th>Servicio</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
        </tr>

        @foreach ($citas as $cita)
            <tr>
                <td>{{ $cita->servicio->nombre }}</td>
                <td>{{ $cita->fecha }}</td>
                <td>{{ $cita->hora }}</td>
                <td>{{ ucfirst($cita->estado) }}</td>
            </tr>
        @endforeach
    </table>


<a href="{{ route('cliente.dashboard') }}">Volver</a>

</body>

</html>
