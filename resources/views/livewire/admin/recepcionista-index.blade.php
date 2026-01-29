<div class="table-container">

    <table>
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
                        @if ($r->active)
                            <span class="badge badge-on">Activo</span>
                        @else
                            <span class="badge badge-off">Inactivo</span>
                        @endif
                    </td>

                    <td class="actions">
                        <a href="{{ route('admin.recepcionistas.edit', $r) }}" class="btn-edit">
                            Editar
                        </a>

                        <button
                            wire:click="toggleActivo({{ $r->id }})"
                            class="btn-delete">
                            {{ $r->active ? 'Desactivar' : 'Activar' }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
