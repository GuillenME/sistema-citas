<div class="serv-shell">
    <div class="serv-panel">
        <header class="serv-head">
            <div>

                <h3>Administra el menu de servicios, duraciones y precios para tus clientes.</h3>
            </div>
            <div class="serv-actions">
                <a href="{{ route('admin.servicios.template') }}" class="serv-btn ghost">
                    <span class="serv-btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                    </span>
                    Plantilla CSV
                </a>
                <a href="{{ route('admin.servicios.import') }}" class="serv-btn ghost">
                    <span class="serv-btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m8 11 4 4 4-4"/><path d="M8 5H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-4"/></svg>
                    </span>
                    Importar
                </a>
                <a href="{{ route('admin.servicios.create') }}" class="serv-btn primary">
                    <span class="serv-btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    </span>
                    Nuevo Servicio
                </a>
            </div>
        </header>

        <div class="table-container serv-table-wrap">
            <table class="admin-table serv-table">
                <thead>
                    <tr>
                        <th>Nombre del servicio</th>
                        <th>Duracion</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($servicios as $servicio)
                        <tr>
                            <td>
                                <div class="serv-name-cell">

                                    <div>
                                        <strong>{{ $servicio->name }}</strong>
                                        <small>{{ $servicio->description ?: 'Sin descripcion' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $servicio->duration_minutes }} min</td>
                            <td>${{ number_format($servicio->price, 2) }}</td>
                            <td>
                                <span class="serv-status {{ $servicio->active ? 'on' : 'off' }}">
                                    {{ $servicio->active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="serv-actions-col">
                                <a href="{{ route('admin.servicios.edit', $servicio) }}" class="serv-icon-btn edit" title="Editar" aria-label="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18.226 5.226-2.52-2.52A2.4 2.4 0 0 0 14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.351"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><path d="M8 18h1"/></svg>
                                </a>
                                <button class="serv-btn ghost" wire:click="toggleActivo({{ $servicio->id }})">
                                    {{ $servicio->active ? 'Desactivar' : 'Activar' }}
                                </button>
                                <button class="serv-icon-btn delete" wire:click="confirmAction({{ $servicio->id }}, 'delete')" title="Eliminar" aria-label="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">No hay servicios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper serv-pagination">
            <div class="pagination-info">
                Mostrando {{ $servicios->firstItem() ?? 0 }}-{{ $servicios->lastItem() ?? 0 }} de {{ $servicios->total() }} servicios
            </div>
            {{ $servicios->links() }}
        </div>
    </div>

    @if ($confirmActionId)
        <div class="modal-overlay" wire:click.self="cancelAction">
            <div class="modal-box">
                <h3>Eliminar servicio?</h3>
                <p>Esta accion elimina el servicio de forma permanente.</p>
                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelAction">Cancelar</button>
                    <button class="btn btn-save" wire:click="executeConfirmedAction" wire:loading.attr="disabled">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session('success') || session('error'))
        <div
            class="modal-overlay"
            id="serviceFlashModal"
            onclick="if (event.target === this) { this.remove(); }"
        >
            <div class="modal-box">
                <h3>{{ session('error') ? 'No se pudo completar la acción' : 'Acción completada' }}</h3>
                <p>{{ session('error') ?? session('success') }}</p>
                <div class="modal-actions">
                    <button
                        type="button"
                        class="btn {{ session('error') ? 'btn-cancel' : 'btn-save' }}"
                        onclick="document.getElementById('serviceFlashModal')?.remove()"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
