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

<body class="recepcionista-citas-index-page">

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
                            'completada' => 'confirmada',
                            'no_asistio' => 'cancelada',
                            'cancelada' => 'cancelada',
                            default => 'pendiente',
                        };

                        $estadoTexto = match ($cita->status) {
                            'pendiente_anticipo' => 'Pendiente de anticipo',
                            'confirmada' => 'Confirmada',
                            'completada' => 'Completada',
                            'no_asistio' => 'No asistio',
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

                        @if (in_array($cita->status, ['confirmada', 'pendiente_anticipo'], true) && (($cita->reagendas_count ?? 0) < 2))
                            <div class="cita-actions">
                                <button type="button"
                                    class="reagendar-btn"
                                    data-reagendar-action="{{ route('recepcionista.citas.reagendar', $cita) }}"
                                    data-service-id="{{ $cita->service_id }}"
                                    data-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d') }}">
                                    Reagendar
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <div id="reagendarModal" class="modal-overlay" onclick="if(event.target === this) cerrarModalReagenda()">
        <div class="modal-content">
            <h3>Reagendar cita</h3>
            <form method="POST" id="reagendarForm">
                @csrf
                <label for="reagendarFecha">Nueva fecha</label>
                <input type="date" id="reagendarFecha" name="fecha" required>

                <label for="reagendarHorario">Nuevo horario</label>
                <select id="reagendarHorario" name="hora_inicio" required>
                    <option value="">Selecciona un horario</option>
                </select>

                <label for="reagendarObs">Observaciones (opcional)</label>
                <textarea id="reagendarObs" name="observaciones" rows="3" placeholder="Comentario de la reagenda..."></textarea>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-cancel" onclick="cerrarModalReagenda()">Cancelar</button>
                    <button type="submit" class="modal-btn modal-btn-confirm">Confirmar</button>
                </div>
            </form>
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

        const reagendarModal = document.getElementById('reagendarModal');
        const reagendarForm = document.getElementById('reagendarForm');
        const reagendarFecha = document.getElementById('reagendarFecha');
        const reagendarHorario = document.getElementById('reagendarHorario');
        let reagendarServiceId = '';

        function formatHora12(hora24) {
            if (!hora24) return '';
            const partes = hora24.split(':');
            const h = parseInt(partes[0], 10);
            const m = partes[1] || '00';
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12 = ((h + 11) % 12) + 1;
            return `${h12}:${m} ${ampm}`;
        }

        async function cargarBloquesReagenda() {
            if (!reagendarServiceId || !reagendarFecha.value) return;

            reagendarHorario.innerHTML = '<option value="">Cargando...</option>';

            try {
                const res = await fetch(`/citas/bloques?servicio_id=${encodeURIComponent(reagendarServiceId)}&fecha=${encodeURIComponent(reagendarFecha.value)}`);
                const bloques = await res.json();
                reagendarHorario.innerHTML = '';

                if (!Array.isArray(bloques) || bloques.length === 0) {
                    reagendarHorario.innerHTML = '<option value="">No hay horarios disponibles</option>';
                    return;
                }

                const opt0 = document.createElement('option');
                opt0.value = '';
                opt0.textContent = 'Selecciona un horario';
                reagendarHorario.appendChild(opt0);

                bloques.forEach((b) => {
                    const opt = document.createElement('option');
                    opt.value = b.inicio;
                    opt.textContent = `${formatHora12(b.inicio)} - ${formatHora12(b.fin)}`;
                    reagendarHorario.appendChild(opt);
                });
            } catch (e) {
                reagendarHorario.innerHTML = '<option value="">Error al cargar horarios</option>';
            }
        }

        function abrirModalReagenda(action, serviceId, currentDate) {
            reagendarForm.setAttribute('action', action);
            reagendarServiceId = serviceId || '';
            const today = new Date().toISOString().split('T')[0];
            reagendarFecha.min = today;
            reagendarFecha.value = currentDate || today;
            reagendarHorario.innerHTML = '<option value="">Selecciona un horario</option>';
            reagendarModal.classList.add('active');
            cargarBloquesReagenda();
        }

        function cerrarModalReagenda() {
            reagendarModal.classList.remove('active');
        }

        reagendarFecha.addEventListener('change', cargarBloquesReagenda);

        document.querySelectorAll('.reagendar-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                abrirModalReagenda(
                    btn.getAttribute('data-reagendar-action'),
                    btn.getAttribute('data-service-id'),
                    btn.getAttribute('data-date')
                );
            });
        });
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
