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
            gap: 45px;
        }

        nav a {
            color: #e5e7eb;
            text-decoration: none;
            font-size: 17px;
            font-weight: bold;
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
        }

        .logout-btn:hover {
            transform: scale(1.05);
        }

        /* ===== CONTENIDO ===== */
        .container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding-top: 110px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1100px;
            width: 100%;
            align-items: center;
            padding: 40px;
        }

        .dashboard-image {
            height: 360px;
            border-radius: 18px;
            background-image: url('{{ asset("imagenes/hombreCorte.png") }}');
            background-size: cover;
            background-position: center;
        }

        .welcome {
            background: rgba(17,24,39,.35);
            backdrop-filter: blur(12px);
            padding: 34px;
            border-radius: 18px;
            box-shadow:
                0 0 25px rgba(42,22,218,.6),
                inset 0 0 20px rgba(42,22,218,.25);
        }

        .welcome h2 {
            margin-top: 0;
            font-size: 26px;
        }

        .welcome p {
            font-size: 15px;
            line-height: 1.6;
        }

        /* ===== MODAL LOGOUT ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.7);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: rgba(17,24,39,.95);
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 0 25px rgba(255,45,45,.6);
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .modal-btn {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            border: none;
        }

        .modal-btn-confirm {
            background: #ef4444;
            color: #fff;
        }

        .modal-btn-cancel {
            background: #6b7280;
            color: #fff;
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
    </style>
</head>
<body>

<header>
    <div class="title">Cliente</div>

    <nav>
        <a href="{{ route('cliente.citas.create') }}">Agendar cita</a>
        <a href="{{ route('cliente.citas.index') }}">Mis citas</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">
            Cerrar sesión
        </button>
    </form>
</header>

<div class="container">
    <div class="dashboard-content">
        <div class="dashboard-image"></div>

        <div class="welcome">
            <h2>Bienvenido 👋</h2>
            <p>
                Desde aquí puedes agendar nuevas citas y consultar el estado
                de las que ya tienes programadas.
            </p>
        </div>
    </div>
</div>

<!-- MODAL LOGOUT -->
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

<script>
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

</body>
</html>
