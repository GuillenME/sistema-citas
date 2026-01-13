<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;

            background-image: url('{{ asset("imagenes/Citas-SalaEspera.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            position: relative;
        }

        /* Oscurecer fondo como login */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        /* ===== Barra superior ===== */
        header {
            position: relative;
            z-index: 2;
            background: rgba(115, 114, 126, 0.8);
            color: #fff;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Flecha */
        .back-btn {
            background: transparent;
            color: #ffffff;
            text-decoration: none;
            font-size: 38px;
            font-weight: bold;
            padding: 6px 10px;
            cursor: pointer;

            display: flex;
            align-items: center;
            justify-content: center;

            text-shadow:
                0 0 6px rgba(255, 255, 255, 0.8),
                0 0 16px rgba(42, 22, 218, 0.8),
                0 0 32px rgba(42, 22, 218, 0.8);

            transition: transform .2s;
        }

        .back-btn:hover {
            transform: scale(1.2);
        }

        /* Logout */
        .logout-btn {
            background: transparent;
            border: 2px solid #ff0000;
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;

            box-shadow:
                0 0 18px rgba(255, 0, 0, 1),
                inset 0 0 8px rgba(255, 45, 45, 0.4);

            transition: .2s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
        }

        /* ===== CONTENEDOR ===== */
        .container {
            position: relative;
            z-index: 2;
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* CARD igual al login */
        .form-container {
            width: 360px;
            padding: 28px;
            background: rgba(17, 24, 39, .65);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 14px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .45);
            color: #fff;
            position: relative;
        }

        .form-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 24px;
            right: 24px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #1F4E79, transparent);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* ===== ERRORES (IGUAL LOGIN) ===== */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .field-error {
            color: #fecaca;
            font-size: 13px;
            margin-bottom: 10px;
            display: block;
        }

        /* INPUTS */
        select,
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 6px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255, 255, 255, .9);
            color: #111827;
        }

        select:focus,
        input:focus {
            outline: 2px solid #1F4E79;
        }

        .input-error {
            outline: 2px solid #ef4444 !important;
            background: #fee2e2;
        }

        /* BOTÓN */
        .submit-btn {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            background: #1F4E79;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(42, 22, 218, 0.8);
            transition: .2s;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(42, 22, 218, 0.8);
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                text-align: center;
            }

            .form-container {
                width: 100%;
                max-width: 360px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="header-left">
        <a href="{{ route('cliente.dashboard') }}" class="back-btn">←</a>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn" type="submit">Cerrar sesión</button>
    </form>
</header>

<div class="container">

    <div class="form-container">
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

        <form method="POST" action="{{ route('cliente.citas.store') }}" novalidate>
            @csrf

            <select name="servicio_id" class="@error('servicio_id') input-error @enderror">
                <option value="">Servicio</option>
                @foreach ($servicios as $servicio)
                    <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                @endforeach
            </select>
            @error('servicio_id')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="date" name="fecha"
                   class="@error('fecha') input-error @enderror">
            @error('fecha')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="time" name="hora"
                   class="@error('hora') input-error @enderror">
            @error('hora')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <button type="submit" class="submit-btn">
                AGENDAR
            </button>
        </form>
    </div>

</div>

</body>
</html>
