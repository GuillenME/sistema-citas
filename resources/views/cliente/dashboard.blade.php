<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Cliente</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
        }

        header {
            background: #1f2937;
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

        .container {
            padding: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 10px;
            color: #1f2937;
        }

        .card p {
            color: #555;
            margin-bottom: 20px;
        }

        .card a {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .card a:hover {
            background: #1d4ed8;
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
    </style>
</head>
<body>

<header>
    <h1>Panel del Cliente</h1>

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
            <p>Consulta el estado de tus citas programadas.</p>
            <a href="{{ route('cliente.citas.index') }}">Ver citas</a>
        </div>

    </div>

</div>

</body>
</html>
