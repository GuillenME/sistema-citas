<div class="card table-card">

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Título</th>
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
                                <span class="badge-service">
                                    {{ $servicio->name }}
                                </span>
                            @empty
                                <span style="color:#9ca3af;">Sin servicios</span>
                            @endforelse
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                            -
                            {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                        </td>

                        <td>
                            @if ($promo->published)
                                <span class="badge badge-on">Sí</span>
                            @else
                                <span class="badge badge-off">No</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.promociones.edit', $promo) }}"
                               class="btn-edit">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#9ca3af;">
                            No hay promociones registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="pagination-wrapper">
        {{ $promociones->links() }}
    </div>

</div>
