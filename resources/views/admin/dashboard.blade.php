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
                <span>👥</span>
                <strong>Gestionar clientes</strong>
            </a>

            <a href="{{ route('admin.recepcionistas.index') }}" class="card">
                <span>👩🏽‍💻👨🏽‍💻</span>
                <strong>Gestionar recepcionistas</strong>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="logout" id="logoutForm">
            @csrf
            <button type="button" onclick="mostrarModalLogout()">Cerrar sesión</button>
        </form>

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
