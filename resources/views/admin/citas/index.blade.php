@extends('layouts.admin')

@section('title', 'Gestión de citas')
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
                <a href="{{ route('admin.citas.agenda') }}" class="citas-btn ghost">Agenda visual</a>
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
                    <h3>Listado de próximas citas</h3>
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
                                $timelineItems = collect([
                                    [
                                        'label' => 'Cita creada',
                                        'meta' => 'Sistema',
                                        'date' => optional($cita->created_at)->format('d/m/Y H:i'),
                                    ],
                                ])
                                    ->merge(
                                        $cita->estados->sortBy('change_date')->map(function ($estado) {
                                            $label = match ($estado->status) {
                                                'confirmada' => 'Confirmada',
                                                'cancelada' => 'Cancelada',
                                                'completada' => 'Completada',
                                                'no_asistio' => 'No asistio',
                                                'reagendada' => 'Reagendada',
                                                'pendiente_anticipo' => 'Pendiente de anticipo',
                                                'anticipo_rechazado' => 'Anticipo rechazado',
                                                default => ucfirst(str_replace('_', ' ', (string) $estado->status)),
                                            };

                                            return [
                                                'label' => $label,
                                                'meta' =>
                                                    trim(
                                                        (string) (($estado->user?->name ?? '') .
                                                            ' ' .
                                                            ($estado->user?->last_name ?? '')),
                                                    ) ?:
                                                    'Sistema',
                                                'date' => \Carbon\Carbon::parse($estado->change_date)->format(
                                                    'd/m/Y H:i',
                                                ),
                                            ];
                                        }),
                                    )
                                    ->values();
                                $clientAppointments = $cita->client?->appointments ?? collect();
                                $favoriteServices = $clientAppointments
                                    ->filter(fn($appointment) => $appointment->service?->name)
                                    ->groupBy(fn($appointment) => $appointment->service->name)
                                    ->map(
                                        fn($group, $serviceName) => [
                                            'service' => $serviceName,
                                            'count' => $group->count(),
                                        ],
                                    )
                                    ->sortByDesc('count')
                                    ->take(3)
                                    ->values();
                                $nextAppointment = $clientAppointments
                                    ->filter(function ($appointment) {
                                        $date = optional($appointment->date)?->format('Y-m-d');
                                        return $date && $date >= today()->toDateString();
                                    })
                                    ->sortBy(
                                        fn($appointment) => optional($appointment->date)?->format('Y-m-d') .
                                            ' ' .
                                            $appointment->getRawOriginal('start_time'),
                                    )
                                    ->first();
                                $lastAppointment = $clientAppointments
                                    ->sortByDesc(
                                        fn($appointment) => optional($appointment->date)?->format('Y-m-d') .
                                            ' ' .
                                            $appointment->getRawOriginal('start_time'),
                                    )
                                    ->first();
                                $completedAppointments = $clientAppointments->where('status', 'completada');
                                $completedCount = $completedAppointments->count();
                                $cancelledCount = $clientAppointments->where('status', 'cancelada')->count();
                                $noShowCount = $clientAppointments->where('status', 'no_asistio')->count();
                                $totalAppointments = $clientAppointments->count();
                                $totalSpent = $completedAppointments->sum(
                                    fn($appointment) => $appointment->precioRegistrado(),
                                );
                                $avgTicket = $completedCount > 0 ? $totalSpent / $completedCount : 0;
                                $upcomingCount = $clientAppointments
                                    ->filter(
                                        fn($appointment) => optional($appointment->date)?->format('Y-m-d') >=
                                            today()->toDateString(),
                                    )
                                    ->whereNotIn('status', ['cancelada', 'no_asistio'])
                                    ->count();
                                $lastCompletedAppointment = $completedAppointments
                                    ->sortByDesc(
                                        fn($appointment) => optional($appointment->date)?->format('Y-m-d') .
                                            ' ' .
                                            $appointment->getRawOriginal('start_time'),
                                    )
                                    ->first();
                                $favoriteEmployee = $clientAppointments
                                    ->filter(fn($appointment) => $appointment->employee?->name)
                                    ->groupBy(fn($appointment) => $appointment->employee->name)
                                    ->map(
                                        fn($group, $employeeName) => [
                                            'employee' => $employeeName,
                                            'count' => $group->count(),
                                        ],
                                    )
                                    ->sortByDesc('count')
                                    ->first();
                                $completionRate =
                                    $totalAppointments > 0 ? round(($completedCount / $totalAppointments) * 100) : 0;
                                $incidentRate =
                                    $totalAppointments > 0
                                        ? round((($cancelledCount + $noShowCount) / $totalAppointments) * 100)
                                        : 0;
                                $clientProfile = [
                                    'name' =>
                                        trim(
                                            (string) (($cita->client?->user?->name ?? '') .
                                                ' ' .
                                                ($cita->client?->user?->last_name ?? '')),
                                        ) ?:
                                        'Cliente',
                                    'email' => (string) ($cita->client?->user?->email ?? 'No registrado'),
                                    'phone' => (string) ($cita->client?->user?->phone ?? 'No registrado'),
                                    'birth_date' => $cita->client?->birth_date
                                        ? $cita->client->birth_date->format('d/m/Y')
                                        : 'No registrada',
                                    'member_since' =>
                                        optional($cita->client?->created_at)->format('d/m/Y') ?: 'Sin registro',
                                    'total_appointments' => $totalAppointments,
                                    'completed' => $completedCount,
                                    'cancelled' => $cancelledCount,
                                    'no_show' => $noShowCount,
                                    'upcoming_count' => $upcomingCount,
                                    'completion_rate' => $completionRate . '%',
                                    'incident_rate' => $incidentRate . '%',
                                    'total_spent' => '$' . number_format($totalSpent, 2),
                                    'avg_ticket' => '$' . number_format($avgTicket, 2),
                                    'last_completed' => $lastCompletedAppointment
                                        ? $lastCompletedAppointment->date->format('d/m/Y') .
                                            ' · ' .
                                            \Carbon\Carbon::parse($lastCompletedAppointment->start_time)->format(
                                                'h:i A',
                                            )
                                        : 'Sin visitas completadas',
                                    'favorite_employee' => $favoriteEmployee
                                        ? $favoriteEmployee['employee'] . ' (' . $favoriteEmployee['count'] . ')'
                                        : 'Aun sin preferencia clara',
                                    'next_appointment' => $nextAppointment
                                        ? $nextAppointment->date->format('d/m/Y') .
                                            ' · ' .
                                            \Carbon\Carbon::parse($nextAppointment->start_time)->format('h:i A')
                                        : 'Sin citas proximas',
                                    'last_appointment' => $lastAppointment
                                        ? $lastAppointment->date->format('d/m/Y') .
                                            ' · ' .
                                            \Carbon\Carbon::parse($lastAppointment->start_time)->format('h:i A')
                                        : 'Sin historial',
                                    'favorite_services' => $favoriteServices,
                                ];
                            @endphp
                            <tr data-search="{{ $searchText }}" data-employee-id="{{ $cita->employee_id ?? '' }}"
                                data-cita-row="{{ $cita->id }}">
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
                                        $precio = $cita->precioRegistrado();
                                        $anticipo = $cita->anticipoRegistrado();
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
                                        data-cita-id="{{ $cita->id }}"
                                        title="Ver empleado, comprobante y acciones de la cita"
                                        data-origin="{{ $cita->receipt ? 'cliente' : 'recepcion' }}"
                                        data-price="{{ $precio }}" data-deposit="{{ $anticipo }}"
                                        data-remaining="{{ $restante }}"
                                        data-deadline="{{ $cita->payment_deadline ? $cita->payment_deadline->toIso8601String() : '' }}"
                                        data-notes="{{ e($cita->notes ?? '') }}"
                                        data-employee="{{ e($cita->employee?->name ?? '- Sin asignar -') }}"
                                        data-receipt="{{ $cita->receipt ? asset('storage/' . $cita->receipt) : '' }}"
                                        data-assign-action="{{ route('admin.citas.asignarEmpleado', $cita) }}"
                                        data-can-assign="{{ $cita->status === 'confirmada' && !$cita->employee_id ? '1' : '0' }}"
                                        data-can-confirm="{{ $cita->receipt && $cita->status === 'pendiente_anticipo' ? '1' : '0' }}"
                                        data-can-reject="{{ $cita->receipt && $cita->status === 'pendiente_anticipo' ? '1' : '0' }}"
                                        data-can-reschedule="{{ $cita->status === 'confirmada' && ($cita->reagendas_count ?? 0) < 2 && now()->lt($inicioCita) ? '1' : '0' }}"
                                        data-can-complete="{{ $cita->status === 'confirmada' && now()->greaterThanOrEqualTo($finCita) ? '1' : '0' }}"
                                        data-can-no-show="{{ $cita->status === 'confirmada' && now()->greaterThanOrEqualTo($inicioCita) ? '1' : '0' }}"
                                        data-can-cancel="{{ $cita->status !== 'cancelada' ? '1' : '0' }}"
                                        data-confirm-action="{{ route('admin.citas.confirmar', $cita) }}"
                                        data-edit-deposit-action="{{ route('admin.citas.actualizarAnticipo', $cita) }}"
                                        data-reject-action="{{ route('admin.citas.rechazar', $cita) }}"
                                        data-cancel-action="{{ route('admin.citas.cancelar', $cita) }}"
                                        data-reschedule-action="{{ route('admin.citas.reagendar', $cita) }}"
                                        data-complete-action="{{ route('admin.citas.completar', $cita) }}"
                                        data-no-show-action="{{ route('admin.citas.noAsistio', $cita) }}"
                                        data-can-edit-deposit="{{ $cita->status !== 'cancelada' && now()->lt($inicioCita) ? '1' : '0' }}"
                                        data-service-id="{{ $cita->service_id }}"
                                        data-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d') }}"
                                        data-timeline='@json($timelineItems)'
                                        data-client-profile='@json($clientProfile)'
                                        data-ticket-url="{{ route('admin.citas.ticket', $cita) }}">
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
        <div class="modal-box citas-detail-modal-box">
            <div class="citas-detail-modal-head">
                <h3>Detalle de la cita</h3>
                <button type="button" class="citas-detail-close-icon" id="notesModalDismiss"
                    aria-label="Cerrar">×</button>
            </div>

            <div class="citas-detail-summary-grid">
                <div class="citas-detail-summary-item">
                    <span>Origen</span>
                    <strong id="notesModalOrigin">-</strong>
                </div>
                <div class="citas-detail-summary-item">
                    <span>Empleado</span>
                    <strong id="notesModalEmployee">-</strong>
                </div>
                <div class="citas-detail-summary-item">
                    <span>Precio servicio</span>
                    <strong>$<span id="modalPrice">0.00</span></strong>
                </div>
                <div class="citas-detail-summary-item is-positive">
                    <span>Anticipo</span>
                    <strong>$<span id="modalDeposit">0.00</span></strong>
                </div>
                <div class="citas-detail-summary-item is-danger">
                    <span>Restante</span>
                    <strong>$<span id="modalRemaining">0.00</span></strong>
                </div>
                <div class="citas-detail-summary-item" id="deadlineCard">
                    <span>Tiempo restante</span>
                    <strong id="notesModalDeadline">-</strong>
                </div>
            </div>

            <div class="citas-detail-comment-box">
                <span>Comentario</span>
                <p id="notesModalText">No se han proporcionado comentarios adicionales para esta cita.</p>
            </div>

            <div class="citas-detail-extra citas-detail-timeline-box">
                <span>Historial</span>
                <div id="notesModalTimeline" class="citas-detail-timeline"></div>
            </div>

            <div class="citas-detail-extra" id="receiptCard">
                <span>Comprobante</span>
                <div id="notesModalReceipt">-</div>
            </div>

            <div class="citas-detail-actions-stack">
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

                    <label>Monto recibido</label>
                    <input type="number" name="anticipo_monto" id="modalAnticipoMonto" step="0.01" min="0"
                        required>

                    <button type="submit" class="btn btn-save btn-compact">
                        Confirmar anticipo
                    </button>
                </form>
                <form method="POST" id="editDepositForm" class="assign-inline" style="display:none;">
                    @csrf
                    <label>Editar anticipo</label>
                    <input type="number" name="anticipo_monto" id="modalEditarAnticipo" step="0.01" min="0"
                        required>
                    <button type="submit" class="btn btn-save btn-compact">
                        Guardar anticipo
                    </button>
                </form>
                <button type="button" class="btn btn-cancel btn-compact" id="openCancelFromModal">
                    Cancelar
                </button>
                <form method="POST" id="rejectPaymentForm" class="assign-inline" style="display:none;">
                    @csrf
                    <button type="submit" class="btn btn-cancel btn-compact">
                        Rechazar anticipo
                    </button>
                </form>
                <form method="POST" id="completeForm" class="assign-inline">
                    @csrf

                    <label>Pago restante</label>

                    <!-- Visible pero NO editable -->
                    <input type="number" id="modalPagoFinal" step="0.01" readonly>

                    <!-- Valor real que se envía -->
                    <input type="hidden" name="pago_final" id="modalPagoFinalHidden">

                    <button type="submit" class="btn btn-save btn-compact">
                        Marcar completada
                    </button>
                </form>
                <form method="POST" id="noShowForm" class="assign-inline">
                    @csrf
                    <button type="submit" class="btn btn-cancel btn-compact">Marcar no asistió</button>
                </form>
            </div>
            <div class="citas-detail-footer">
                <button type="button" class="btn btn-cancel" id="notesModalClose">Cerrar</button>
                <button type="button" class="btn btn-cancel btn-compact" id="openClientProfileFromModal">Ver
                    cliente</button>
                <a href="#" target="_blank" rel="noopener" class="btn btn-cancel btn-compact"
                    id="printTicketFromModal">Imprimir ticket</a>
                <button type="button" class="btn btn-save btn-compact" id="openRescheduleFromModal">Reagendar</button>
            </div>
        </div>
    </div>

    <div id="clientProfileModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box client-profile-modal-box">
            <div class="citas-detail-modal-head">
                <div>
                    <span class="client-profile-eyebrow">Vista general</span>
                    <h3>Ficha del cliente</h3>
                    <p class="client-profile-head-note">Resumen rapido del comportamiento e historial del cliente.</p>
                </div>
                <button type="button" class="citas-detail-close-icon" id="clientProfileDismiss"
                    aria-label="Cerrar">×</button>
            </div>

            <div class="client-profile-content">
                <div class="client-profile-hero">
                    <div class="client-profile-avatar" id="clientProfileInitials">CL</div>
                    <div class="client-profile-hero-copy">
                        <span class="client-profile-kicker">Cliente registrado</span>
                        <h4 id="clientProfileName">Cliente</h4>
                        <p id="clientProfileEmail">correo@ejemplo.com</p>
                    </div>
                    <div class="client-profile-highlight">
                        <span>Actividad</span>
                        <strong id="clientProfileTotalHero">0 citas</strong>
                        <small>Historial acumulado en el sistema</small>
                    </div>
                </div>

                <div class="client-profile-grid">
                    <div class="client-profile-card">
                        <span>Teléfono</span>
                        <strong id="clientProfilePhone">-</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Fecha de nacimiento</span>
                        <strong id="clientProfileBirthDate">-</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Cliente desde</span>
                        <strong id="clientProfileMemberSince">-</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Total de citas</span>
                        <strong id="clientProfileTotal">0</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Completadas</span>
                        <strong id="clientProfileCompleted">0</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Canceladas</span>
                        <strong id="clientProfileCancelled">0</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>No asistio</span>
                        <strong id="clientProfileNoShow">0</strong>
                    </div>
                </div>

                <div class="client-profile-grid">
                    <div class="client-profile-card">
                        <span>Gasto total</span>
                        <strong id="clientProfileTotalSpent">$0.00</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Ticket promedio</span>
                        <strong id="clientProfileAvgTicket">$0.00</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Próximas citas</span>
                        <strong id="clientProfileUpcomingCount">0</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Tasa de completadas</span>
                        <strong id="clientProfileCompletionRate">0%</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Tasa de incidencias</span>
                        <strong id="clientProfileIncidentRate">0%</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Empleado frecuente</span>
                        <strong id="clientProfileFavoriteEmployee">-</strong>
                    </div>
                </div>

                <div class="client-profile-grid client-profile-grid-secondary">
                    <div class="client-profile-card">
                        <span>Proxima cita</span>
                        <strong id="clientProfileNext">Sin citas proximas</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Ultima cita</span>
                        <strong id="clientProfileLast">Sin historial</strong>
                    </div>
                </div>

                <div class="client-profile-grid client-profile-grid-secondary">
                    <div class="client-profile-card">
                        <span>Ultima visita completada</span>
                        <strong id="clientProfileLastCompleted">Sin visitas completadas</strong>
                    </div>
                    <div class="client-profile-card">
                        <span>Resumen de valor</span>
                        <strong id="clientProfileValueSummary">Sin datos suficientes</strong>
                    </div>
                </div>

                <div class="client-profile-favorites">
                    <span>Servicios frecuentes</span>
                    <div id="clientProfileFavorites" class="client-profile-favorite-list"></div>
                </div>
            </div>

            <div class="citas-detail-footer client-profile-footer">
                <button type="button" class="btn btn-cancel" id="clientProfileClose">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="cancelModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <h3>Cancelar cita</h3>
            <form method="POST" id="cancelForm">
                @csrf
                <label for="cancelNotes">Observaciones (opcional)</label>
                <textarea id="cancelNotes" name="observaciones" rows="4" placeholder="Motivo de cancelación..."></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="cancelModalClose">Cerrar</button>
                    <button type="submit" class="btn btn-save">Confirmar cancelación</button>
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

            function normalizeText(value) {
                return String(value || '')
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase()
                    .trim();
            }

            function applyFilters() {
                var q = searchInput ? normalizeText(searchInput.value) : '';
                var selectedEmployee = employeeFilter ? employeeFilter.value : '';

                rows.forEach(function(row) {
                    var haystack = normalizeText(row.getAttribute('data-search') || '');
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
            var editDepositForm = document.getElementById('editDepositForm');
            var completeForm = document.getElementById('completeForm');
            var noShowForm = document.getElementById('noShowForm');
            var openCancelFromModal = document.getElementById('openCancelFromModal');
            var openRescheduleFromModal = document.getElementById('openRescheduleFromModal');
            var rescheduleDate = document.getElementById('rescheduleDate');
            var rescheduleTime = document.getElementById('rescheduleTime');
            var closeBtn = document.getElementById('notesModalClose');
            var dismissBtn = document.getElementById('notesModalDismiss');
            var timelineContainer = document.getElementById('notesModalTimeline');
            var openClientProfileFromModal = document.getElementById('openClientProfileFromModal');
            var printTicketFromModal = document.getElementById('printTicketFromModal');
            var clientProfileModal = document.getElementById('clientProfileModal');
            var clientProfileClose = document.getElementById('clientProfileClose');
            var clientProfileDismiss = document.getElementById('clientProfileDismiss');
            var currentClientProfile = null;
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
                    var canEditDeposit = btn.getAttribute('data-can-edit-deposit') === '1';
                    var canReschedule = btn.getAttribute('data-can-reschedule') === '1';
                    var canComplete = btn.getAttribute('data-can-complete') === '1';
                    var canNoShow = btn.getAttribute('data-can-no-show') === '1';
                    var confirmAction = btn.getAttribute('data-confirm-action') || '';
                    var editDepositAction = btn.getAttribute('data-edit-deposit-action') || '';
                    var cancelAction = btn.getAttribute('data-cancel-action') || '';
                    var rescheduleAction = btn.getAttribute('data-reschedule-action') || '';
                    var completeAction = btn.getAttribute('data-complete-action') || '';
                    var noShowAction = btn.getAttribute('data-no-show-action') || '';
                    var serviceId = btn.getAttribute('data-service-id') || '';
                    var currentDate = btn.getAttribute('data-date') || '';
                    var timelineRaw = btn.getAttribute('data-timeline') || '[]';
                    var clientProfileRaw = btn.getAttribute('data-client-profile') || '{}';
                    var ticketUrl = btn.getAttribute('data-ticket-url') || '#';
                    var deadline = btn.getAttribute('data-deadline');
                    var deadlineSpan = document.getElementById('notesModalDeadline');
                    var deadlineCard = document.getElementById('deadlineCard');
                    var receiptCard = document.getElementById('receiptCard');
                    var price = btn.getAttribute('data-price') || 0;
                    var deposit = btn.getAttribute('data-deposit') || 0;
                    var remaining = btn.getAttribute('data-remaining') || 0;

                    document.getElementById('modalPrice').textContent = parseFloat(price).toFixed(2);
                    document.getElementById('modalDeposit').textContent = parseFloat(deposit).toFixed(
                        2);
                    document.getElementById('modalRemaining').textContent = parseFloat(remaining)
                        .toFixed(2);
                    var anticipoInput = document.getElementById('modalAnticipoMonto');
                    if (anticipoInput) {
                        anticipoInput.value = parseFloat(deposit).toFixed(2);
                    }
                    var editarAnticipoInput = document.getElementById('modalEditarAnticipo');
                    if (editarAnticipoInput) {
                        editarAnticipoInput.value = parseFloat(deposit).toFixed(2);
                    }
                    var pagoVisible = document.getElementById('modalPagoFinal');
                    var pagoHidden = document.getElementById('modalPagoFinalHidden');

                    if (pagoVisible && pagoHidden) {
                        var restante = parseFloat(remaining).toFixed(2);
                        pagoVisible.value = restante;
                        pagoHidden.value = restante;
                    }

                    if (deadline) {
                        deadlineCard.style.display = '';
                        if (deadlineTimer) {
                            clearInterval(deadlineTimer);
                            deadlineTimer = null;
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
                        deadlineSpan.style.color = "";
                        deadlineCard.style.display = '';
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
                    try {
                        currentClientProfile = JSON.parse(clientProfileRaw);
                    } catch (e) {
                        currentClientProfile = null;
                    }
                    if (timelineContainer) {
                        var timeline = [];
                        try {
                            timeline = JSON.parse(timelineRaw);
                        } catch (e) {
                            timeline = [];
                        }

                        timelineContainer.innerHTML = timeline.length ?
                            timeline.map(function(item) {
                                return '<div class="timeline-item">' +
                                    '<div class="timeline-dot"></div>' +
                                    '<div class="timeline-copy">' +
                                    '<strong>' + (item.label || '-') + '</strong>' +
                                    '<span>' + (item.meta || 'Sistema') + '</span>' +
                                    '<small>' + (item.date || '-') + '</small>' +
                                    '</div>' +
                                    '</div>';
                            }).join('') :
                            '<p class="timeline-empty">No hay movimientos registrados.</p>';
                    }
                    if (rejectPaymentForm) {
                        rejectPaymentForm.style.display = canReject ? 'inline-flex' : 'none';
                        rejectPaymentForm.setAttribute('action', rejectAction);
                    }

                    if (receipt) {
                        receiptCard.style.display = '';
                        modalReceipt.innerHTML = '<a href="' + receipt +
                            '" target="_blank" rel="noopener">Ver comprobante</a>';
                    } else {
                        receiptCard.style.display = 'none';
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
                    if (editDepositForm) {
                        editDepositForm.style.display = canEditDeposit ? 'inline-flex' : 'none';
                        editDepositForm.setAttribute('action', editDepositAction);
                    }
                    if (completeForm) {
                        completeForm.style.display = canComplete ? 'inline-flex' : 'none';
                        completeForm.setAttribute('action', completeAction);
                    }
                    if (noShowForm) {
                        noShowForm.style.display = canNoShow ? 'inline-flex' : 'none';
                        noShowForm.setAttribute('action', noShowAction);
                    }
                    var canCancel = btn.getAttribute('data-can-cancel') === '1';

                    if (openCancelFromModal) {
                        openCancelFromModal.setAttribute('data-cancel-action', cancelAction);
                        openCancelFromModal.style.display = canCancel ? 'inline-flex' : 'none';
                    }
                    if (openRescheduleFromModal) {
                        openRescheduleFromModal.style.display = canReschedule ? 'inline-flex' : 'none';
                        openRescheduleFromModal.setAttribute('data-reschedule-action',
                            rescheduleAction);
                        openRescheduleFromModal.setAttribute('data-service-id', serviceId);
                        openRescheduleFromModal.setAttribute('data-date', currentDate);
                    }
                    if (openClientProfileFromModal) {
                        openClientProfileFromModal.style.display = currentClientProfile ?
                            'inline-flex' : 'inline-flex';
                    }
                    if (printTicketFromModal) {
                        printTicketFromModal.setAttribute('href', ticketUrl);
                    }
                    modal.classList.add('active');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            function fillClientProfile(profile) {
                if (!profile) return;
                var initials = (profile.name || 'Cliente')
                    .split(/\s+/)
                    .filter(Boolean)
                    .slice(0, 2)
                    .map(function(part) {
                        return part.charAt(0).toUpperCase();
                    })
                    .join('') || 'CL';

                document.getElementById('clientProfileName').textContent = profile.name || 'Cliente';
                document.getElementById('clientProfileEmail').textContent = profile.email || 'No registrado';
                document.getElementById('clientProfileInitials').textContent = initials;
                document.getElementById('clientProfilePhone').textContent = profile.phone || 'No registrado';
                document.getElementById('clientProfileBirthDate').textContent = profile.birth_date || 'No registrada';
                document.getElementById('clientProfileMemberSince').textContent = profile.member_since ||
                    'Sin registro';
                document.getElementById('clientProfileTotal').textContent = profile.total_appointments ?? 0;
                document.getElementById('clientProfileTotalHero').textContent = (profile.total_appointments ?? 0) +
                    ' citas';
                document.getElementById('clientProfileCompleted').textContent = profile.completed ?? 0;
                document.getElementById('clientProfileCancelled').textContent = profile.cancelled ?? 0;
                document.getElementById('clientProfileNoShow').textContent = profile.no_show ?? 0;
                document.getElementById('clientProfileTotalSpent').textContent = profile.total_spent || '$0.00';
                document.getElementById('clientProfileAvgTicket').textContent = profile.avg_ticket || '$0.00';
                document.getElementById('clientProfileUpcomingCount').textContent = profile.upcoming_count ?? 0;
                document.getElementById('clientProfileCompletionRate').textContent = profile.completion_rate || '0%';
                document.getElementById('clientProfileIncidentRate').textContent = profile.incident_rate || '0%';
                document.getElementById('clientProfileFavoriteEmployee').textContent = profile.favorite_employee ||
                    'Aun sin preferencia clara';
                document.getElementById('clientProfileNext').textContent = profile.next_appointment ||
                    'Sin citas proximas';
                document.getElementById('clientProfileLast').textContent = profile.last_appointment || 'Sin historial';
                document.getElementById('clientProfileLastCompleted').textContent = profile.last_completed ||
                    'Sin visitas completadas';
                document.getElementById('clientProfileValueSummary').textContent = (profile.total_spent || '$0.00') +
                    ' acumulado · ' + (profile.avg_ticket || '$0.00') + ' promedio';

                var favoritesWrap = document.getElementById('clientProfileFavorites');
                var favorites = Array.isArray(profile.favorite_services) ? profile.favorite_services : [];
                favoritesWrap.innerHTML = favorites.length ?
                    favorites.map(function(item) {
                        return '<span class="client-favorite-chip">' +
                            (item.service || 'Servicio') + ' (' + (item.count || 0) + ')' +
                            '</span>';
                    }).join('') :
                    '<p class="client-favorite-empty">Aun no hay suficientes citas para detectar favoritos.</p>';
            }

            function openClientProfile() {
                if (!clientProfileModal || !currentClientProfile) return;

                fillClientProfile(currentClientProfile);
                clientProfileModal.classList.add('active');
                clientProfileModal.setAttribute('aria-hidden', 'false');
            }

            function closeClientProfile() {
                if (!clientProfileModal) return;
                clientProfileModal.classList.remove('active');
                clientProfileModal.setAttribute('aria-hidden', 'true');
            }

            function closeModal() {
                if (deadlineTimer) {
                    clearInterval(deadlineTimer);
                    deadlineTimer = null;
                }
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            closeBtn.addEventListener('click', closeModal);
            if (dismissBtn) {
                dismissBtn.addEventListener('click', closeModal);
            }
            if (openClientProfileFromModal) {
                openClientProfileFromModal.addEventListener('click', function() {
                    closeModal();
                    openClientProfile();
                });
            }
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            if (clientProfileClose) {
                clientProfileClose.addEventListener('click', closeClientProfile);
            }
            if (clientProfileDismiss) {
                clientProfileDismiss.addEventListener('click', closeClientProfile);
            }
            if (clientProfileModal) {
                clientProfileModal.addEventListener('click', function(e) {
                    if (e.target === clientProfileModal) closeClientProfile();
                });
            }

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
                    openCancelFromModal.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        var action = this.getAttribute('data-cancel-action');

                        if (!action) {
                            console.error('No hay ruta de cancelación');
                            return;
                        }

                        closeModal();
                        openCancel(action);
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
            var focusCitaId = new URLSearchParams(window.location.search).get('focus_cita');

            function openFocusedAppointment() {
                if (!focusCitaId) return;

                var targetButton = document.querySelector('.notes-btn[data-cita-id="' + focusCitaId + '"]');
                var targetRow = document.querySelector('[data-cita-row="' + focusCitaId + '"]');

                if (targetRow) {
                    targetRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    targetRow.classList.add('is-highlighted');
                    setTimeout(function() {
                        targetRow.classList.remove('is-highlighted');
                    }, 2400);
                }

                if (targetButton) {
                    setTimeout(function() {
                        targetButton.click();
                        var url = new URL(window.location.href);
                        url.searchParams.delete('focus_cita');
                        window.history.replaceState({}, '', url.toString());
                    }, 180);
                }
            }
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

            openFocusedAppointment();
        })();
    </script>
@endsection
