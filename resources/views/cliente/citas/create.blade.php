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
        }

        /* ===== Barra superior ===== */
        header {
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

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        /* ===== Flecha ===== */
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
                0 0 12px rgba(255, 255, 255, 0.6),
                0 0 20px rgba(255, 255, 255, 0.4);

            transition:
                transform 0.2s ease,
                text-shadow 0.3s ease;
        }

        .back-btn:hover {
            transform: scale(1.2);
            text-shadow:
                0 0 10px rgba(255, 255, 255, 1),
                0 0 18px rgba(255, 255, 255, 0.8),
                0 0 28px rgba(255, 255, 255, 0.6);
        }

        /* ===== Logout ===== */
        .logout-btn {
            background: transparent;
            border: 2px solid #ff0000;
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 0.5px;

            box-shadow:
                0 0 18px rgba(255, 0, 0, 1),
                inset 0 0 8px rgba(255, 45, 45, 0.4);

            transition: 
                background 0.3s ease,
                box-shadow 0.3s ease,
                transform 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 45, 45, 0.15);
            box-shadow:
                0 0 14px rgba(255, 45, 45, 1),
                inset 0 0 12px rgba(255, 45, 45, 0.6);
            transform: scale(1.05);
        }

        /* ===== Contenido ===== */
        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, .75);
        }

        .form-container h2 {
            margin-top: 0;
            text-align: center;
            font-size: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 14px;
        }

        button.submit-btn {
            margin-top: 20px;
            background: #1F4E79;
            color: #fff;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        button.submit-btn:hover {
            background: #0d273f;
        }

        /* ===== RESPONSIVE ===== */

        /* Tablets */
        @media (max-width: 900px) {
            .container {
                padding: 20px;
            }
        }

        /* Celulares */
        @media (max-width: 600px) {

            header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 15px;
            }

            .header-left {
                justify-content: center;
            }

            .back-btn {
                font-size: 32px;
            }

            .logout-btn {
                width: 100%;
                max-width: 220px;
            }

            .container {
                padding: 15px;
            }

            .form-container {
                padding: 20px;
            }

            .form-container h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="header-left">
        <a href="{{ route('cliente.dashboard') }}" class="back-btn" title="Volver">←</a>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn" type="submit">
            Cerrar sesión
        </button>
    </form>
</header>

<div class="container">

    <div class="form-container">

        <h2>Formulario de cita</h2>

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

            <button type="submit" class="submit-btn">
                Agendar cita
            </button>
        </form>

    </div>

</div>

</body>
</html>
