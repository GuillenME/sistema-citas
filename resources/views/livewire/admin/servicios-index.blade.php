<div class="card">

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->name }}</td>
                        <td>{{ $servicio->duration_minutes }} min</td>
                        <td>${{ number_format($servicio->price, 2) }}</td>
                        <td>
                            @if ($servicio->active)
                                <span class="status-badge on">Activo</span>
                            @else
                                <span class="status-badge off">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.servicios.edit', $servicio) }}"
                               class="action-link edit">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">
                            No hay servicios registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $servicios->links() }}
    </div>

</div>
