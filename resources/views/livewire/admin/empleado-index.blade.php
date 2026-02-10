<div class="card table-card">
    <div class="table-container">

        <table class="admin-table">
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
                        <td>
                            @if ($e->servicios && $e->servicios->count())
                                @foreach ($e->servicios as $servicio)
                                    <span class="badge badge-on">
                                        {{ $servicio->name }}
                                    </span>
                                @endforeach
                            @else
                                {{ $e->specialty ?? '—' }}
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $e->active ? 'badge-on' : 'badge-off' }}">
                                {{ $e->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('admin.empleados.edit', $e) }}" class="btn-edit">
                                Editar
                            </a>

                            <button class="btn-toggle {{ $e->active ? 'is-on' : 'is-off' }}" wire:click="toggle({{ $e->id }})">
                                {{ $e->active ? 'Desactivar' : 'Activar' }}
                            </button>
                            <button class="btn-delete" wire:click="confirmDelete({{ $e->id }})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        {{ $empleados->links() }}
    </div>

    @if ($confirmDeleteId)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3>¿Eliminar empleado?</h3>

                <p>Esta acción desactivará al empleado.</p>

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
