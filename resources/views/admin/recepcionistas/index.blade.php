<h1>Recepcionistas</h1>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<a href="{{ route('admin.recepcionistas.create') }}">➕ Nuevo recepcionista</a>

<table>
    <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Teléfono</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>

    @foreach($recepcionistas as $r)
        <tr>
            <td>{{ $r->nombre }} {{ $r->apellido }}</td>
            <td>{{ $r->email }}</td>
            <td>{{ $r->telefono ?? '—' }}</td>
            <td>{{ $r->activo ? 'Activo' : 'Inactivo' }}</td>
            <td>
                <a href="{{ route('admin.recepcionistas.edit', $r) }}">Editar</a>

                <form method="POST"
                      action="{{ route('admin.recepcionistas.toggle', $r) }}"
                      style="display:inline">
                    @csrf
                    <button>
                        {{ $r->activo ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
