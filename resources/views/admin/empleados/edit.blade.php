<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>

    <style>
        body {
            background: #0f172a;
            color: #e5e7eb;
            font-family: Arial;
            padding: 40px;
        }

        form {
            max-width: 400px;
            margin: auto;
            background: rgba(17,24,39,.85);
            padding: 30px;
            border-radius: 12px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border-radius: 6px;
            border: none;
        }

        button {
            background: #3b82f6;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1 style="text-align:center">Editar Empleado</h1>

<form method="POST" action="{{ route('admin.empleados.update', $empleado) }}">
    @csrf
    @method('PUT')

    <input type="text" value="{{ $empleado->usuario?->nombre }}" disabled>

    <input type="text" name="especialidad" value="{{ $empleado->especialidad }}" required>

    <select name="activo">
        <option value="1" @selected($empleado->activo)>Activo</option>
        <option value="0" @selected(!$empleado->activo)>Inactivo</option>
    </select>

    <button>Actualizar</button>
</form>

</body>
</html>
