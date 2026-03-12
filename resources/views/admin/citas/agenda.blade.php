@extends('layouts.admin')

@section('title', 'Agenda visual')
@section('back-url', route('admin.citas.index'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/citas-agenda.css') }}">
@endsection

@section('header-actions')
    <a href="{{ route('admin.citas.index') }}" class="agenda-header-btn is-ghost">Ver listado</a>
    <a href="{{ route('admin.citas.create') }}" class="agenda-header-btn is-primary">Nueva cita</a>
@endsection

@section('content')
    @php
        $previousDate = $fechaSeleccionada->copy()->subDay()->toDateString();
        $nextDate = $fechaSeleccionada->copy()->addDay()->toDateString();
        $currentDate = $fechaSeleccionada->toDateString();
        $slotHeight = 74;
        $boardHeight = max($slots->count() * $slotHeight, 420);
        $queryBase = [
            'ocultar_descansos' => $ocultarDescansos ? 1 : null,
            'ocultar_sin_citas' => $ocultarSinCitas ? 1 : null,
        ];
    @endphp

    <div class="agenda-shell">
        <section class="agenda-toolbar">
            <div class="agenda-toolbar-copy">
                <span class="agenda-kicker">Operacion diaria</span>
                <h2>{{ $fechaSeleccionada->translatedFormat('l, d \\d\\e F') }}</h2>
                <p>Visualiza la carga por empleado, detecta huecos y revisa el estado de cada cita por horario.</p>
            </div>

            <div class="agenda-toolbar-actions">
                <div class="agenda-nav-actions">
                    <a href="{{ route('admin.citas.agenda', array_filter(array_merge($queryBase, ['fecha' => $previousDate]), fn ($value) => $value !== null)) }}" class="agenda-pill-btn">Dia anterior</a>
                    <a href="{{ route('admin.citas.agenda', array_filter(array_merge($queryBase, ['fecha' => now()->toDateString()]), fn ($value) => $value !== null)) }}" class="agenda-pill-btn">Hoy</a>
                    <a href="{{ route('admin.citas.agenda', array_filter(array_merge($queryBase, ['fecha' => $nextDate]), fn ($value) => $value !== null)) }}" class="agenda-pill-btn">Dia siguiente</a>
                </div>

                <form method="GET" action="{{ route('admin.citas.agenda') }}" class="agenda-date-form">
                    <label for="agendaDate" class="sr-only">Seleccionar fecha</label>
                    <input type="date" id="agendaDate" name="fecha" value="{{ $currentDate }}" class="agenda-date-input">
                    <label class="agenda-toggle-pill">
                        <input type="checkbox" name="ocultar_descansos" value="1" {{ $ocultarDescansos ? 'checked' : '' }}>
                        <span class="agenda-toggle-indicator" aria-hidden="true"></span>
                        <span>Ocultar descansos</span>
                    </label>
                    <label class="agenda-toggle-pill">
                        <input type="checkbox" name="ocultar_sin_citas" value="1" {{ $ocultarSinCitas ? 'checked' : '' }}>
                        <span class="agenda-toggle-indicator" aria-hidden="true"></span>
                        <span>Ocultar sin citas</span>
                    </label>
                    <button type="submit" class="agenda-pill-btn is-apply">Aplicar</button>
                </form>
            </div>
        </section>

        <section class="agenda-stats">
            <article class="agenda-stat-card">
                <span>Total del dia</span>
                <strong>{{ $stats['total'] }}</strong>
            </article>
            <article class="agenda-stat-card">
                <span>Confirmadas</span>
                <strong>{{ $stats['confirmadas'] }}</strong>
            </article>
            <article class="agenda-stat-card">
                <span>Completadas</span>
                <strong>{{ $stats['completadas'] }}</strong>
            </article>
            <article class="agenda-stat-card">
                <span>Ingreso estimado</span>
                <strong>${{ number_format($stats['ingresos_estimados'], 2) }}</strong>
            </article>
        </section>

        <section class="agenda-board-shell">
            @if ($lanes->isEmpty())
                <div class="agenda-empty-state">
                    <h3>No hay empleados activos para mostrar la agenda.</h3>
                    <p>Activa al menos un empleado para distribuir las citas en esta vista.</p>
                </div>
            @else
                <div class="agenda-board-scroll">
                    <div class="agenda-board" style="--agenda-height: {{ $boardHeight }}px;">
                        <div class="agenda-time-column">
                            <div class="agenda-column-head agenda-time-head">Hora</div>
                            <div class="agenda-time-grid">
                                @foreach ($slots as $slot)
                                    <div class="agenda-time-slot {{ $slot['is_hour'] ? 'is-hour' : '' }}">
                                        <span>{{ $slot['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="agenda-lanes">
                            @foreach ($lanes as $lane)
                                <section class="agenda-lane {{ !empty($lane['is_rest_day']) ? 'is-rest-day' : '' }}">
                                    <header class="agenda-column-head">
                                        <strong>{{ $lane['name'] }}</strong>
                                        <span>{{ $lane['subtitle'] }}</span>
                                    </header>

                                    <div class="agenda-column-body">
                                        <div class="agenda-grid-lines">
                                            @foreach ($slots as $slot)
                                                <div class="agenda-grid-line {{ $slot['is_hour'] ? 'is-hour' : '' }}"></div>
                                            @endforeach
                                        </div>

                                        @forelse ($lane['appointments'] as $appointment)
                                            @php
                                                $statusClass = match ($appointment['status']) {
                                                    'confirmada' => 'is-confirmada',
                                                    'completada' => 'is-completada',
                                                    'cancelada' => 'is-cancelada',
                                                    'no_asistio' => 'is-no-asistio',
                                                    'pendiente_anticipo' => 'is-pendiente',
                                                    default => 'is-default',
                                                };
                                            @endphp
                                            <button type="button" class="agenda-appointment-card {{ $statusClass }}"
                                                data-appointment='@json($appointment['details'])'
                                                style="top: {{ $appointment['top_percent'] }}%; height: max({{ $appointment['height_percent'] }}%, 84px);">
                                                <div class="agenda-appointment-time">{{ $appointment['time_range'] }}</div>
                                                <h4>{{ $appointment['service'] }}</h4>
                                            </button>
                                        @empty
                                            <div class="agenda-lane-empty">
                                                <span>{{ !empty($lane['is_rest_day']) ? 'Descanso programado' : 'Sin citas asignadas' }}</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <div id="agendaAppointmentModal" class="agenda-modal" aria-hidden="true">
        <div class="agenda-modal-box">
            <div class="agenda-modal-head">
                <div>
                    <span class="agenda-modal-kicker">Detalle rapido</span>
                    <h3 id="agendaModalService">Servicio</h3>
                </div>
                <button type="button" class="agenda-modal-close" id="agendaModalClose" aria-label="Cerrar"></button>
            </div>
            <div class="agenda-modal-body">
                <div class="agenda-modal-grid">
                    <div class="agenda-modal-card">
                        <span>Horario</span>
                        <strong id="agendaModalTime">-</strong>
                    </div>
                    <div class="agenda-modal-card">
                        <span>Cliente</span>
                        <strong id="agendaModalClient">-</strong>
                    </div>
                    <div class="agenda-modal-card">
                        <span>Empleado</span>
                        <strong id="agendaModalEmployee">-</strong>
                    </div>
                    <div class="agenda-modal-card">
                        <span>Estado</span>
                        <strong id="agendaModalStatus">-</strong>
                    </div>
                    <div class="agenda-modal-card">
                        <span>Anticipo</span>
                        <strong id="agendaModalDeposit">-</strong>
                    </div>
                    <div class="agenda-modal-card">
                        <span>Restante</span>
                        <strong id="agendaModalRemaining">-</strong>
                    </div>
                </div>
                <div class="agenda-modal-notes">
                    <span>Notas</span>
                    <p id="agendaModalNotes">Sin comentarios adicionales.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            var modal = document.getElementById('agendaAppointmentModal');
            var closeBtn = document.getElementById('agendaModalClose');
            if (!modal || !closeBtn) return;

            var fieldMap = {
                service: document.getElementById('agendaModalService'),
                time_range: document.getElementById('agendaModalTime'),
                client: document.getElementById('agendaModalClient'),
                employee: document.getElementById('agendaModalEmployee'),
                status: document.getElementById('agendaModalStatus'),
                deposit: document.getElementById('agendaModalDeposit'),
                remaining: document.getElementById('agendaModalRemaining'),
                notes: document.getElementById('agendaModalNotes')
            };

            function openModal(payload) {
                fieldMap.service.textContent = payload.service || 'Servicio';
                fieldMap.time_range.textContent = payload.time_range || '-';
                fieldMap.client.textContent = payload.client || '-';
                fieldMap.employee.textContent = payload.employee || '-';
                fieldMap.status.textContent = payload.status || '-';
                fieldMap.deposit.textContent = payload.deposit || '-';
                fieldMap.remaining.textContent = payload.remaining || '-';
                fieldMap.notes.textContent = payload.notes || 'Sin comentarios adicionales.';
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('.agenda-appointment-card').forEach(function(card) {
                card.addEventListener('click', function() {
                    var raw = card.getAttribute('data-appointment') || '{}';
                    try {
                        openModal(JSON.parse(raw));
                    } catch (e) {
                        openModal({});
                    }
                });
            });

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
        })();
    </script>
@endsection
