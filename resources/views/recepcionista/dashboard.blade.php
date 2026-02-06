<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de recepcionista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-dashboard.css') }}">
</head>
<body style="--bg-url: url('{{ asset('imagenes/registro_fondo3.png') }}');">

@include('recepcionista.partials.menu')

<main class="container">
    <section class="hero">
        <div class="hero-copy">
            <p class="hero-tag">Recepcionista</p>
            <h2>Bienvenida a tu panel de control</h2>
            <p class="hero-text">
                Organiza tus citas y mantén el flujo del día bajo control.
            </p>

            <div class="hero-actions">
                <a class="action-card" href="{{ route('recepcionista.citas.create') }}">
                    <span class="action-title">Agendar cita</span>
                    <span class="action-desc">Registra una nueva cita en segundos.</span>
                </a>
                <a class="action-card" href="{{ route('recepcionista.citas.index') }}">
                    <span class="action-title">Citas del día</span>
                    <span class="action-desc">Consulta el historial y el estado actual.</span>
                </a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="summary-card">
                <h3>Resumen rápido</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span class="summary-label">Citas hoy</span>
                        <span class="summary-value">{{ $citasHoy }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Pendientes</span>
                        <span class="summary-value">{{ $pendientes }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Confirmadas</span>
                        <span class="summary-value">{{ $confirmadas }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Canceladas</span>
                        <span class="summary-value">{{ $canceladas }}</span>
                    </div>
                </div>
                <p class="summary-note">Actualiza durante el día para mantener el control.</p>
            </div>
        </div>
    </section>

    <section class="secondary">
        <div class="secondary-card">
            <h3>📌 Recomendaciones diarias</h3>
            <ul class="checklist">
                <li>Revisar agenda del día</li>
                <li>Registrar nuevas citas</li>
                <li>Atender cambios de horario</li>
            </ul>
        </div>
        <div class="secondary-card">
            <h3>📌 Recordatorio</h3>
            <p>Mantén actualizada la agenda y avisa a clientes ante cambios.</p>
        </div>
    </section>
</main>

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
