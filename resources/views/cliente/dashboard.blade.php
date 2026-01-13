<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente</title>

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
            background-image: url('{{ asset("imagenes/registro_fondo.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* ===== HEADER ===== */
        header {
            background: rgba(42, 22, 218, 0.8);
            color: #fff;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        .logout-btn {
            background: #dc2626;
            border: none;
            padding: 10px 18px;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        /* ===== CONTENEDOR ===== */
        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* ===== TARJETAS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(250px, 1fr));
            gap: 25px;
            max-width: 700px;
            width: 100%;
        }

        .card {
            background: rgba(42, 22, 218, 0.85);
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.35);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 10px;
            color: #fff;
            font-size: 20px;
        }

        .card p {
            color: #f1f1f1;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .card a {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: #1F4E79;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.4);
        }

        /* ===== RESPONSIVE ===== */

        /* Tablets */
        @media (max-width: 900px) {
            .cards {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
        }

        /* Celulares */
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
                padding: 20px;
            }

            .card h2 {
                font-size: 18px;
            }

            .card p {
                font-size: 14px;
            }

            .logout-btn {
                width: 100%;
                max-width: 220px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Cliente</h1>

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
            <p>Reserva una nueva cita seleccionando fecha y servicio.</p>
            <a href="{{ route('cliente.citas.create') }}">Agendar</a>
        </div>

        <div class="card">
            <h2>Mis citas</h2>
            <p>Consulta el estado de tu cita programadas.</p>
            <a href="{{ route('cliente.citas.index') }}">Ver cita</a>
        </div>

    </div>

</div>

</body>
</html>
