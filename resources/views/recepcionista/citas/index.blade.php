<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Citas - Recepcion</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-citas.css') }}">
</head>

<body style="--bg-url: url('{{ asset('imagenes/SalaEsperaa.png') }}')">

    @include('recepcionista.partials.menu')

    <div class="container">

        <div class="table-card">
            <h2>Historial de citas</h2>

            <div class="citas-grid">
                @foreach ($citas as $cita)
                    @php
                        $estadoClase = match ($cita->status) {
                            'pendiente_anticipo' => 'pendiente',
                            'confirmada' => 'confirmada',
                            'cancelada' => 'cancelada',
                            default => 'pendiente',
                        };

                        $estadoTexto = match ($cita->status) {
                            'pendiente_anticipo' => 'Pendiente de anticipo',
                            'confirmada' => 'Confirmada',
                            'cancelada' => 'Cancelada',
                            default => ucfirst($cita->status),
                        };
                    @endphp

                    <div class="cita-card">
                        <div class="cita-header">
                            <div class="cita-title">{{ $cita->service->name }}</div>
                            <span class="estado {{ $estadoClase }}">{{ $estadoTexto }}</span>
                        </div>

                        <div class="cita-meta">
                            <div><strong>Cliente:</strong> {{ $cita->client->user->name ?? '' }} {{ $cita->client->user->last_name ?? '' }}</div>
                            <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}</div>
                            <div><strong>Hora:</strong> {{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($cita->end_time)->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <script>
        // Modal de confirmacion de logout
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

    <!-- Modal de confirmacion de logout -->
    <div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
        <div class="modal-content">
            <h3>¿Cerrar sesion?</h3>
            <p>¿Estas seguro de que deseas cerrar sesion?</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Si, cerrar sesion</button>
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
            </div>
        </div>
    </div>

</body>

</html>
