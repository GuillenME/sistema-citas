<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo empleado</title>

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
            max-width: 600px;
            padding: 40px;
        }

        /* HEADER */
        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 35px;
        }

        .back-arrow {
            font-size: 26px;
            text-decoration: none;
            color: #a5b4fc;
            text-shadow: 0 0 10px rgba(99,102,241,.7);
            transition: transform .2s, text-shadow .2s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            text-shadow: 0 0 20px rgba(99,102,241,1);
        }

        h2 {
            font-size: 30px;
            letter-spacing: 1px;
            text-shadow:
                0 0 10px rgba(99,102,241,.8),
                0 0 25px rgba(99,102,241,.6);
        }

        /* FORM CARD */
        .form-card {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow:
                0 0 25px rgba(99,102,241,.35),
                inset 0 0 10px rgba(99,102,241,.25);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #c7d2fe;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 6px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(2, 6, 23, .7);
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border .2s, box-shadow .2s;
        }

        input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 12px rgba(129,140,248,.7);
        }

        /* ERROR STATES */
        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 14px rgba(239,68,68,.9);
        }

        .error-text {
            color: #f87171;
            font-size: 12px;
            margin-bottom: 16px;
            display: block;
            text-shadow: 0 0 6px rgba(239,68,68,.7);
        }

        .btn-save {
            width: 100%;
            background: transparent;
            border: 2px solid #22c55e;
            color: #fff;
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(34,197,94,.7),
                inset 0 0 8px rgba(34,197,94,.4);
        }

        .btn-save:hover {
            transform: scale(1.03);
            box-shadow:
                0 0 25px rgba(34,197,94,1),
                inset 0 0 12px rgba(34,197,94,.6);
        }
    </style>
</head>

<body>

<div class="dashboard">

    <div class="header">
        <a href="{{ route('admin.empleados.index') }}" class="back-arrow">←</a>
        <h2>Nuevo empleado</h2>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.empleados.store') }}">
            @csrf

            <label>Nombre</label>
            <input
                type="text"
                name="nombre"
                value="{{ old('nombre') }}"
                class="@error('nombre') input-error @enderror"
                placeholder="Nombre">
            @error('nombre')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <label>Teléfono</label>
            <input
                type="text"
                name="telefono"
                value="{{ old('telefono') }}"
                class="@error('telefono') input-error @enderror"
                placeholder="Teléfono">
            @error('telefono')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <label>Especialidad</label>
            <input
                type="text"
                name="especialidad"
                value="{{ old('especialidad') }}"
                class="@error('especialidad') input-error @enderror"
                placeholder="Especialidad">
            @error('especialidad')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <button type="submit" class="btn-save">
                Guardar empleado
            </button>
        </form>
    </div>

</div>

</body>
</html>
