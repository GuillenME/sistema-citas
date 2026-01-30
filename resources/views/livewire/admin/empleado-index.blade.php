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
                        <td>{{ $e->specialty }}</td>
                        <td>
                            <span class="badge {{ $e->active ? 'badge-on' : 'badge-off' }}">
                                {{ $e->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('admin.empleados.edit', $e) }}" class="btn-edit">
                                Editar
                            </a>

                            <button class="btn-delete" wire:click="toggle({{ $e->id }})">
                                {{ $e->active ? 'Desactivar' : 'Activar' }}
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
</div>
