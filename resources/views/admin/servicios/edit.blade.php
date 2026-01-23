<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar servicio</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #1e1b4b, #020617);
            color: #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard {
            width: 100%;
            max-width: 720px;
            padding: 40px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-arrow {
            font-size: 28px;
            text-decoration: none;
            color: #a5b4fc;
            text-shadow: 0 0 10px rgba(99,102,241,.7);
            transition: transform .2s, text-shadow .2s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            text-shadow: 0 0 20px rgba(99,102,241,1);
        }

        h1 {
            font-size: 30px;
            letter-spacing: 1px;
            text-shadow:
                0 0 10px rgba(99,102,241,.8),
                0 0 25px rgba(99,102,241,.6);
        }

        /* FORM */
        .form-container {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow:
                0 0 25px rgba(99,102,241,.35),
                inset 0 0 10px rgba(99,102,241,.25);
        }

        form label {
            display: block;
            margin-top: 18px;
            margin-bottom: 6px;
            font-size: 14px;
            letter-spacing: 1px;
        }

        form input,
        form textarea,
        form select {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(2, 6, 23, .8);
            color: #fff;
            font-size: 14px;
            outline: none;
        }

        form textarea {
            resize: vertical;
            min-height: 90px;
        }

        form input:focus,
        form textarea:focus,
        form select:focus {
            border-color: #818cf8;
            box-shadow: 0 0 10px rgba(129,140,248,.7);
        }

        .btn-submit {
            margin-top: 30px;
            width: 100%;
            background: transparent;
            border: 2px solid #facc15;
            color: #fff;
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            letter-spacing: 1px;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(250,204,21,.7),
                inset 0 0 8px rgba(250,204,21,.4);
        }

        .btn-submit:hover {
            transform: scale(1.03);
            box-shadow:
                0 0 25px rgba(250,204,21,1),
                inset 0 0 12px rgba(250,204,21,.6);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <div class="header">
        <div class="header-left">
            <!-- Volver a servicios -->
            <a href="{{ route('admin.servicios.index') }}" class="back-arrow">←</a>
            <h1>Editar servicio</h1>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('admin.servicios.update', $servicio) }}">
            @csrf
            @method('PUT')

            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ $servicio->nombre }}">

            <label>Descripción</label>
            <textarea name="descripcion">{{ $servicio->descripcion }}</textarea>

            <label>Duración (min)</label>
            <input type="number" name="duracion_minutos" value="{{ $servicio->duracion_minutos }}">

            <label>Precio</label>
            <input type="number" step="0.01" name="precio" value="{{ $servicio->precio }}">

            <label>Activo</label>
            <select name="activo">
                <option value="1" {{ $servicio->activo ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$servicio->activo ? 'selected' : '' }}>No</option>
            </select>

            <button type="submit" class="btn-submit">
                Actualizar
            </button>
        </form>
    </div>

</div>

</body>
</html>
