<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/clientes/dashboard.css') }}">
</head>
<body style="--bg-url: url('{{ asset('imagenes/registro_fondo.png') }}');">

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
