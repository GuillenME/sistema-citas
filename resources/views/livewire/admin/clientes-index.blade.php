<div class="table-container">

    <table>
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
                        @if ($cliente->user->active)
                            <span class="badge badge-on">Activo</span>
                        @else
                            <span class="badge badge-off">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        @if ($cliente->user->active)
                            <button
                                class="btn btn-cancel"
                                wire:click="confirmarAccion({{ $cliente->id }}, 'desactivar')">
                                Desactivar
                            </button>
                        @else
                            <button
                                class="btn btn-save"
                                wire:click="confirmarAccion({{ $cliente->id }}, 'activar')">
                                Activar
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- MODAL --}}
    @if ($confirmar)
        <div class="modal-overlay">
            <div class="modal-box">
                <h3>
                    {{ $accion === 'activar' ? '¿Activar cliente?' : '¿Desactivar cliente?' }}
                </h3>

                <p>
                    {{ $accion === 'activar'
                        ? 'El cliente podrá volver a usar el sistema.'
                        : 'El cliente no podrá acceder al sistema.' }}
                </p>

                <div class="modal-actions">
                    <button class="btn btn-cancel"
                            wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>

                    <button class="btn btn-save"
                            wire:click="ejecutarAccion">
                        Sí, confirmar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>