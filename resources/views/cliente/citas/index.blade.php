<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>

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

            background-image: url('{{ asset("imagenes/SalaEspera.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            position: relative;
        }

        /* Overlay oscuro */
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
            background: rgba(42, 22, 218, 0.75);
            color: #fff;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;

            box-shadow: 0 0 25px rgba(42, 22, 218, 0.6);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        /* Flecha */
        .back-btn {
            background: transparent;
            color: #ffffff;
            text-decoration: none;
            font-size: 34px;
            font-weight: bold;
            cursor: pointer;

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
            border: 2px solid #ff2d2d;
            padding: 8px 18px;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;

            box-shadow:
                0 0 12px rgba(255, 45, 45, 0.9),
                inset 0 0 6px rgba(255, 45, 45, 0.4);

            transition: transform .2s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
        }

        /* ===== CONTENEDOR ===== */
        .container {
            position: relative;
            z-index: 2;
            padding: 40px 30px;
        }

        .table-card {
            max-width: 900px;
            margin: auto;

            background: rgba(17, 24, 39, 0.65);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 25px;

            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.45),
                0 0 20px rgba(42, 22, 218, 0.6);
        }

        .table-card h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            color: #fff;
            letter-spacing: 1px;
        }

        /* ===== TABLA ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            color: #e5e7eb;
            font-size: 14px;
        }

        thead {
            background: rgba(31, 41, 55, 0.9);
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            color: #fff;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Estado */
        .estado {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .estado.pendiente {
            background: rgba(234, 179, 8, 0.2);
            color: #fde68a;
        }

        .estado.confirmada {
            background: rgba(34, 197, 94, 0.2);
            color: #bbf7d0;
        }

        .estado.cancelada {
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 700px) {

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tbody tr {
                margin-bottom: 15px;
                padding: 12px;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.05);
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                font-weight: bold;
                color: #9ca3af;
                text-align: left;
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

    <div class="table-card">
        <h2>Historial de citas</h2>

        <table>
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($citas as $cita)
                    <tr>
                        <td data-label="Servicio">{{ $cita->servicio->nombre }}</td>
                        <td data-label="Fecha">{{ $cita->fecha }}</td>
                        <td data-label="Hora">{{ $cita->hora }}</td>
                        <td data-label="Estado">
                            <span class="estado {{ $cita->estado }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

</body>
</html>
