<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente</title>

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

        header {
            background: rgba(42, 22, 218, 0.8);
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        /* ===== Contenedor centrado ===== */
        .container {
            min-height: calc(100vh - 70px); /* resto del header */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(250px, 1fr));
            gap: 25px;
            max-width: 650px;
            width: 100%;
        }

        .card {
            background: rgba(42, 22, 218, 0.8);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.35);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 10px;
            color: #fff;
        }

        .card p {
            color: #f1f1f1;
            margin-bottom: 20px;
        }

        .card a {
            display: inline-block;
            padding: 10px 22px;
            background: #1F4E79;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.4);
        }

        .logout-btn {
            background: #dc2626;
            border: none;
            padding: 8px 15px;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        /* ===== Responsive ===== */
        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Cliente</h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn" type="submit">Cerrar sesión</button>
    </form>
</header>

<div class="container">

    <div class="cards">

        <!-- Agendar cita -->
        <div class="card">
            <h2>Agendar cita</h2>
            <p>Reserva una nueva cita seleccionando fecha y servicio.</p>
            <a href="{{ route('cliente.citas.create') }}">Agendar</a>
        </div>

        <!-- Consultar citas -->
        <div class="card">
            <h2>Mis citas</h2>
            <p>Consulta el estado de tu cita programadas.</p>
            <a href="{{ route('cliente.citas.index') }}">Ver cita</a>
        </div>

    </div>

</div>

</body>
</html>
