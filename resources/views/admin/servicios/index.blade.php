<h1>Servicios</h1>

<a href="{{ route('admin.servicios.create') }}">Nuevo servicio</a>

<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Duración</th>
        <th>Precio</th>
        <th>Activo</th>
        <th>Acciones</th>
    </tr>

    @foreach($servicios as $servicio)
        <tr>
            <td>{{ $servicio->nombre }}</td>
            <td>{{ $servicio->duracion_minutos }} min</td>
            <td>${{ $servicio->precio }}</td>
            <td>{{ $servicio->activo ? 'Sí' : 'No' }}</td>
            <td>
                <a href="{{ route('admin.servicios.edit', $servicio) }}">Editar</a>
            </td>
        </tr>
    @endforeach
</table>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>