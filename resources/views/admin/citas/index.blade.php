<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de citas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            padding: 40px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(17, 24, 39, .85);
            border-radius: 12px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px;
            text-align: center;
        }

        th {
            background: rgba(31, 41, 55, .9);
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .estado {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .pendiente_anticipo {
            background: rgba(234, 179, 8, .2);
            color: #fde68a;
        }

        .confirmada {
            background: rgba(34, 197, 94, .2);
            color: #bbf7d0;
        }

        .cancelada {
            background: rgba(239, 68, 68, .2);
            color: #fecaca;
        }

        select {
            background: #020617;
            color: #e5e7eb;
            border-radius: 6px;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, .2);
        }

        .btn {
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 13px;
        }

        .btn-confirmar {
            background: #22c55e;
        }

        .btn-cancelar {
            background: #ef4444;
        }

        .btn-asignar {
            background: #3b82f6;
        }

        a {
            color: #93c5fd;
            text-decoration: none;
        }

        .alert-success {
            background: #16a34a;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-error {
            background: #dc2626;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <h1>Gestión de citas</h1>

    {{-- MENSAJES --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Empleado</th>
                <th>Estado</th>
                <th>Comprobante</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($citas as $cita)
                <tr>
                    <td>{{ $cita->cliente->usuario->nombre }}</td>
                    <td>{{ $cita->servicio->nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $cita->hora_inicio }} - {{ $cita->hora_fin }}</td>

                    {{-- EMPLEADO --}}
                    <td>
                        @if ($cita->estado === 'confirmada' && !$cita->empleado_id)
                            {{-- Confirmada pero sin empleado → permitir asignar --}}
                            <form method="POST" action="{{ route('admin.citas.asignarEmpleado', $cita) }}">
                                @csrf
                                <select name="empleado_id" required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach ($empleados as $empleado)
                                        <option value="{{ $empleado->id }}">
                                            {{ $empleado->nombre }} ({{ $empleado->especialidad }})
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-asignar" title="Asignar empleado">✔</button>
                            </form>
                        @else
                            {{-- Cualquier otro caso → solo mostrar --}}
                            {{ $cita->empleado?->nombre ?? '— Sin asignar —' }}
                        @endif
                    </td>


                    {{-- ESTADO --}}
                    <td>
                        <span class="estado {{ $cita->estado }}">
                            {{ str_replace('_', ' ', ucfirst($cita->estado)) }}
                        </span>
                    </td>

                    {{-- COMPROBANTE --}}
                    <td>
                        @if ($cita->comprobante)
                            <a href="{{ asset('storage/' . $cita->comprobante) }}" target="_blank">Ver</a>
                        @else
                            —
                        @endif
                    </td>

                    {{-- ACCIONES --}}
                    <td>
                        @if ($cita->estado === 'pendiente_anticipo')
                            <form method="POST" action="{{ route('admin.citas.confirmar', $cita) }}">
                                @csrf
                                <button class="btn btn-confirmar">Confirmar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.citas.cancelar', $cita) }}">
                                @csrf
                                <button class="btn btn-cancelar">Cancelar</button>
                            </form>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <a href="{{ route('admin.dashboard') }}">⬅ Volver al panel</a>

</body>

</html>
