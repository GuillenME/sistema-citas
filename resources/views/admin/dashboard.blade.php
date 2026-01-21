<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>

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
            max-width: 900px;
            padding: 40px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 32px;
            letter-spacing: 1px;
            text-shadow:
                0 0 10px rgba(99, 102, 241, .8),
                0 0 25px rgba(99, 102, 241, .6);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .15);
            transition: transform .25s, box-shadow .25s;
            box-shadow:
                0 0 25px rgba(99, 102, 241, .35),
                inset 0 0 10px rgba(99, 102, 241, .25);
        }

        .card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow:
                0 0 35px rgba(99, 102, 241, .9),
                inset 0 0 15px rgba(99, 102, 241, .5);
        }

        .card span {
            display: block;
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card strong {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .logout {
            display: flex;
            justify-content: center;
        }

        .logout button {
            background: transparent;
            border: 2px solid #ef4444;
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            letter-spacing: 1px;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(239, 68, 68, .7),
                inset 0 0 8px rgba(239, 68, 68, .4);
        }

        .logout button:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 25px rgba(239, 68, 68, 1),
                inset 0 0 12px rgba(239, 68, 68, .6);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard">

        <h1>Panel del Administrador</h1>

        <div class="cards">
            <a href="{{ route('admin.citas.index') }}" class="card">
                <span>📅</span>
                <strong>Gestionar citas</strong>
            </a>

            <a href="{{ route('admin.servicios.index') }}" class="card">
                <span>✂️</span>
                <strong>Gestionar servicios</strong>
            </a>

            <a href="{{ route('admin.promociones.index') }}" class="card">
                <span>🎉</span>
                <strong>Gestionar promociones</strong>
            </a>

            <a href="{{ route('admin.empleados.index') }}" class="card">
                <span>👨‍💼</span>
                <strong>Gestionar empleados</strong>
            </a>

            <a href="{{ route('admin.clientes.index') }}" class="card">
                <span>👨‍💼</span>
                <strong>Gestionar clientes</strong>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="logout">
            @csrf
            <button type="submit">Cerrar sesión</button>
        </form>

    </div>

</body>

</html>
