<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1f2937;
            color: white;
        }
    </style>
</head>

<body>

    <h1>Listado de clientes</h1>

    <table>
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->user->name }}</td>
                    <td>{{ $cliente->user->last_name ?? '—' }}</td>
                    <td>{{ $cliente->user->email }}</td>
                    <td>{{ $cliente->user->phone ?? '—' }}</td>
                    <td>
                        @if ($cliente->user->active)
                            <span style="color:green;font-weight:bold;">Activo</span>
                        @else
                            <span style="color:red;font-weight:bold;">Inactivo</span>
                        @endif
                    </td>

                    <td>
                        @if ($cliente->user->active)
                            <form method="POST" action="{{ route('admin.clientes.desactivar', $cliente) }}">
                                @csrf
                                <button
                                    style="
                background:#dc2626;
                color:white;
                border:none;
                padding:6px 10px;
                border-radius:6px;
                cursor:pointer;
            ">
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.clientes.activar', $cliente) }}">
                                @csrf
                                <button
                                    style="
                background:#16a34a;
                color:white;
                border:none;
                padding:6px 10px;
                border-radius:6px;
                cursor:pointer;
            ">
                                    Activar
                                </button>
                            </form>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
<a href="{{ route('admin.dashboard') }}">⬅ Volver</a>



</body>

</html>
