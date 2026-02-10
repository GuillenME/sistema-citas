<div class="card table-card">
    <div class="table-container">

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->user->name }} {{ $cliente->user->last_name }}</td>
                        <td>{{ $cliente->user->email }}</td>
                        <td>{{ $cliente->user->phone ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $cliente->user->active ? 'badge-on' : 'badge-off' }}">
                                {{ $cliente->user->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="table-actions">
                            <button
                                class="action-toggle {{ $cliente->user->active ? 'off' : 'on' }}"
                                wire:click="confirmarAccion({{ $cliente->id }}, '{{ $cliente->user->active ? 'desactivar' : 'activar' }}')">
                                {{ $cliente->user->active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>
</div>
