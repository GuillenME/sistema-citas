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
                                <span class="badge badge-on">Sí</span>
                            @else
                                <span class="badge badge-off">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.servicios.edit', $servicio) }}" class="btn-edit">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#9ca3af;">
                            No hay servicios registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="pagination-wrapper">
        {{ $servicios->links() }}
    </div>

</div>
