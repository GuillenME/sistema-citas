@extends('layouts.admin')

@section('title', 'Gestion de Citas')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/citas-index.css') }}">
@endsection

@section('content')
    <div class="citas-shell">
        <header class="citas-topbar">
            <div class="citas-topbar-left">
                <div class="citas-control-wrap is-search">
                    <span class="citas-control-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>
                    </span>
                    <input type="text" id="citasSearch" class="citas-search" placeholder="Buscar cliente o servicio...">
                </div>
                <div class="citas-control-wrap is-filter">
                    <span class="citas-control-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 20c1.6-3.3 4.2-5 8-5s6.4 1.7 8 5"></path>
                        </svg>
                    </span>
                    <select id="citasEmployeeFilter" class="citas-filter">
                        <option value="">Todos los empleados</option>
                        <option value="__unassigned__">Sin asignar</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="citas-topbar-right">
                <a href="{{ route('admin.citas.create') }}" class="citas-btn ghost">Nueva cita</a>
                <a href="{{ route('admin.citas.index') }}" class="citas-btn primary">Actualizar página</a>
            </div>
        </header>

        <section class="citas-stats">
            <article class="stat-card">
                <p>Citas de Hoy</p>
                <strong>{{ $stats['hoy'] ?? 0 }}</strong>
            </article>
            <article class="stat-card">
                <p>Pendientes</p>
                <strong>{{ $stats['pendientes'] ?? 0 }}</strong>
            </article>
            <article class="stat-card">
                <p>Canceladas</p>
                <strong>{{ $stats['canceladas'] ?? 0 }}</strong>
            </article>
        </section>

        <section class="citas-panel">
            <div class="citas-panel-head">
                <div>
                    <h3>Listado de Proximas Citas</h3>
                    <p>Actualizado al momento</p>
                </div>
            </div>

            <div class="table-container citas-table-wrap">
                <table class="admin-table admin-table-fixed citas-table">
                    <colgroup>
                        <col class="col-cliente">
                        <col class="col-servicio">
                        <col class="col-fecha">
                        <col class="col-estado">
                        <col class="col-acciones">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="col-cliente">Cliente</th>
                            <th class="col-servicio">Servicio</th>
                            <th class="col-fecha">Fecha y Hora</th>
                            <th class="col-estado">Estado</th>
                            <th>Pago</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($citas as $cita)
                            @php
                                $isToday = \Carbon\Carbon::parse($cita->date)->isToday();
                                $isTomorrow = \Carbon\Carbon::parse($cita->date)->isTomorrow();
                                $inicioCita = \Carbon\Carbon::parse(
                                    \Carbon\Carbon::parse($cita->date)->format('Y-m-d') .
                                        ' ' .
                                        $cita->getRawOriginal('start_time'),
                                );
                                $finCita = \Carbon\Carbon::parse(
                                    \Carbon\Carbon::parse($cita->date)->format('Y-m-d') .
                                        ' ' .
                                        $cita->getRawOriginal('end_time'),
                                );
                                $searchText = strtolower(
                                    trim(
                                        ($cita->client->user->name ?? '') .
                                            ' ' .
                                            ($cita->service->name ?? '') .
                                            ' ' .
                                            ($cita->employee?->name ?? '') .
                                            ' ' .
                                            ($cita->status ?? ''),
                                    ),
                                );

                                $statusClass = match ($cita->status) {
                                    'confirmada' => 'status-confirmada',
                                    'cancelada' => 'status-cancelada',
                                    'completada' => 'status-completada',
                                    'no_asistio' => 'status-no-asistio',
                                    'pendiente_anticipo' => 'status-pendiente',
                                    default => 'status-cancelada',
                                };
                            @endphp
                            <tr data-search="{{ $searchText }}" data-employee-id="{{ $cita->employee_id ?? '' }}">
                                <td class="col-cliente">
                                    <div class="cell-main">{{ $cita->client->user->name }}</div>
                                    <small class="cell-sub">{{ $cita->client->user->email ?? '-' }}</small>
                                </td>

                                <td class="col-servicio">
                                    <div class="cell-main">{{ $cita->service->name }}</div>
                                    @if ($cita->employee)
                                        <small class="cell-sub">
                                            <span class="employee-chip is-assigned">Empleado:
                                                {{ $cita->employee->name }}</span>
                                        </small>
                                    @else
                                        <small class="cell-sub">
                                            <span class="employee-chip is-unassigned">Sin empleado asignado</span>
                                        </small>
                                    @endif
                                </td>

                                <td class="col-fecha">
                                    <div class="cell-main">
                                        {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}
                                        {{ \Carbon\Carbon::parse($cita->start_time)->format('h:i A') }}
                                    </div>
                                    <small class="cell-sub">
                                        @if ($isToday)
                                            Hoy
                                        @elseif ($isTomorrow)
                                            Mañana
                                        @else
                                            Programada
                                        @endif
                                    </small>
                                </td>

                                <td class="col-estado">
                                    <span
                                        class="status-pill {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $cita->status)) }}</span>
                                </td>

                                <td>
                                    @php
                                        $precio = $cita->service_price ?? $cita->service->price;
                                        $anticipo = $cita->deposit_amount ?? 0;
                                        $restante = $precio - $anticipo;
                                    @endphp

                                    <div class="cell-main">
                                        ${{ number_format($anticipo, 2) }} pagado
                                    </div>

                                    @if ($restante > 0 && $cita->status !== 'completada')
                                        <small class="cell-sub text-warning">
                                            Restante: ${{ number_format($restante, 2) }}
                                        </small>
                                    @else
                                        <small class="cell-sub text-success">
                                            Pagado
                                        </small>
                                    @endif
                                </td>

                                <td class="table-actions col-acciones">
                                    <button type="button" class="btn notes-btn citas-detail-btn"
                                        title="Ver empleado, comprobante y acciones de la cita"
                                        data-origin="{{ $cita->receipt ? 'cliente' : 'recepcion' }}"
                                        data-price="{{ $cita->service_price ?? $cita->service->price }}"
                                        data-deposit="{{ $cita->deposit_amount ?? 0 }}"
                                        data-remaining="{{ ($cita->service_price ?? $cita->service->price) - ($cita->deposit_amount ?? 0) }}"
                                        data-deadline="{{ $cita->payment_deadline ? $cita->payment_deadline->toIso8601String() : '' }}"
                                        data-notes="{{ e($cita->notes ?? '') }}"
                                        data-employee="{{ e($cita->employee?->name ?? '- Sin asignar -') }}"
                                        data-receipt="{{ $cita->receipt ? asset('storage/' . $cita->receipt) : '' }}"
                                        data-assign-action="{{ route('admin.citas.asignarEmpleado', $cita) }}"
                                        data-can-assign="{{ $cita->status === 'confirmada' && !$cita->employee_id ? '1' : '0' }}"
                                        data-can-confirm="{{ $cita->receipt && $cita->status === 'pendiente_anticipo' ? '1' : '0' }}"
                                        data-can-reject="{{ $cita->receipt && $cita->status === 'pendiente_anticipo' ? '1' : '0' }}"
                                        data-can-reschedule="{{ $cita->status === 'confirmada' && ($cita->reagendas_count ?? 0) < 2 ? '1' : '0' }}"
                                        data-can-complete="{{ $cita->status === 'confirmada' && now()->greaterThanOrEqualTo($finCita) ? '1' : '0' }}"
                                        data-can-no-show="{{ $cita->status === 'confirmada' && now()->greaterThanOrEqualTo($inicioCita) ? '1' : '0' }}"
                                        data-confirm-action="{{ route('admin.citas.confirmar', $cita) }}"
                                        data-reject-action="{{ route('admin.citas.rechazar', $cita) }}"
                                        data-cancel-action="{{ route('admin.citas.cancelar', $cita) }}"
                                        data-reschedule-action="{{ route('admin.citas.reagendar', $cita) }}"
                                        data-complete-action="{{ route('admin.citas.completar', $cita) }}"
                                        data-no-show-action="{{ route('admin.citas.noAsistio', $cita) }}"
                                        data-service-id="{{ $cita->service_id }}"
                                        data-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d') }}">
                                        Detalles
                                    </button>
                                    <small class="details-hint">Empleado, comprobante y acciones</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="table-empty">No hay citas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Mostrando {{ $citas->count() }} de {{ $citas->total() }} citas
                </div>
                {{ $citas->links('pagination::simple-bootstrap-4') }}
            </div>
        </section>
    </div>

    <div id="notesModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <h3>Detalle de la cita</h3>
            <p><strong>Origen:</strong> <span id="notesModalOrigin">-</span></p>
            <p><strong>Empleado:</strong> <span id="notesModalEmployee">-</span></p>
            <p><strong>Comprobante:</strong> <span id="notesModalReceipt">-</span></p>
            <p><strong>Precio servicio:</strong> $<span id="modalPrice">0.00</span></p>
            <p><strong>Anticipo:</strong> $<span id="modalDeposit">0.00</span></p>
            <p><strong>Restante:</strong> $<span id="modalRemaining">0.00</span></p>
            <p><strong>Tiempo restante:</strong> <span id="notesModalDeadline">-</span></p>
            <p><strong>Comentario:</strong> <span id="notesModalText">-</span></p>
            <form method="POST" id="assignForm" class="assign-inline">
                @csrf
                <select name="empleado_id" id="assignEmployeeSelect" class="select-compact">
                    <option value="">Seleccionar empleado</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-save btn-compact">Asignar</button>
            </form>
            <form method="POST" id="confirmForm" class="assign-inline">
                @csrf
                <button type="submit" class="btn btn-save btn-compact">Confirmar</button>
                <button type="button" class="btn btn-cancel btn-compact" id="openCancelFromModal">Cancelar</button>
            </form>
            <form method="POST" id="rejectPaymentForm" class="assign-inline" style="display:none;">
                @csrf
                <button type="submit" class="btn btn-cancel btn-compact">
                    Rechazar anticipo
                </button>
            </form>
            <form method="POST" id="completeForm" class="assign-inline">
                @csrf

                @php
                    $precio = $cita->service_price ?? $cita->service->price;
                    $anticipo = $cita->deposit_amount ?? 0;
                    $restante = $precio - $anticipo;
                @endphp

                @if ($restante > 0)
                    <label>Pago restante</label>
                    <input type="number" name="pago_final" step="0.01" min="0" value="{{ $restante }}"
                        required>
                @else
                    <input type="hidden" name="pago_final" value="0">
                @endif

                <button type="submit" class="btn btn-save btn-compact">
                    Marcar completada
                </button>
            </form>
            <form method="POST" id="noShowForm" class="assign-inline">
                @csrf
                <button type="submit" class="btn btn-cancel btn-compact">Marcar no asistió</button>
            </form>
            <div class="modal-actions">
                <button type="button" class="btn btn-save btn-compact" id="openRescheduleFromModal">Reagendar</button>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" id="notesModalClose">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="cancelModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <h3>Cancelar cita</h3>
            <form method="POST" id="cancelForm">
                @csrf
                <label for="cancelNotes">Observaciones (opcional)</label>
                <textarea id="cancelNotes" name="observaciones" rows="4" placeholder="Motivo de cancelacion..."></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="cancelModalClose">Cerrar</button>
                    <button type="submit" class="btn btn-save">Confirmar cancelacion</button>
                </div>
            </form>
        </div>
    </div>

    <div id="rescheduleModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <h3>Reagendar cita</h3>
            <form method="POST" id="rescheduleForm">
                @csrf
                <label for="rescheduleDate">Nueva fecha</label>
                <input type="date" id="rescheduleDate" name="fecha" required>
                <label for="rescheduleTime">Nuevo horario</label>
                <select id="rescheduleTime" name="hora_inicio" required>
                    <option value="">Selecciona un horario</option>
                </select>
                <label for="rescheduleNotes">Observaciones (opcional)</label>
                <textarea id="rescheduleNotes" name="observaciones" rows="3" placeholder="Motivo o comentario del cambio..."></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="rescheduleModalClose">Cerrar</button>
                    <button type="submit" class="btn btn-save">Confirmar reagenda</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            var searchInput = document.getElementById('citasSearch');
            var employeeFilter = document.getElementById('citasEmployeeFilter');
            var rows = Array.prototype.slice.call(document.querySelectorAll('tbody tr[data-search]'));

            function applyFilters() {
                var q = searchInput ? searchInput.value.toLowerCase().trim() : '';
                var selectedEmployee = employeeFilter ? employeeFilter.value : '';

                rows.forEach(function(row) {
                    var haystack = row.getAttribute('data-search') || '';
                    var employeeId = row.getAttribute('data-employee-id') || '';
                    var matchesSearch = haystack.includes(q);
                    var matchesEmployee = true;

                    if (selectedEmployee === '__unassigned__') {
                        matchesEmployee = employeeId === '';
                    } else if (selectedEmployee !== '') {
                        matchesEmployee = employeeId === selectedEmployee;
                    }

                    row.style.display = (matchesSearch && matchesEmployee) ? '' : 'none';
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }
            if (employeeFilter) {
                employeeFilter.addEventListener('change', applyFilters);
            }

            var modal = document.getElementById('notesModal');
            var modalText = document.getElementById('notesModalText');
            var modalEmployee = document.getElementById('notesModalEmployee');
            var modalReceipt = document.getElementById('notesModalReceipt');
            var assignForm = document.getElementById('assignForm');
            var assignSelect = document.getElementById('assignEmployeeSelect');
            var confirmForm = document.getElementById('confirmForm');
            var completeForm = document.getElementById('completeForm');
            var noShowForm = document.getElementById('noShowForm');
            var openCancelFromModal = document.getElementById('openCancelFromModal');
            var openRescheduleFromModal = document.getElementById('openRescheduleFromModal');
            var rescheduleDate = document.getElementById('rescheduleDate');
            var rescheduleTime = document.getElementById('rescheduleTime');
            var closeBtn = document.getElementById('notesModalClose');
            var rescheduleServiceId = '';
            var rescheduleOriginalDate = '';
            var deadlineTimer = null;


            function formatHora12(hora24) {
                if (!hora24) return '';
                var partes = hora24.split(':');
                var h = parseInt(partes[0], 10);
                var m = partes[1] || '00';
                var ampm = h >= 12 ? 'PM' : 'AM';
                var h12 = ((h + 11) % 12) + 1;
                return h12 + ':' + m + ' ' + ampm;
            }

            async function cargarBloquesReagenda() {
                if (!rescheduleServiceId || !rescheduleDate || !rescheduleDate.value || !rescheduleTime) {
                    return;
                }

                rescheduleTime.innerHTML = '<option value="">Cargando...</option>';

                try {
                    var res = await fetch('/citas/bloques?servicio_id=' + encodeURIComponent(rescheduleServiceId) +
                        '&fecha=' + encodeURIComponent(rescheduleDate.value));
                    var bloques = await res.json();

                    rescheduleTime.innerHTML = '';

                    if (!Array.isArray(bloques) || bloques.length === 0) {
                        rescheduleTime.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        return;
                    }

                    var defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Selecciona un horario';
                    rescheduleTime.appendChild(defaultOption);

                    bloques.forEach(function(b) {
                        var opt = document.createElement('option');
                        opt.value = b.inicio;
                        opt.textContent = formatHora12(b.inicio) + ' - ' + formatHora12(b.fin);
                        rescheduleTime.appendChild(opt);
                    });
                } catch (e) {
                    rescheduleTime.innerHTML = '<option value="">Error al cargar horarios</option>';
                }
            }

            if (!modal || !modalText || !closeBtn) return;

            document.querySelectorAll('.notes-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var rejectPaymentForm = document.getElementById('rejectPaymentForm');
                    var rejectAction = btn.getAttribute('data-reject-action') || '';
                    var canReject = btn.getAttribute('data-can-reject') === '1';
                    var text = btn.getAttribute('data-notes') || '-';
                    var employee = btn.getAttribute('data-employee') || '-';
                    var origin = btn.getAttribute('data-origin') || 'recepcion';
                    var modalOrigin = document.getElementById('notesModalOrigin');
                    var receipt = btn.getAttribute('data-receipt') || '';
                    var assignAction = btn.getAttribute('data-assign-action') || '';
                    var canAssign = btn.getAttribute('data-can-assign') === '1';
                    var canConfirm = btn.getAttribute('data-can-confirm') === '1';
                    var canReschedule = btn.getAttribute('data-can-reschedule') === '1';
                    var canComplete = btn.getAttribute('data-can-complete') === '1';
                    var canNoShow = btn.getAttribute('data-can-no-show') === '1';
                    var confirmAction = btn.getAttribute('data-confirm-action') || '';
                    var cancelAction = btn.getAttribute('data-cancel-action') || '';
                    var rescheduleAction = btn.getAttribute('data-reschedule-action') || '';
                    var completeAction = btn.getAttribute('data-complete-action') || '';
                    var noShowAction = btn.getAttribute('data-no-show-action') || '';
                    var serviceId = btn.getAttribute('data-service-id') || '';
                    var currentDate = btn.getAttribute('data-date') || '';
                    var deadline = btn.getAttribute('data-deadline');
                    var deadlineSpan = document.getElementById('notesModalDeadline');
                    var deadlineRow = deadlineSpan.closest('p');
                    var price = btn.getAttribute('data-price') || 0;
                    var deposit = btn.getAttribute('data-deposit') || 0;
                    var remaining = btn.getAttribute('data-remaining') || 0;

                    document.getElementById('modalPrice').textContent = parseFloat(price).toFixed(2);
                    document.getElementById('modalDeposit').textContent = parseFloat(deposit).toFixed(
                        2);
                    document.getElementById('modalRemaining').textContent = parseFloat(remaining)
                        .toFixed(2);
                    var pagoInput = document.querySelector('#completeForm input[name="pago_final"]');
                    if (pagoInput) {
                        pagoInput.value = parseFloat(remaining).toFixed(2);
                    }

                    if (deadline) {
                        deadlineRow.style.display = '';
                        if (deadlineTimer) {
                            clearInterval(deadlineTimer);
                            deadlineTimer = null;
                        } else {
                            deadlineRow.style.display = 'none';
                        }

                        var end = new Date(deadline);

                        function actualizarTiempo() {
                            var nowDate = new Date();
                            var diff = Math.floor((end - nowDate) / 60000);

                            if (diff > 0) {
                                deadlineSpan.innerHTML = diff + " minutos restantes";
                                deadlineSpan.style.color = "orange";
                            } else {
                                deadlineSpan.innerHTML = "Vencido";
                                deadlineSpan.style.color = "red";
                                if (deadlineTimer) {
                                    clearInterval(deadlineTimer);
                                    deadlineTimer = null;
                                }
                            }
                        }

                        actualizarTiempo(); // ejecutar inmediatamente
                        deadlineTimer = setInterval(actualizarTiempo, 60000);

                    } else {
                        deadlineSpan.innerHTML = "-";
                        if (deadlineTimer) {
                            clearInterval(deadlineTimer);
                            deadlineTimer = null;
                        }
                    }

                    modalText.textContent = text;
                    modalEmployee.textContent = employee;
                    modalOrigin.textContent = origin === 'cliente' ?
                        'Cliente (anticipo por transferencia)' :
                        'Recepción / Administrador';
                    if (rejectPaymentForm) {
                        rejectPaymentForm.style.display = canReject ? 'inline-flex' : 'none';
                        rejectPaymentForm.setAttribute('action', rejectAction);
                    }
                    var receiptRow = modalReceipt.closest('p');

                    if (receipt) {
                        receiptRow.style.display = '';
                        modalReceipt.innerHTML = '<a href="' + receipt +
                            '" target="_blank" rel="noopener">Ver comprobante</a>';
                    } else {
                        receiptRow.style.display = 'none';
                    }

                    if (assignForm && assignSelect) {
                        assignForm.style.display = canAssign ? 'inline-flex' : 'none';
                        assignForm.setAttribute('action', assignAction);
                        assignSelect.value = '';
                    }
                    if (confirmForm) {
                        confirmForm.style.display = canConfirm ? 'inline-flex' : 'none';
                        confirmForm.setAttribute('action', confirmAction);
                    }
                    if (completeForm) {
                        completeForm.style.display = canComplete ? 'inline-flex' : 'none';
                        completeForm.setAttribute('action', completeAction);
                    }
                    if (noShowForm) {
                        noShowForm.style.display = canNoShow ? 'inline-flex' : 'none';
                        noShowForm.setAttribute('action', noShowAction);
                    }
                    if (openCancelFromModal) {
                        openCancelFromModal.setAttribute('data-cancel-action', cancelAction);
                    }
                    if (openRescheduleFromModal) {
                        openRescheduleFromModal.style.display = canReschedule ? 'inline-flex' : 'none';
                        openRescheduleFromModal.setAttribute('data-reschedule-action',
                            rescheduleAction);
                        openRescheduleFromModal.setAttribute('data-service-id', serviceId);
                        openRescheduleFromModal.setAttribute('data-date', currentDate);
                    }
                    modal.classList.add('active');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            function closeModal() {
                if (deadlineTimer) {
                    clearInterval(deadlineTimer);
                    deadlineTimer = null;
                }
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            var cancelModal = document.getElementById('cancelModal');
            var cancelForm = document.getElementById('cancelForm');
            var cancelClose = document.getElementById('cancelModalClose');
            if (cancelModal && cancelForm && cancelClose) {
                function openCancel(action) {
                    cancelForm.setAttribute('action', action);
                    cancelModal.classList.add('active');
                    cancelModal.setAttribute('aria-hidden', 'false');
                }

                if (openCancelFromModal) {
                    openCancelFromModal.addEventListener('click', function() {
                        var action = openCancelFromModal.getAttribute('data-cancel-action');
                        if (action) {
                            closeModal();
                            openCancel(action);
                        }
                    });
                }

                function closeCancel() {
                    cancelModal.classList.remove('active');
                    cancelModal.setAttribute('aria-hidden', 'true');
                }

                cancelClose.addEventListener('click', closeCancel);
                cancelModal.addEventListener('click', function(e) {
                    if (e.target === cancelModal) closeCancel();
                });
            }

            var rescheduleModal = document.getElementById('rescheduleModal');
            var rescheduleForm = document.getElementById('rescheduleForm');
            var rescheduleClose = document.getElementById('rescheduleModalClose');
            if (rescheduleModal && rescheduleForm && rescheduleClose) {
                function openReschedule(action) {
                    rescheduleForm.setAttribute('action', action);
                    rescheduleModal.classList.add('active');
                    rescheduleModal.setAttribute('aria-hidden', 'false');
                }

                if (openRescheduleFromModal) {
                    openRescheduleFromModal.addEventListener('click', function() {
                        var action = openRescheduleFromModal.getAttribute('data-reschedule-action');
                        rescheduleServiceId = openRescheduleFromModal.getAttribute('data-service-id') || '';
                        rescheduleOriginalDate = openRescheduleFromModal.getAttribute('data-date') || '';

                        if (rescheduleDate) {
                            var today = new Date().toISOString().split('T')[0];
                            rescheduleDate.min = today;
                            rescheduleDate.value = rescheduleOriginalDate || today;
                        }
                        if (rescheduleTime) {
                            rescheduleTime.innerHTML = '<option value="">Selecciona un horario</option>';
                        }

                        if (action) {
                            closeModal();
                            openReschedule(action);
                            cargarBloquesReagenda();
                        }
                    });
                }

                function closeReschedule() {
                    rescheduleModal.classList.remove('active');
                    rescheduleModal.setAttribute('aria-hidden', 'true');
                }

                rescheduleClose.addEventListener('click', closeReschedule);
                rescheduleModal.addEventListener('click', function(e) {
                    if (e.target === rescheduleModal) closeReschedule();
                });

                if (rescheduleDate) {
                    rescheduleDate.addEventListener('change', function() {
                        if (rescheduleDate.value) {
                            cargarBloquesReagenda();
                        }
                    });
                }
            }
        })();
    </script>
@endsection
