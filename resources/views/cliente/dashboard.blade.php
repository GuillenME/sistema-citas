<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/dashboard.css') }}">
</head>
<body class="cliente-dashboard-page">

@include('cliente.partials.menu')

<main class="container">
    <section class="hero">
        <div class="hero-copy">
            <p class="hero-tag">Panel del cliente</p>
            <h2>Bienvenido a tu espacio personal "{{ auth()->user()->name }}"</h2>
            <p class="hero-text">
                En Barbería & Spa cuidamos cada detalle para que tu experiencia sea única. Desde aquí puedes
                agendar, revisar y gestionar tus citas sin complicaciones.
            </p>

            <div class="hero-actions">
                <a class="action-card" href="{{ route('cliente.citas.create') }}">
                    <span class="action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="17" rx="3"></rect>
                            <path d="M8 2v4M16 2v4M3 10h18"></path>
                            <path d="M12 13v5M9.5 15.5h5"></path>
                        </svg>
                    </span>
                    <span class="action-title">Agendar cita</span>
                    <span class="action-desc">Reserva tu próximo servicio en segundos.</span>
                </a>
                <a class="action-card" href="{{ route('cliente.citas.index') }}">
                    <span class="action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 3h6l1 2h4v16H4V5h4l1-2Z"></path>
                            <path d="M8 10h8M8 14h8M8 18h5"></path>
                        </svg>
                    </span>
                    <span class="action-title">Mis citas</span>
                    <span class="action-desc">Consulta tu historial y comprobantes.</span>
                </a>
                <a class="action-card" href="{{ route('cliente.comentarios') }}">
                    <span class="action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 5h16v11a3 3 0 0 1-3 3H9l-5 3V5Z"></path>
                            <path d="M8 10h8M8 14h5"></path>
                        </svg>
                    </span>
                    <span class="action-title">Comentarios</span>
                    <span class="action-desc">Comparte tu experiencia con nosotros.</span>
                </a>
            </div>

            <div class="visual-note">
                <strong>Tip:</strong> Tu anticipo se confirma en minutos cuando subes el comprobante.
            </div>
        </div>

        <div class="hero-visual">
            <div class="collage">
                <div class="collage-tile tile-1" role="img" aria-label="Collage 1"></div>
                <div class="collage-tile tile-2" role="img" aria-label="Collage 2"></div>
                <div class="collage-tile tile-3" role="img" aria-label="Collage 3"></div>
                <div class="collage-tile tile-4" role="img" aria-label="Collage 4"></div>
            </div>
        </div>
    </section>
</main>

@include('cliente.partials.footer')

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
