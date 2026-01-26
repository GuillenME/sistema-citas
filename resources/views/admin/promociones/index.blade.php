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

        /* Modal de confirmación */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: rgba(17, 24, 39, 0.95);
            padding: 30px;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            color: #fff;
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.6);
            border: 2px solid rgba(239, 68, 68, 0.5);
        }

        .modal-content h3 {
            margin-bottom: 20px;
            font-size: 20px;
            color: #fff;
        }

        .modal-content p {
            margin-bottom: 25px;
            color: #e5e7eb;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: transform .2s;
        }

        .modal-btn:hover {
            transform: scale(1.05);
        }

        .modal-btn-confirm {
            background: #ef4444;
            color: #fff;
            box-shadow: 0 0 14px rgba(239, 68, 68, 0.7);
        }

        .modal-btn-cancel {
            background: #6b7280;
            color: #fff;
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

            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="button" class="btn-logout" onclick="mostrarModalLogout()">
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
                    <th>Descuento</th>
                    <th>Servicios</th>
                    <th>Fecha</th>
                    <th>Publicada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        @foreach ($promociones as $promo)
            <tr>
                <td>{{ $promo->title }}</td>
                <td>{{ $promo->discount }}%</td>
                <td>
                    @if($promo->servicios->count() > 0)
                        @foreach($promo->servicios as $servicio)
                            <span style="display: inline-block; background: rgba(99,102,241,.2); padding: 4px 8px; border-radius: 6px; margin: 2px; font-size: 12px;">
                                {{ $servicio->name }}
                            </span>
                        @endforeach
                    @else
                        <span style="color: #9ca3af; font-size: 12px;">Sin servicios</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}</td>
                <td>
                    @if($promo->published)
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
        </table>
    </div>
</div>

<script>
// Modal de confirmación de logout
function mostrarModalLogout() {
    document.getElementById('modalLogout').classList.add('active');
}

function cerrarModalLogout() {
    document.getElementById('modalLogout').classList.remove('active');
}

function confirmarLogout() {
    document.getElementById('logoutForm').submit();
}
</script>

<!-- Modal de confirmación de logout -->
<div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro de que deseas cerrar sesión?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Sí, cerrar sesión</button>
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
        </div>
    </div>
</div>

</body>
</html>
