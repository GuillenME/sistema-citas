<div class="serv-shell">
    <div class="serv-panel">
        <header class="serv-head">
            <div>
                <h3>Gestión de clientes del sistema.</h3>
            </div>
            <a href="{{ route('admin.clientes.create') }}" class="serv-btn primary">Agregar cliente</a>
        </header>

        <div class="table-container serv-table-wrap">
            <table class="admin-table serv-table">
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
                    @foreach ($items as $cliente)
                        <tr>
                            <td>{{ $cliente->user->name }} {{ $cliente->user->last_name }}</td>
                            <td>{{ $cliente->user->email }}</td>
                            <td>{{ $cliente->user->phone ?? '-' }}</td>
                            <td>
                                <span class="serv-status {{ $cliente->user->active ? 'on' : 'off' }}">
                                    {{ $cliente->user->active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="serv-actions-col">
                                <button
                                    class="serv-btn ghost"
                                    wire:click="confirmarAccion({{ $cliente->id }}, '{{ $cliente->user->active ? 'desactivar' : 'activar' }}')">
                                    {{ $cliente->user->active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper serv-pagination">
            <div class="pagination-info">
                Mostrando {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} de {{ $items->total() }} clientes
            </div>
            {{ $items->links() }}
        </div>
    </div>

    @if ($confirmar)
        <div class="modal-overlay" wire:click.self="$set('confirmar', false)">
            <div class="modal-box">
                <h3>Confirmar accion</h3>
                <p>Se va a {{ $accion }} el cliente seleccionado.</p>
                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">Cancelar</button>
                    <button class="btn btn-save" wire:click="ejecutarAccion">Confirmar</button>
                </div>
            </div>
        </div>
    @endif
</div>
