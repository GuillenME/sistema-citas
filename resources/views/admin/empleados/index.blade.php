<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empleados</title>

    <style>
        body {
            background: #0f172a;
            color: #e5e7eb;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        h1 { text-align: center; margin-bottom: 30px; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(17, 24, 39, .85);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: center;
        }

        th {
            background: rgba(31, 41, 55, .9);
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:not(:last-child) {
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .activo { color: #22c55e; font-weight: bold; }
        .inactivo { color: #ef4444; font-weight: bold; }

        .btn {
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 13px;
        }

        .btn-edit { background: #3b82f6; }
        .btn-delete { background: #ef4444; }

        a { color: #93c5fd; text-decoration: none; }
    </style>
</head>

<body>

<h1>Empleados</h1>

<a href="{{ route('admin.empleados.create') }}">➕ Nuevo empleado</a>

<br><br>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Especialidad</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($empleados as $empleado)
            <tr>
                <td>{{ $empleado->nombre }}</td>
                <td>{{ $empleado->especialidad }}</td>
                <td class="{{ $empleado->activo ? 'activo' : 'inactivo' }}">
                    {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                </td>
                <td>
                    <a class="btn btn-edit" href="{{ route('admin.empleados.edit', $empleado) }}">Editar</a>

                    <form method="POST" action="{{ route('admin.empleados.destroy', $empleado) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-delete">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<a href="{{ route('admin.dashboard') }}">⬅ Volver</a>

</body>
</html>
