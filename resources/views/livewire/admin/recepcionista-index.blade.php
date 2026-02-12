<div class="card table-card">
    <div class="table-container">

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($recepcionistas as $r)
                    <tr>
                        <td>{{ $r->name }} {{ $r->last_name }}</td>
                        <td>{{ $r->email }}</td>
                        <td>{{ $r->phone ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $r->active ? 'badge-on' : 'badge-off' }}">
                                {{ $r->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('admin.recepcionistas.edit', $r) }}" class="btn-edit">
                                Editar
                            </a>

                            <button class="btn-toggle {{ $r->active ? 'is-on' : 'is-off' }}" wire:click="toggleActivo({{ $r->id }})">
                                {{ $r->active ? 'Desactivar' : 'Activar' }}
                            </button>
                            <button class="btn-delete" wire:click="confirmDelete({{ $r->id }})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        <div class="pagination-info">
            Pagina {{ $recepcionistas->currentPage() }} de {{ $recepcionistas->lastPage() }} ({{ $recepcionistas->total() }} registros)
        </div>
        {{ $recepcionistas->links() }}
    </div>

    @if ($confirmDeleteId)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3>¿Eliminar recepcionista?</h3>

                <p>Esta acción desactivará al recepcionista.</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelDelete">
                        Cancelar
                    </button>

                    <button class="btn btn-save" wire:click="deleteConfirmed" wire:loading.attr="disabled">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>Eliminando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
