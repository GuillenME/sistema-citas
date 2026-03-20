<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Citas - Recepción</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-citas.css') }}">
</head>

<body class="recepcionista-citas-index-page">

    @include('recepcionista.partials.menu')

    <div class="container">

        <div class="table-card">
            <h2>Citas de la semana</h2>

            @if ($statusOptions->isNotEmpty())
                <div class="status-tabs">
                    @foreach ($statusOptions as $statusOption)
                        <a
                            href="{{ request()->url() }}?status={{ $statusOption['key'] }}"
                            class="status-tab {{ $selectedStatus === $statusOption['key'] ? 'active' : '' }}">
                            {{ $statusOption['label'] }} ({{ $statusOption['count'] }})
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="citas-grid">
                @forelse ($citas as $cita)
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

                        $inicioCita = \Carbon\Carbon::parse(
                            \Carbon\Carbon::parse($cita->date)->format('Y-m-d') . ' ' . $cita->getRawOriginal('start_time'),
                        );
                        $puedeReagendar = in_array($cita->status, ['confirmada', 'pendiente_anticipo'], true)
                            && (($cita->reagendas_count ?? 0) < 2)
                            && now()->lt($inicioCita);
                    @endphp

	                    <div class="cita-card">
	                        <div class="cita-header">
	                            <div class="cita-eyebrow">Servicio</div>
	                            <div class="cita-head-row">
	                                <div class="cita-title">{{ $cita->service->name }}</div>
	                                <span class="estado {{ $estadoClase }}">{{ $estadoTexto }}</span>
	                            </div>
	                        </div>

	                        <div class="cita-when">
	                            <div class="cita-when-box">
	                                <span class="when-label">Fecha</span>
	                                <strong>{{ \Carbon\Carbon::parse($cita->date)->format('d M, Y') }}</strong>
	                            </div>
	                            <div class="cita-when-box">
	                                <span class="when-label">Hora</span>
	                                <strong>{{ \Carbon\Carbon::parse($cita->start_time)->format('h:i A') }}</strong>
	                            </div>
	                        </div>

	                        <div class="cita-meta">
	                            <div class="meta-row">
	                                <span>Cliente</span>
	                                <strong>{{ trim(($cita->client->user->name ?? '') . ' ' . ($cita->client->user->last_name ?? '')) }}</strong>
	                            </div>
	                            <div class="meta-row">
	                                <span>Horario</span>
	                                <strong>{{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($cita->end_time)->format('H:i') }}</strong>
	                            </div>
	                        </div>

	                        <div class="cita-actions">
                            <button type="button"
                                class="detalle-btn"
                                data-servicio="{{ e($cita->service->name ?? 'Servicio') }}"
                                data-cliente="{{ e(trim(($cita->client->user->name ?? '') . ' ' . ($cita->client->user->last_name ?? ''))) }}"
                                data-email="{{ e($cita->client->user->email ?? '-') }}"
                                data-fecha="{{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}"
                                data-hora="{{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($cita->end_time)->format('H:i') }}"
                                data-estado="{{ e($estadoTexto) }}"
                                data-notas="{{ e($cita->notes ?? 'Sin observaciones') }}">
                                Ver detalle
                            </button>

                            @if ($puedeReagendar)
                                <button type="button"
                                    class="reagendar-btn"
                                    data-reagendar-action="{{ route('recepcionista.citas.reagendar', $cita) }}"
                                    data-service-id="{{ $cita->service_id }}"
                                    data-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d') }}">
                                    Reagendar
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="citas-empty-state">
                        No hay citas en este estado durante esta semana.
                    </div>
                @endforelse
            </div>

            @if ($citas->hasPages())
                <div class="citas-pagination">
                    {{ $citas->links('pagination::simple-bootstrap-4') }}
                </div>
            @endif

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

    <div id="detalleModal" class="modal-overlay" onclick="if(event.target === this) cerrarModalDetalle()">
        <div class="modal-content detalle-modal-content">
            <h3>Detalle de la cita</h3>
            <div class="detalle-grid">
                <div><span>Servicio</span><strong id="detalleServicio">-</strong></div>
                <div><span>Cliente</span><strong id="detalleCliente">-</strong></div>
                <div><span>Correo</span><strong id="detalleEmail">-</strong></div>
                <div><span>Fecha</span><strong id="detalleFecha">-</strong></div>
                <div><span>Horario</span><strong id="detalleHora">-</strong></div>
                <div><span>Estado</span><strong id="detalleEstado">-</strong></div>
            </div>

            <div class="detalle-notas-wrap">
                <span>Notas</span>
                <p id="detalleNotas">Sin observaciones</p>
            </div>

            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="cerrarModalDetalle()">Cerrar</button>
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

        const reagendarModal = document.getElementById('reagendarModal');
        const reagendarForm = document.getElementById('reagendarForm');
        const reagendarFecha = document.getElementById('reagendarFecha');
        const reagendarHorario = document.getElementById('reagendarHorario');
        const detalleModal = document.getElementById('detalleModal');
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

        function abrirModalDetalle(btn) {
            document.getElementById('detalleServicio').textContent = btn.getAttribute('data-servicio') || '-';
            document.getElementById('detalleCliente').textContent = btn.getAttribute('data-cliente') || '-';
            document.getElementById('detalleEmail').textContent = btn.getAttribute('data-email') || '-';
            document.getElementById('detalleFecha').textContent = btn.getAttribute('data-fecha') || '-';
            document.getElementById('detalleHora').textContent = btn.getAttribute('data-hora') || '-';
            document.getElementById('detalleEstado').textContent = btn.getAttribute('data-estado') || '-';
            document.getElementById('detalleNotas').textContent = btn.getAttribute('data-notas') || 'Sin observaciones';
            detalleModal.classList.add('active');
        }

        function cerrarModalDetalle() {
            detalleModal.classList.remove('active');
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

        document.querySelectorAll('.detalle-btn').forEach((btn) => {
            btn.addEventListener('click', () => abrirModalDetalle(btn));
        });
    </script>

    <!-- Modal de confirmacion de logout -->
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
