<h1>Promociones</h1>

<a href="{{ route('admin.promociones.create') }}">Nueva promoción</a>

<table border="1">
    <tr>
        <th>Título</th>
        <th>Publicada</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>

    @foreach ($promociones as $promo)
        <tr>
            <td>{{ $promo->titulo }}</td>
            <td>{{ $promo->publicada ? 'Sí' : 'No' }}</td>
            <td>{{ $promo->fecha_publicacion }}</td>
            <td>
                <a href="{{ route('admin.promociones.edit', $promo) }}">Editar</a>
            </td>
        </tr>
    @endforeach
</table>
