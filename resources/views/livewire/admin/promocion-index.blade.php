<div class="card table-card">
    <div class="table-container">

        <table class="admin-table">
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
                                <span class="badge badge-on">
                                    {{ $servicio->name }}
                                </span>
                            @empty
                                <span class="table-empty">Sin servicios</span>
                            @endforelse
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                            –
                            {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                        </td>

                        <td>
                            <span class="badge {{ $promo->published ? 'badge-on' : 'badge-off' }}">
                                {{ $promo->published ? 'Sí' : 'No' }}
                            </span>
                        </td>

                        <td class="table-actions">
                            <a href="{{ route('admin.promociones.edit', $promo) }}" class="btn-edit">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">
                            No hay promociones registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        {{ $promociones->links() }}
    </div>
</div>
