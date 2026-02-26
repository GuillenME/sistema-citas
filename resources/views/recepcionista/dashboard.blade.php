<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de recepcionista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-dashboard.css') }}">
</head>
<body class="recepcionista-dashboard-page">

@include('recepcionista.partials.menu')

<main class="container">
    <section class="hero">
        <div class="hero-copy">
            <p class="hero-tag">Recepcionista</p>
            <h2>Bienvenido a tu panel de control</h2>
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
                <li>Confirmar citas de mañana</li>
            </ul>
        </div>
        <div class="secondary-card recent-card">
            <h3>Citas mas recientes</h3>
            <div class="recent-list">
                @forelse ($citasRecientes as $cita)
                    @php
                        $estadoTexto = match ($cita->status) {
                            'pendiente_anticipo' => 'Pendiente',
                            'confirmada' => 'Confirmado',
                            'cancelada' => 'Cancelada',
                            'completada' => 'Completada',
                            'no_asistio' => 'No asistio',
                            default => ucfirst($cita->status),
                        };
                        $estadoClase = match ($cita->status) {
                            'confirmada', 'completada' => 'confirmada',
                            'cancelada', 'no_asistio' => 'cancelada',
                            default => 'pendiente',
                        };
                    @endphp
                    <article class="recent-item">
                        <div>
                            <p class="recent-name">{{ $cita->client->user->name ?? 'Cliente' }} {{ $cita->client->user->last_name ?? '' }}</p>
                            <p class="recent-service">{{ $cita->service->name ?? 'Servicio' }}</p>
                        </div>
                        <div class="recent-meta">
                            <p class="recent-time">{{ optional($cita->date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }}</p>
                            <p class="recent-status recent-status--{{ $estadoClase }}">{{ $estadoTexto }}</p>
                        </div>
                    </article>
                @empty
                    <p class="recent-empty">Aun no hay citas registradas.</p>
                @endforelse
            </div>
        </div>
        <div class="secondary-card">
            <h3>📌 Recordatorio</h3>
            @forelse ($recordatorios as $recordatorio)
                <p style="margin-bottom: 8px;">• {{ $recordatorio->message }}</p>
            @empty
                <p>Mantén actualizada la agenda y avisa a clientes ante cambios.</p>
            @endforelse
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
