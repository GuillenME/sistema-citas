<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recepcionista</title>

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

            background-image: url('{{ asset("imagenes/recepFon.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 0;
        }

        /* ===== HEADER ===== */
        header {
            position: relative;
            z-index: 2;
            background: rgba(90, 90, 90, 0.75); 
            color: #fff;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;

            box-shadow: 0 0 25px rgba(160, 160, 160, 0.6);
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        /* LOGOUT */
        .logout-btn {
            background: transparent;
            border: 2px solid #ff2d2d;
            padding: 10px 18px;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;

            box-shadow:
                0 0 12px rgba(255, 45, 45, 0.9),
                inset 0 0 6px rgba(255, 45, 45, 0.4);

            transition: transform .2s, box-shadow .2s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 18px rgba(255, 45, 45, 1),
                inset 0 0 10px rgba(255, 45, 45, 0.6);
        }

        /* ===== CONTENEDOR ===== */
        .container {
            position: relative;
            z-index: 2;
            min-height: calc(100vh - 90px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* ===== TARJETAS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(250px, 1fr));
            gap: 30px;
            max-width: 720px;
            width: 100%;
        }

        .card {
            background: rgba(17, 24, 39, 0.65);
            backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 16px;
            text-align: center;

            border: 1px solid rgba(255, 255, 255, 0.15);

            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.45),
                0 0 20px rgba(160, 160, 160, 0.6);

            transition: transform .25s, box-shadow .25s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow:
                0 18px 45px rgba(0, 0, 0, 0.6),
                0 0 28px rgba(200, 200, 200, 0.9);
        }

        .card h2 {
            margin-bottom: 12px;
            color: #fff;
            font-size: 21px;
            letter-spacing: .5px;
        }

        .card p {
            color: #e5e7eb;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .card a {
            display: inline-block;
            width: 100%;
            padding: 14px;
            background: #7a7a7a; /* BOTÓN GRIS */
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;

            box-shadow: 0 6px 20px rgba(180, 180, 180, 0.8);
            transition: transform .2s, box-shadow .2s;
        }

        .card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(220, 220, 220, 1);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .cards {
                grid-template-columns: 1fr;
                max-width: 520px;
            }
        }

        @media (max-width: 600px) {
            header {
                flex-direction: column;
                text-align: center;
                padding: 15px;
            }

            header h1 {
                font-size: 20px;
            }

            .container {
                padding: 20px;
            }

            .card {
                padding: 24px;
            }

            .logout-btn {
                width: 100%;
                max-width: 240px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Recepcionista</h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn" type="submit">
            Cerrar sesión
        </button>
    </form>
</header>

<div class="container">

    <div class="cards">

        <div class="card">
            <h2>Agendar cita</h2>
            <p>Registra una nueva cita para un cliente.</p>
            <a href="{{ route('recepcionista.citas.create') }}">AGENDAR</a>
        </div>

        <div class="card">
            <h2>Citas del día</h2>
            <p>Consulta y administra las citas programadas.</p>
            <a href="{{ route('recepcionista.citas.index') }}">VER CITAS</a>
        </div>

    </div>

</div>

</body>
</html>
