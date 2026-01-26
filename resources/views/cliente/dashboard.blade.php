<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; }

        body{
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            background-image: url('{{ asset("imagenes/registro_fondo.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        /* OVERLAY */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 0;
        }

        /* ===== HEADER ===== */
        header{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: rgba(2, 6, 23, 0.95);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1000;
            backdrop-filter: blur(6px);
        }

        .title {
            color: #93c5fd;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 45px;
        }

        nav a {
            color: #e5e7eb;
            text-decoration: none;
            font-size: 17px;
            font-weight: bold;
            padding: 6px 0;
            transition: color .2s, text-shadow .2s;
        }

        nav a:hover {
            color: #93c5fd;
            text-shadow: 0 0 10px rgba(255,255,255,.9);
        }

        .logout-btn {
            background: transparent;
            border: 2px solid #ff2d2d;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;

            box-shadow:
                0 0 12px rgba(255,45,45,.9),
                inset 0 0 6px rgba(255,45,45,.4);

            transition: transform .2s, box-shadow .2s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 18px rgba(255,45,45,1),
                inset 0 0 10px rgba(255,45,45,.6);
        }

        /* ===== CONTENIDO ===== */
        .container {
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 90px);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 40px;
        }

        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1100px;
            width: 100%;
            align-items: center;
        }

        /* IMAGEN IZQUIERDA */
        .dashboard-image {
            width: 107%;
            height: 360px;
            border-radius: 18px;
            background-image: url('{{ asset("imagenes/hombreCorte.png") }}');
            background-size: cover;
            background-position: center;
        }

        /* TARJETA BIENVENIDA */
        .welcome {
            background: rgba(17,24,39,.65);
            backdrop-filter: blur(12px);
            padding: 34px;
            border-radius: 18px;
            color: #fff;

            box-shadow:
                0 0 25px rgba(42,22,218,.6),
                inset 0 0 20px rgba(42,22,218,.25);
        }

        .welcome h2 {
            margin-top: 0;
            font-size: 26px;
        }

        .welcome p {
            color: #e5e7eb;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }

            .dashboard-image {
                height: 260px;
            }

            .welcome {
                text-align: center;
            }
        }

        @media (max-width: 600px) {
            header {
                grid-template-columns: 1fr;
                gap: 15px;
                text-align: center;
            }

            nav {
                flex-wrap: wrap;
                gap: 25px;
            }

            .logout-btn {
                justify-self: center;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="title">Cliente</div>

    <nav>
        <a href="{{ route('cliente.citas.create') }}">Agendar cita</a>
        <a href="{{ route('cliente.citas.index') }}">Mis citas</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            Cerrar sesión
        </button>
    </form>
</header>

<div class="container">
    <div class="dashboard-content">

        <!-- IMAGEN -->
        <div class="dashboard-image"></div>

        <!-- BIENVENIDA -->
        <div class="welcome">
            <h2>Bienvenido!!</h2>
            <p>
                Desde aquí puedes agendar nuevas citas, consultar el estado
                de las que ya tienes programadas.
            </p>
        </div>

    </div>
</div>

</body>
</html>
