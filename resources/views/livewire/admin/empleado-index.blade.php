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
                                <button class="serv-btn ghost" wire:click="toggle({{ $e->id }})">
                                    {{ $e->active ? 'Desactivar' : 'Activar' }}
                                </button>
                                <button class="serv-btn reassign-btn" wire:click="confirmReassign({{ $e->id }})">
                                    Reasignar citas de hoy
                                </button>
                                <button class="serv-icon-btn delete" wire:click="confirmDelete({{ $e->id }})" title="Eliminar" aria-label="Eliminar">
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
            <div class="modal-box">
                <h3>Eliminar empleado?</h3>
                <p>Esta accion eliminara el empleado seleccionado.</p>
                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelDelete">Cancelar</button>
                    <button class="btn btn-save" wire:click="deleteConfirmed" wire:loading.attr="disabled">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>Eliminando...</span>
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
