<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Promociones</title>

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
            max-width: 1000px;
            padding: 40px;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 35px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-arrow {
            font-size: 28px;
            text-decoration: none;
            color: #a5b4fc;
            transition: transform .2s, text-shadow .2s;
            text-shadow: 0 0 10px rgba(99,102,241,.7);
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            text-shadow: 0 0 20px rgba(99,102,241,1);
        }

        h1 {
            font-size: 32px;
            letter-spacing: 1px;
            text-shadow:
                0 0 10px rgba(99,102,241,.8),
                0 0 25px rgba(99,102,241,.6);
        }

        .btn-create {
            background: transparent;
            border: 2px solid #22c55e;
            color: #fff;
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            letter-spacing: 1px;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(34,197,94,.7),
                inset 0 0 8px rgba(34,197,94,.4);
        }

        .btn-create:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 25px rgba(34,197,94,1),
                inset 0 0 12px rgba(34,197,94,.6);
        }

        /* ===== TABLE ===== */
        .table-container {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow:
                0 0 25px rgba(99,102,241,.35),
                inset 0 0 10px rgba(99,102,241,.25);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 14px;
            font-size: 14px;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,.2);
        }

        td {
            padding: 14px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        tr:hover {
            background: rgba(99,102,241,.08);
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-on {
            background: rgba(34,197,94,.2);
            color: #4ade80;
            box-shadow: 0 0 10px rgba(34,197,94,.6);
        }

        .badge-off {
            background: rgba(239,68,68,.2);
            color: #f87171;
            box-shadow: 0 0 10px rgba(239,68,68,.6);
        }

        .btn-edit {
            background: transparent;
            border: 1px solid #60a5fa;
            color: #fff;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            box-shadow: 0 0 10px rgba(96,165,250,.6);
            transition: box-shadow .2s;
        }

        .btn-edit:hover {
            box-shadow: 0 0 20px rgba(96,165,250,1);
        }

        /* ===== LOGOUT ===== */
        .logout {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .btn-logout {
            background: transparent;
            border: 2px solid #ef4444;
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            letter-spacing: 1px;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(239,68,68,.7),
                inset 0 0 8px rgba(239,68,68,.4);
        }

        .btn-logout:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 25px rgba(239,68,68,1),
                inset 0 0 12px rgba(239,68,68,.6);
        }
    </style>
</head>

<body>

<div class="dashboard">

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="back-arrow">←</a>
            <h1>Promociones</h1>
        </div>

        <div style="display:flex; gap:15px; align-items:center;">
            <a href="{{ route('admin.promociones.create') }}" class="btn-create">
                + Nueva promoción
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Publicada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($promociones as $promo)
            <tr>
                <td>{{ $promo->titulo }}</td>
                <td>{{ \Carbon\Carbon::parse($promo->fecha_publicacion)->format('d/m/Y') }}</td>
                <td>
                    @if($promo->publicada)
                        <span class="badge badge-on">Sí</span>
                    @else
                        <span class="badge badge-off">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.promociones.edit', $promo) }}" class="btn-edit">
                        Editar
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
</html>
