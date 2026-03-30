<div class="serv-shell">
    <div class="serv-panel">
        <header class="serv-head">
            <div>
                <h3>Gestion de empleados y sus especialidades.</h3>
            </div>
            <div class="serv-actions">
                <a href="{{ route('admin.empleados.create') }}" class="serv-btn primary">
                    <span class="serv-btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    </span>
                    Nuevo empleado
                </a>
            </div>
        </header>

        @if ($reassignMessage)
            <div class="serv-reassign-alert {{ $reassignType === 'error' ? 'is-error' : 'is-success' }}">
                {{ $reassignMessage }}
            </div>
        @endif

        <div class="table-container serv-table-wrap">
            <table class="admin-table serv-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $e)
                        <tr>
                            <td>{{ $e->name }}</td>
                            <td class="serv-specialty-cell">
                                @if ($e->servicios && $e->servicios->count())
                                    @php
                                        $serviciosVisibles = $e->servicios->take(2);
                                        $serviciosOcultos = max(0, $e->servicios->count() - 2);
                                        $specialtyTargetId = 'specialties-' . $e->id;
                                    @endphp
                                    <div class="serv-specialty-list">
                                        @foreach ($serviciosVisibles as $servicio)
                                            <span class="serv-specialty-pill">{{ $servicio->name }}</span>
                                        @endforeach
                                        @foreach ($e->servicios->slice(2) as $servicio)
                                            <span class="serv-specialty-pill serv-specialty-pill-hidden" data-specialty-hidden="{{ $specialtyTargetId }}">
                                                {{ $servicio->name }}
                                            </span>
                                        @endforeach
                                        @if ($serviciosOcultos > 0)
                                            <button
                                                type="button"
                                                class="serv-specialty-more"
                                                data-specialty-toggle="{{ $specialtyTargetId }}"
                                                aria-expanded="false">
                                                +{{ $serviciosOcultos }}
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="serv-specialty-empty">{{ $e->specialty ?? 'Sin especialidades' }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="serv-status {{ $e->active ? 'on' : 'off' }}">
                                    {{ $e->active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="serv-actions-col">
                                <a href="{{ route('admin.empleados.edit', $e) }}" class="serv-icon-btn edit" title="Editar" aria-label="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18.226 5.226-2.52-2.52A2.4 2.4 0 0 0 14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.351"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><path d="M8 18h1"/></svg>
                                </a>
                                @if ($e->active)
                                    <button class="serv-btn ghost" wire:click="toggle({{ $e->id }})">
                                        Desactivar
                                    </button>
                                @else
                                    <button class="serv-btn ghost" wire:click="toggle({{ $e->id }})">
                                        Activar
                                    </button>
                                @endif
                                <button class="serv-btn reassign-btn" wire:click="confirmReassign({{ $e->id }})">
                                    Reasignar citas de hoy
                                </button>
                                <button class="serv-icon-btn delete" wire:click="confirmDelete({{ $e->id }})" title="Dar de baja" aria-label="Dar de baja">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper serv-pagination">
            <div class="pagination-info">
                Mostrando {{ $empleados->firstItem() ?? 0 }}-{{ $empleados->lastItem() ?? 0 }} de {{ $empleados->total() }} empleados
            </div>
            {{ $empleados->links() }}
        </div>
    </div>

    @if ($confirmDeleteId)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box serv-deactivate-modal">
                <div class="serv-deactivate-modal-head">
                    <span class="serv-deactivate-modal-kicker">
                        <span class="serv-deactivate-modal-kicker-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                                <circle cx="9.5" cy="7" r="3"/>
                                <path d="M18 8v6"/>
                                <path d="M15 11h6"/>
                            </svg>
                        </span>
                        <span>Gestion de personal</span>
                    </span>
                    <h3>¿Dar de baja al empleado?</h3>
                </div>

                @php
                    $internalCode = 'EMP-' . str_pad((string) $confirmDeleteId, 4, '0', STR_PAD_LEFT);
                @endphp

                @if ($reassignMessage && $reassignType === 'error')
                    <div class="serv-reassign-alert is-error">
                        {{ $reassignMessage }}
                    </div>
                @endif

                @if ($upcomingAppointmentsCount > 0)
                    <p class="serv-deactivate-modal-copy">
                        Esta accion marcara al empleado como inactivo. Se han detectado compromisos pendientes que requieren
                        atencion antes de proceder.
                    </p>

                    <div class="serv-deactivate-profile-card">
                        <div class="serv-deactivate-profile-head">
                            <div class="serv-deactivate-profile-avatar" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                    <circle cx="12" cy="8" r="4"/>
                                </svg>
                            </div>
                            <div class="serv-deactivate-profile-copy">
                                <span>Nombre del empleado</span>
                                <strong>{{ $upcomingAppointmentsLabel }}</strong>
                            </div>
                            <div class="serv-deactivate-profile-meta">
                                <span>ID interno</span>
                                <strong>{{ $internalCode }}</strong>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="serv-deactivate-summary-card is-inline is-action"
                            wire:click="openUpcomingAppointmentsModal"
                            @disabled(empty($upcomingAppointments))>
                            <div class="serv-deactivate-summary-copy">
                                <span>Citas proximas</span>
                                <small>Requieren reasignacion antes de la baja.</small>
                            </div>
                            <div class="serv-deactivate-summary-value">
                                <strong>{{ $upcomingAppointmentsCount }}</strong>
                            </div>
                        </button>
                    </div>

                    <div class="serv-deactivate-note" role="note">
                        <span class="serv-deactivate-note-icon" aria-hidden="true">!</span>
                        <p>
                            Al reasignar, el sistema buscara automaticamente el perfil mas adecuado dentro del departamento de
                            <strong>Administracion</strong>.
                        </p>
                    </div>

                    <div class="modal-actions serv-deactivate-actions">
                        <button class="btn btn-cancel serv-deactivate-btn" wire:click="cancelDelete">
                            Cancelar
                        </button>
                        <button class="btn btn-save serv-deactivate-btn is-primary" wire:click="deleteAndReassignUpcomingAppointments" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="deleteAndReassignUpcomingAppointments">Reasignar y dar de baja</span>
                            <span wire:loading wire:target="deleteAndReassignUpcomingAppointments">Reasignando...</span>
                        </button>
                    </div>
                @else
                    <p class="serv-deactivate-modal-copy">
                        El empleado quedara inactivo y ya no podra recibir nuevas citas, pero su historial se conservara.
                    </p>

                    <div class="serv-deactivate-profile-card is-simple">
                        <div class="serv-deactivate-profile-head">
                            <div class="serv-deactivate-profile-avatar" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                    <circle cx="12" cy="8" r="4"/>
                                </svg>
                            </div>
                            <div class="serv-deactivate-profile-copy">
                                <span>Nombre del empleado</span>
                                <strong>{{ $upcomingAppointmentsLabel }}</strong>
                            </div>
                            <div class="serv-deactivate-profile-meta">
                                <span>ID interno</span>
                                <strong>{{ $internalCode }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="modal-actions serv-deactivate-actions is-compact">
                        <button class="btn btn-cancel serv-deactivate-btn" wire:click="cancelDelete">Cancelar</button>
                        <button class="btn btn-save serv-deactivate-btn is-primary" wire:click="deleteConfirmed" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="deleteConfirmed">Dar de baja</span>
                            <span wire:loading wire:target="deleteConfirmed">Dando de baja...</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($confirmToggleId)
        <div class="modal-overlay" wire:click.self="cancelToggle">
            <div class="modal-box">
                <h3>¿Desactivar empleado?</h3>
                <p>
                    {{ $toggleUpcomingAppointmentsLabel }} dejara de estar disponible para nuevas asignaciones, pero seguira
                    visible en el sistema.
                </p>

                @if ($reassignMessage && $reassignType === 'error')
                    <div class="serv-reassign-alert is-error">
                        {{ $reassignMessage }}
                    </div>
                @endif

                @if ($toggleUpcomingAppointmentsCount > 0)
                    <div class="serv-reassign-alert is-error">
                        Este empleado tiene {{ $toggleUpcomingAppointmentsCount }} cita(s) proxima(s) activa(s). No se puede
                        desactivar hasta resolverlas.
                    </div>
                @endif

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelToggle">Cancelar</button>
                    <button class="btn btn-save" wire:click="toggleConfirmed" wire:loading.attr="disabled"
                        @disabled($toggleUpcomingAppointmentsCount > 0)>
                        <span wire:loading.remove wire:target="toggleConfirmed">Confirmar desactivacion</span>
                        <span wire:loading wire:target="toggleConfirmed">Desactivando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showUpcomingAppointmentsModal)
        <div class="modal-overlay" wire:click.self="closeUpcomingAppointmentsModal">
            <div class="modal-box serv-appointments-modal">
                <div class="serv-appointments-modal-head">
                    <div>
                        <span class="serv-appointments-modal-kicker">Citas proximas</span>
                        <h3>{{ $upcomingAppointmentsCount }} cita(s) pendientes</h3>
                        <p>{{ $upcomingAppointmentsLabel }} tiene los siguientes compromisos activos.</p>
                    </div>
                    <button type="button" class="serv-appointments-close" wire:click="closeUpcomingAppointmentsModal" aria-label="Cerrar listado de citas">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="serv-appointments-modal-list">
                    @foreach ($upcomingAppointments as $appointment)
                        <article class="serv-deactivate-appointment-item">
                            <div class="serv-deactivate-appointment-main">
                                <strong>{{ $appointment['service'] }}</strong>
                                <span>{{ $appointment['client'] }}</span>
                            </div>
                            <div class="serv-deactivate-appointment-meta">
                                <span>{{ $appointment['date'] }}</span>
                                <small>{{ $appointment['time'] }}</small>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="modal-actions serv-appointments-modal-actions">
                    <button type="button" class="btn btn-cancel serv-deactivate-btn" wire:click="closeUpcomingAppointmentsModal">
                        Volver
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($confirmReassignId)
        <div class="modal-overlay" wire:click.self="cancelReassign">
            <div class="modal-box">
                <h3>Reasignar citas de hoy?</h3>
                <p>Se moveran automaticamente solo las citas compatibles por servicio, horario y disponibilidad.</p>
                @if ($reassignMessage && $reassignType === 'error')
                    <div class="serv-reassign-alert is-error">
                        {{ $reassignMessage }}
                    </div>
                @endif
                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelReassign">Cancelar</button>
                    <button class="btn btn-save" wire:click="reassignTodayAppointments" wire:loading.attr="disabled">
                        <span wire:loading.remove>Reasignar</span>
                        <span wire:loading>Reasignando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-specialty-toggle]');
        if (!toggle) return;

        var target = toggle.getAttribute('data-specialty-toggle');
        if (!target) return;

        var hiddenItems = document.querySelectorAll('[data-specialty-hidden="' + target + '"]');
        if (!hiddenItems.length) return;

        hiddenItems.forEach(function (item) {
            item.classList.remove('serv-specialty-pill-hidden');
        });

        toggle.setAttribute('aria-expanded', 'true');
        toggle.style.display = 'none';
    });
</script>
