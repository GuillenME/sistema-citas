@extends('layouts.recepcionista')

@section('title', 'Citas')
@section('back-url', route('recepcionista.dashboard'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/citas-agenda.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/citas-index.css') }}">
    <style>
        #agendaAppointmentModal .agenda-modal-box {
            width: min(920px, calc(100vw - 36px));
            max-width: 920px;
            max-height: calc(100vh - 32px);
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #agendaAppointmentModal .agenda-modal-body {
            overflow-y: auto;
            max-height: calc(100vh - 180px);
            padding-bottom: 28px;
            scrollbar-width: thin;
            scrollbar-color: rgba(214, 160, 87, 0.6) rgba(20, 8, 4, 0.25);
        }

        #agendaAppointmentModal .agenda-modal-body::-webkit-scrollbar {
            width: 10px;
        }

        #agendaAppointmentModal .agenda-modal-body::-webkit-scrollbar-track {
            background: rgba(20, 8, 4, 0.22);
            border-radius: 999px;
        }

        #agendaAppointmentModal .agenda-modal-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(232, 191, 116, 0.92), rgba(185, 119, 47, 0.92));
            border-radius: 999px;
        }

        #agendaAppointmentModal .citas-detail-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px 20px;
            padding: 22px 24px 18px;
        }

        #agendaAppointmentModal .citas-detail-summary-item > span,
        #agendaAppointmentModal .citas-detail-extra > span,
        #agendaAppointmentModal .citas-detail-comment-box > span {
            display: block;
            margin-bottom: 8px;
            color: #d5a870;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        #agendaAppointmentModal .citas-detail-summary-item strong {
            color: #fff1d8;
            font-size: 22px;
            line-height: 1.25;
        }

        #agendaAppointmentModal .citas-detail-summary-item strong span {
            color: inherit;
            font-size: inherit;
            font-weight: inherit;
            letter-spacing: normal;
            text-transform: none;
            display: inline;
        }

        #agendaAppointmentModal .citas-detail-comment-box,
        #agendaAppointmentModal .citas-detail-extra {
            margin: 0 24px 16px;
            padding: 16px 18px 14px;
            border-radius: 14px;
            background: rgba(33, 14, 8, 0.42);
            border: 1px solid rgba(212, 155, 80, 0.16);
        }

        #agendaAppointmentModal .citas-detail-comment-box p,
        #agendaAppointmentModal .citas-detail-extra div {
            margin: 0;
            color: #f0dfc8;
            font-size: 13px;
            line-height: 1.6;
        }

        #agendaAppointmentModal .citas-detail-timeline {
            display: grid;
            gap: 14px;
        }

        #agendaAppointmentModal .timeline-item {
            display: grid;
            grid-template-columns: 12px 1fr;
            gap: 12px;
            align-items: start;
        }

        #agendaAppointmentModal .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            margin-top: 4px;
            background: linear-gradient(135deg, #d79a45, #f5c97d);
            box-shadow: 0 0 0 4px rgba(216, 154, 69, 0.14);
        }

        #agendaAppointmentModal .timeline-copy {
            display: grid;
            gap: 2px;
        }

        #agendaAppointmentModal .timeline-copy strong {
            color: #fff1d8;
            font-size: 14px;
        }

        #agendaAppointmentModal .timeline-copy span,
        #agendaAppointmentModal .timeline-copy small {
            color: #d9bea0;
        }

        #agendaAppointmentModal .citas-detail-actions-stack {
            display: grid;
            gap: 12px;
            padding: 0 24px 18px;
        }

        #agendaAppointmentModal .citas-detail-actions-stack form {
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(212, 155, 80, 0.12);
        }

        #agendaAppointmentModal .citas-detail-actions-stack label {
            color: #f0dfc8;
            font-size: 13px;
            font-weight: 700;
        }

        #agendaAppointmentModal .citas-detail-actions-stack input,
        #agendaAppointmentModal .citas-detail-actions-stack select,
        #agendaAppointmentModal .citas-detail-actions-stack textarea {
            min-height: 42px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid rgba(212, 155, 80, 0.24);
            background: rgba(20, 8, 4, 0.42);
            color: #fff1d8;
        }

        #agendaAppointmentModal .citas-detail-actions-stack input {
            min-width: 150px;
        }

        #agendaAppointmentModal input[readonly] {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.07), rgba(20, 8, 4, 0.42));
            color: #f8efdf;
            cursor: default;
            box-shadow: inset 0 0 0 1px rgba(212, 155, 80, 0.14);
        }

        #agendaAppointmentModal input[readonly]::-webkit-outer-spin-button,
        #agendaAppointmentModal input[readonly]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #agendaAppointmentModal input[readonly][type=number] {
            -moz-appearance: textfield;
        }

        #agendaAppointmentModal .citas-detail-footer {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            padding: 18px 24px 20px;
            background: rgba(255, 255, 255, 0.04);
            border-top: 1px solid rgba(212, 155, 80, 0.14);
        }

        #agendaAppointmentModal .citas-detail-footer .btn {
            min-height: 46px;
            border-radius: 14px;
        }

        #agendaAppointmentModal .citas-detail-footer .btn-cancel {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(212, 155, 80, 0.24);
            color: #f2e4cf;
            box-shadow: none;
        }

        #agendaAppointmentModal .citas-detail-footer .btn-save {
            background: linear-gradient(135deg, #b9772f, #d6a057);
            border-color: rgba(214, 160, 87, 0.35);
            color: #231308;
            box-shadow: 0 8px 20px rgba(76, 41, 20, 0.28);
        }

        #agendaCancelModal .modal-box,
        #agendaRescheduleModal .modal-box {
            max-width: 620px;
        }

        #agendaCancelModal form,
        #agendaRescheduleModal form {
            display: grid !important;
            gap: 12px;
        }

        #agendaCancelModal input,
        #agendaCancelModal select,
        #agendaCancelModal textarea,
        #agendaRescheduleModal input,
        #agendaRescheduleModal select,
        #agendaRescheduleModal textarea {
            width: 100%;
            min-height: 42px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(212, 155, 80, 0.24);
            background: rgba(20, 8, 4, 0.42);
            color: #fff1d8;
        }

        @media (max-width: 760px) {
            #agendaAppointmentModal .citas-detail-summary-grid,
            #agendaAppointmentModal .citas-detail-footer {
                grid-template-columns: 1fr;
            }

            #agendaAppointmentModal .agenda-modal-box {
                width: calc(100vw - 20px);
                max-height: calc(100vh - 16px);
            }

            #agendaAppointmentModal .agenda-modal-body {
                max-height: calc(100vh - 132px);
            }
        }
    </style>
@endsection

@section('header-actions')
    <a href="{{ route('recepcionista.citas.create') }}" class="agenda-header-btn is-primary">Nueva cita</a>
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
                    <a href="{{ route('recepcionista.citas.agenda', array_filter(array_merge($queryBase, ['fecha' => $previousDate]), fn ($value) => $value !== null)) }}" class="agenda-pill-btn">Dia anterior</a>
                    <a href="{{ route('recepcionista.citas.agenda', array_filter(array_merge($queryBase, ['fecha' => now()->toDateString()]), fn ($value) => $value !== null)) }}" class="agenda-pill-btn">Hoy</a>
                    <a href="{{ route('recepcionista.citas.agenda', array_filter(array_merge($queryBase, ['fecha' => $nextDate]), fn ($value) => $value !== null)) }}" class="agenda-pill-btn">Dia siguiente</a>
                </div>

                <form method="GET" action="{{ route('recepcionista.citas.agenda') }}" class="agenda-date-form">
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
                <span>Ingreso registrado</span>
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
                                                    'break' => 'is-break',
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
                    <span class="agenda-modal-kicker">Detalle operativo</span>
                    <h3 id="agendaModalService">Servicio</h3>
                </div>
                <button type="button" class="agenda-modal-close" id="agendaModalClose" aria-label="Cerrar"></button>
            </div>
            <div class="agenda-modal-body">
                <div class="citas-detail-summary-grid">
                    <div class="citas-detail-summary-item">
                        <span>Origen</span>
                        <strong id="agendaModalOrigin">-</strong>
                    </div>
                    <div class="citas-detail-summary-item">
                        <span>Horario</span>
                        <strong id="agendaModalTime">-</strong>
                    </div>
                    <div class="citas-detail-summary-item">
                        <span>Cliente</span>
                        <strong id="agendaModalClient">-</strong>
                    </div>
                    <div class="citas-detail-summary-item">
                        <span>Empleado</span>
                        <strong id="agendaModalEmployee">-</strong>
                    </div>
                    <div class="citas-detail-summary-item">
                        <span>Estado</span>
                        <strong id="agendaModalStatus">-</strong>
                    </div>
                    <div class="citas-detail-summary-item">
                        <span>Precio servicio</span>
                        <strong>$<span id="agendaModalPrice">0.00</span></strong>
                    </div>
                    <div class="citas-detail-summary-item is-positive">
                        <span>Anticipo</span>
                        <strong>$<span id="agendaModalDeposit">0.00</span></strong>
                    </div>
                    <div class="citas-detail-summary-item is-danger">
                        <span>Restante</span>
                        <strong>$<span id="agendaModalRemaining">0.00</span></strong>
                    </div>
                    <div class="citas-detail-summary-item" id="agendaDeadlineCard">
                        <span>Tiempo restante</span>
                        <strong id="agendaModalDeadline">-</strong>
                    </div>
                </div>

                <div class="citas-detail-comment-box">
                    <span>Notas</span>
                    <p id="agendaModalNotes">Sin comentarios adicionales.</p>
                </div>

                <div class="citas-detail-extra citas-detail-timeline-box">
                    <span>Historial</span>
                    <div id="agendaModalTimeline" class="citas-detail-timeline"></div>
                </div>

                <div class="citas-detail-extra" id="agendaReceiptCard">
                    <span>Comprobante</span>
                    <div id="agendaModalReceipt">-</div>
                </div>

                <div class="citas-detail-actions-stack">
                    <form method="POST" id="agendaAssignForm" class="assign-inline" style="display:none;">
                        @csrf
                        <select name="empleado_id" id="agendaAssignEmployeeSelect" class="select-compact">
                            <option value="">Seleccionar empleado</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-save btn-compact">Asignar</button>
                    </form>

                    <form method="POST" id="agendaConfirmForm" class="assign-inline" style="display:none;">
                        @csrf
                        <label>Monto recibido</label>
                        <input type="number" name="anticipo_monto" id="agendaModalAnticipoMonto" step="0.01" min="0" required>
                        <button type="submit" class="btn btn-save btn-compact">Confirmar anticipo</button>
                    </form>

                    <form method="POST" id="agendaRejectPaymentForm" class="assign-inline" style="display:none;">
                        @csrf
                        <button type="submit" class="btn btn-cancel btn-compact">Rechazar anticipo</button>
                    </form>

                    <form method="POST" id="agendaCompleteForm" class="assign-inline" style="display:none;">
                        @csrf
                        <label>Pago restante</label>
                        <input type="number" name="pago_final" id="agendaModalPagoFinal" step="0.01" min="0" required readonly>
                        <small class="profile-help" style="color:#d6c1a4;">Se completa automaticamente con el restante del servicio.</small>
                        <button type="submit" class="btn btn-save btn-compact">Marcar completada</button>
                    </form>

                    <form method="POST" id="agendaNoShowForm" class="assign-inline" style="display:none;">
                        @csrf
                        <button type="submit" class="btn btn-cancel btn-compact">Marcar no asistio</button>
                    </form>
                </div>

                <div class="citas-detail-footer">
                    <button type="button" class="btn btn-cancel" id="agendaOpenCancel">Cancelar cita</button>
                    <a href="#" target="_blank" rel="noopener" class="btn btn-cancel btn-compact" id="agendaPrintTicket">Imprimir ticket</a>
                    <button type="button" class="btn btn-save btn-compact" id="agendaOpenReschedule">Reagendar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="agendaCancelModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <div class="citas-detail-modal-head">
                <h3>Cancelar cita</h3>
                <button type="button" class="citas-detail-close-icon" id="agendaCancelModalClose" aria-label="Cerrar">x</button>
            </div>
            <form method="POST" id="agendaCancelForm" class="assign-inline" style="display:flex;">
                @csrf
                <label for="agendaCancelObservaciones">Motivo u observaciones</label>
                <textarea id="agendaCancelObservaciones" name="observaciones" rows="4" class="select-compact" placeholder="Comentario de cancelacion..."></textarea>
                <button type="submit" class="btn btn-cancel btn-compact">Confirmar cancelacion</button>
            </form>
        </div>
    </div>

    <div id="agendaRescheduleModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <div class="citas-detail-modal-head">
                <h3>Reagendar cita</h3>
                <button type="button" class="citas-detail-close-icon" id="agendaRescheduleModalClose" aria-label="Cerrar">x</button>
            </div>
            <form method="POST" id="agendaRescheduleForm" class="assign-inline" style="display:flex;">
                @csrf
                <label for="agendaRescheduleDate">Nueva fecha</label>
                <input type="date" id="agendaRescheduleDate" name="fecha" required>

                <label for="agendaRescheduleTime">Nuevo horario</label>
                <select id="agendaRescheduleTime" name="hora_inicio" class="select-compact" required>
                    <option value="">Selecciona un horario</option>
                </select>

                <label for="agendaRescheduleNotes">Observaciones</label>
                <textarea id="agendaRescheduleNotes" name="observaciones" rows="4" class="select-compact" placeholder="Comentario de reagenda..."></textarea>

                <button type="submit" class="btn btn-save btn-compact">Guardar reagenda</button>
            </form>
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
                origin: document.getElementById('agendaModalOrigin'),
                service: document.getElementById('agendaModalService'),
                time_range: document.getElementById('agendaModalTime'),
                client: document.getElementById('agendaModalClient'),
                employee: document.getElementById('agendaModalEmployee'),
                status: document.getElementById('agendaModalStatus'),
                price: document.getElementById('agendaModalPrice'),
                deposit: document.getElementById('agendaModalDeposit'),
                remaining: document.getElementById('agendaModalRemaining'),
                notes: document.getElementById('agendaModalNotes')
            };
            var timelineContainer = document.getElementById('agendaModalTimeline');
            var receiptCard = document.getElementById('agendaReceiptCard');
            var receiptWrap = document.getElementById('agendaModalReceipt');
            var deadlineCard = document.getElementById('agendaDeadlineCard');
            var deadlineValue = document.getElementById('agendaModalDeadline');
            var assignForm = document.getElementById('agendaAssignForm');
            var confirmForm = document.getElementById('agendaConfirmForm');
            var rejectForm = document.getElementById('agendaRejectPaymentForm');
            var completeForm = document.getElementById('agendaCompleteForm');
            var noShowForm = document.getElementById('agendaNoShowForm');
            var openCancelBtn = document.getElementById('agendaOpenCancel');
            var openRescheduleBtn = document.getElementById('agendaOpenReschedule');
            var ticketBtn = document.getElementById('agendaPrintTicket');
            var cancelModal = document.getElementById('agendaCancelModal');
            var cancelForm = document.getElementById('agendaCancelForm');
            var cancelClose = document.getElementById('agendaCancelModalClose');
            var rescheduleModal = document.getElementById('agendaRescheduleModal');
            var rescheduleForm = document.getElementById('agendaRescheduleForm');
            var rescheduleClose = document.getElementById('agendaRescheduleModalClose');
            var rescheduleDate = document.getElementById('agendaRescheduleDate');
            var rescheduleTime = document.getElementById('agendaRescheduleTime');
            var currentServiceId = '';
            var deadlineTimer = null;

            function openModal(payload) {
                fieldMap.origin.textContent = payload.origin || '-';
                fieldMap.service.textContent = payload.service || 'Servicio';
                fieldMap.time_range.textContent = payload.time_range || '-';
                fieldMap.client.textContent = payload.client || '-';
                fieldMap.employee.textContent = payload.employee || '-';
                fieldMap.status.textContent = payload.status || '-';
                fieldMap.price.textContent = payload.price || '0.00';
                fieldMap.deposit.textContent = payload.deposit_amount || '0.00';
                fieldMap.remaining.textContent = payload.remaining_amount || '0.00';
                fieldMap.notes.textContent = payload.notes || 'Sin comentarios adicionales.';

                if (timelineContainer) {
                    var timeline = Array.isArray(payload.timeline) ? payload.timeline : [];
                    timelineContainer.innerHTML = timeline.length
                        ? timeline.map(function(item) {
                            return '<div class="timeline-item"><div class="timeline-dot"></div><div class="timeline-copy"><strong>' +
                                (item.label || '-') + '</strong><span>' + (item.meta || 'Sistema') + '</span><small>' +
                                (item.date || '-') + '</small></div></div>';
                        }).join('')
                        : '<p class="timeline-empty">No hay movimientos registrados.</p>';
                }

                if (payload.receipt_url) {
                    receiptCard.style.display = '';
                    receiptWrap.innerHTML = '<a href="' + payload.receipt_url + '" target="_blank" rel="noopener">Ver comprobante</a>';
                } else {
                    receiptCard.style.display = 'none';
                    receiptWrap.innerHTML = '-';
                }

                if (payload.deadline) {
                    deadlineCard.style.display = '';
                    if (deadlineTimer) {
                        clearInterval(deadlineTimer);
                        deadlineTimer = null;
                    }

                    var end = new Date(payload.deadline);
                    function updateDeadline() {
                        var diff = Math.floor((end - new Date()) / 60000);
                        if (diff > 0) {
                            deadlineValue.textContent = diff + ' minutos restantes';
                            deadlineValue.style.color = 'orange';
                        } else {
                            deadlineValue.textContent = 'Vencido';
                            deadlineValue.style.color = 'red';
                            if (deadlineTimer) {
                                clearInterval(deadlineTimer);
                                deadlineTimer = null;
                            }
                        }
                    }
                    updateDeadline();
                    deadlineTimer = setInterval(updateDeadline, 60000);
                } else {
                    deadlineCard.style.display = 'none';
                    deadlineValue.textContent = '-';
                    deadlineValue.style.color = '';
                }

                assignForm.style.display = payload.can_assign ? 'inline-flex' : 'none';
                confirmForm.style.display = payload.can_confirm ? 'inline-flex' : 'none';
                rejectForm.style.display = payload.can_reject ? 'inline-flex' : 'none';
                completeForm.style.display = payload.can_complete ? 'inline-flex' : 'none';
                noShowForm.style.display = payload.can_no_show ? 'inline-flex' : 'none';

                assignForm.setAttribute('action', payload.assign_action || '');
                confirmForm.setAttribute('action', payload.confirm_action || '');
                rejectForm.setAttribute('action', payload.reject_action || '');
                completeForm.setAttribute('action', payload.complete_action || '');
                noShowForm.setAttribute('action', payload.no_show_action || '');
                cancelForm.setAttribute('action', payload.cancel_action || '');
                rescheduleForm.setAttribute('action', payload.reschedule_action || '');

                document.getElementById('agendaModalAnticipoMonto').value = payload.deposit_amount || '0.00';
                document.getElementById('agendaModalPagoFinal').value = payload.remaining_amount || '0.00';
                openCancelBtn.style.display = payload.cancel_action ? 'inline-flex' : 'none';
                openRescheduleBtn.style.display = payload.can_reschedule ? 'inline-flex' : 'none';
                ticketBtn.style.display = payload.ticket_url ? 'inline-flex' : 'none';
                ticketBtn.setAttribute('href', payload.ticket_url || '#');

                currentServiceId = payload.service_id || '';
                if (rescheduleDate) {
                    var today = new Date().toISOString().split('T')[0];
                    rescheduleDate.min = today;
                    rescheduleDate.value = payload.date || today;
                }
                if (rescheduleTime) {
                    rescheduleTime.innerHTML = '<option value="">Selecciona un horario</option>';
                }

                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                if (deadlineTimer) {
                    clearInterval(deadlineTimer);
                    deadlineTimer = null;
                }
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            function closeCancelModal() {
                cancelModal.classList.remove('active');
                cancelModal.setAttribute('aria-hidden', 'true');
            }

            function closeRescheduleModal() {
                rescheduleModal.classList.remove('active');
                rescheduleModal.setAttribute('aria-hidden', 'true');
            }

            function formatHora12(hora24) {
                if (!hora24) return '';
                var partes = hora24.split(':');
                var h = parseInt(partes[0], 10);
                var m = partes[1] || '00';
                var ampm = h >= 12 ? 'PM' : 'AM';
                var h12 = ((h + 11) % 12) + 1;
                return h12 + ':' + m + ' ' + ampm;
            }

            function cargarBloquesReagenda() {
                if (!currentServiceId || !rescheduleDate.value) return;

                rescheduleTime.innerHTML = '<option value="">Cargando...</option>';
                fetch('/citas/bloques?servicio_id=' + encodeURIComponent(currentServiceId) + '&fecha=' + encodeURIComponent(rescheduleDate.value))
                    .then(function(res) { return res.json(); })
                    .then(function(bloques) {
                        rescheduleTime.innerHTML = '';

                        if (!Array.isArray(bloques) || bloques.length === 0) {
                            rescheduleTime.innerHTML = '<option value="">No hay horarios disponibles</option>';
                            return;
                        }

                        var opt0 = document.createElement('option');
                        opt0.value = '';
                        opt0.textContent = 'Selecciona un horario';
                        rescheduleTime.appendChild(opt0);

                        bloques.forEach(function(b) {
                            var opt = document.createElement('option');
                            opt.value = b.inicio;
                            opt.textContent = formatHora12(b.inicio) + ' - ' + formatHora12(b.fin);
                            rescheduleTime.appendChild(opt);
                        });
                    })
                    .catch(function() {
                        rescheduleTime.innerHTML = '<option value="">Error al cargar horarios</option>';
                    });
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
            openCancelBtn.addEventListener('click', function() {
                cancelModal.classList.add('active');
                cancelModal.setAttribute('aria-hidden', 'false');
            });
            openRescheduleBtn.addEventListener('click', function() {
                closeModal();
                rescheduleModal.classList.add('active');
                rescheduleModal.setAttribute('aria-hidden', 'false');
                cargarBloquesReagenda();
            });
            cancelClose.addEventListener('click', closeCancelModal);
            cancelModal.addEventListener('click', function(e) {
                if (e.target === cancelModal) closeCancelModal();
            });
            rescheduleClose.addEventListener('click', closeRescheduleModal);
            rescheduleModal.addEventListener('click', function(e) {
                if (e.target === rescheduleModal) closeRescheduleModal();
            });
            rescheduleDate.addEventListener('change', cargarBloquesReagenda);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                    closeCancelModal();
                    closeRescheduleModal();
                }
            });
        })();
    </script>
@endsection

