<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('{{ asset('imagenes/RegistrarSala.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 0;
            pointer-events: none;
        }

        header, .container {
            position: relative;
            z-index: 1;
        }

        header {
            background: rgba(115,114,126,.85);
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            font-size: 38px;
            color: #fff;
            text-decoration: none;
            text-shadow: 0 0 10px rgba(255,255,255,.8);
            transition: .2s;
        }

        .back-btn:hover { transform: scale(1.2); }

        .logout-btn {
            background: transparent;
            border: 2px solid #ff0000;
            color: #fff;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 0 14px rgba(255,0,0,1);
        }

        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(17,24,39,.85);
            padding: 28px;
            border-radius: 16px;
            color: #fff;
            box-shadow: 0 0 25px rgba(42,22,218,.6);
        }

        h2 { text-align: center; }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        select:focus, input:focus {
            outline: 2px solid #1F4E79;
        }

        /* ===== ERRORES ===== */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .input-error {
            outline: 2px solid #ef4444 !important;
            background: #fee2e2;
        }

        .field-error {
            display: block;
            margin-top: 4px;
            font-size: 13px;
            color: #fecaca;
        }

        .submit-btn {
            margin-top: 22px;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background: #1F4E79;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
            box-shadow: 0 6px 20px rgba(42,22,218,.8);
        }

        .anticipo {
            margin-top: 25px;
            padding: 18px;
            background: rgba(255,255,255,.08);
            border-radius: 12px;
            text-align: center;
            font-size: 14px;
        }

        .anticipo h4 { color: #fde68a; }

        @media (max-width: 600px) {
            header { flex-direction: column; }
            .back-btn { font-size: 32px; }
        }
    </style>
</head>

<body>

<header>
    <a href="{{ route('cliente.dashboard') }}" class="back-btn">←</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn">Cerrar sesión</button>
    </form>
</header>

<div class="container">
    <div class="card">

        <h2>Agendar cita</h2>

        {{-- ERRORES GENERALES --}}
        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cliente.citas.store') }}">
            @csrf

            <label>Servicio</label>
            <select name="servicio_id" class="@error('servicio_id') input-error @enderror">
                <option value="">Selecciona un servicio</option>
                @foreach ($servicios as $servicio)
                    <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->nombre }}
                    </option>
                @endforeach
            </select>
            @error('servicio_id')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label>Fecha</label>
            <input type="date"
                   name="fecha"
                   value="{{ old('fecha') }}"
                   min="{{ now()->toDateString() }}"
                   class="@error('fecha') input-error @enderror">
            @error('fecha')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label>Horario</label>
            <select name="hora_inicio" class="@error('hora_inicio') input-error @enderror">
                <option value="">Selecciona un horario</option>
            </select>
            @error('hora_inicio')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <button type="submit" class="submit-btn">
                AGENDAR CITA
            </button>
        </form>

        <div class="anticipo">
            <h4>⚠ Anticipo requerido</h4>
            <p>Se solicita un <strong>50%</strong> para confirmar la cita</p>
            <p>Banco: BBVA<br>Cuenta: 1234567890<br>CLABE: 012345678901234567</p>
        </div>

    </div>
</div>

</body>
</html>
