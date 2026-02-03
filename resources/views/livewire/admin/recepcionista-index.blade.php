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

                            <button class="btn-delete" wire:click="toggleActivo({{ $r->id }})">
                                {{ $r->active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        {{ $recepcionistas->links() }}
    </div>
</div>
