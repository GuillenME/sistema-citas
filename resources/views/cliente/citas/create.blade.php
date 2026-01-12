<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
        }

        .form-container {
            max-width: 500px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <h1>Agendar cita</h1>

    <div class="form-container">

        <form method="POST" action="{{ route('cliente.citas.store') }}">
            @csrf

            <label>Servicio</label>
            <select name="servicio_id" required>
                <option value="">Seleccione un servicio</option>
                @foreach ($servicios as $servicio)
                    <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                @endforeach
            </select>

            <label>Fecha</label>
            <input type="date" name="fecha" required>

            <label>Hora</label>
            <input type="time" name="hora" required>

            <button type="submit">Agendar cita</button>
        </form>


    </div>

    <a href="{{ route('cliente.dashboard') }}">⬅ Volver al panel</a>

</body>

</html>
