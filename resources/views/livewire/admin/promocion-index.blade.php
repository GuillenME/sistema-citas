<div class="serv-shell">
    <div class="serv-panel">
        <header class="serv-head">
            <div>
                <h3>Catalogo de promociones y periodos de vigencia.</h3>
            </div>
            <div class="serv-actions">
                <a href="{{ route('admin.promociones.create') }}" class="serv-btn primary">
                    <span class="serv-btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    </span>
                    Nueva promocion
                </a>
            </div>
        </header>

        <div class="table-container serv-table-wrap">
            <table class="admin-table serv-table">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Descuento</th>
                        <th>Servicios</th>
                        <th>Fecha</th>
                        <th>Publicada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($promociones as $promo)
                        <tr>
                            <td>{{ $promo->title }}</td>
                            <td>{{ $promo->discount }}%</td>
                            <td>
                                @forelse ($promo->servicios as $servicio)
                                    <span class="serv-status on">{{ $servicio->name }}</span>
                                @empty
                                    <span class="serv-status off">Sin servicios</span>
                                @endforelse
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                                -
                                {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="serv-status {{ $promo->published ? 'on' : 'off' }}">
                                    {{ $promo->published ? 'Si' : 'No' }}
                                </span>
                            </td>
                            <td class="serv-actions-col">
                                <a href="{{ route('admin.promociones.edit', $promo) }}" class="serv-icon-btn edit" title="Editar" aria-label="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18.226 5.226-2.52-2.52A2.4 2.4 0 0 0 14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.351"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><path d="M8 18h1"/></svg>
                                </a>
                                <button class="serv-icon-btn delete" wire:click="confirmDelete({{ $promo->id }})" title="Eliminar" aria-label="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty">No hay promociones registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper serv-pagination">
            <div class="pagination-info">
                Mostrando {{ $promociones->firstItem() ?? 0 }}-{{ $promociones->lastItem() ?? 0 }} de {{ $promociones->total() }} promociones
            </div>
            {{ $promociones->links() }}
        </div>
    </div>

    @if ($confirmDeleteId)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3>Eliminar promocion?</h3>
                <p>Esta accion no se puede deshacer.</p>
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
</div>
