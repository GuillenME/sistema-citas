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
            background: rgba(34, 197, 94, .2);
        }

        .btn-cancelar {
            background: rgba(239, 68, 68, .2);
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
        .admin-back-btn {
            position: fixed;
            top: 20px;
            left: 20px;

            display: inline-flex;
            align-items: center;
            gap: 10px;

            padding: 10px 16px;
            border-radius: 12px;

            background: rgba(17, 24, 39, .85);
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;

            box-shadow: 0 0 18px rgba(42, 22, 218, .6);
            backdrop-filter: blur(6px);

            transition: all .25s ease;
            z-index: 1000;
        }

        .admin-back-btn span {
            font-size: 20px;
            line-height: 1;
        }

        .admin-back-btn:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 0 25px rgba(42, 22, 218, .9);
            background: rgba(31, 41, 55, .95);
        }
    </style>
</head>

<body>
    
<a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
    <span>←</span>
    Panel
</a>

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
                <th>Observaciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($citas as $cita)
                <tr>
                    <td>{{ $cita->client->user->name }}</td>
                    <td>{{ $cita->service->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}</td>
                    <td>{{ $cita->start_time }} - {{ $cita->end_time }}</td>

                    {{-- EMPLEADO --}}
                    <td>
                        @if ($cita->status === 'confirmada' && !$cita->employee_id)
                            {{-- Confirmada pero sin empleado → permitir asignar --}}
                            <form method="POST" action="{{ route('admin.citas.asignarEmpleado', $cita) }}">
                                @csrf
                                <select name="empleado_id" required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach ($empleados as $empleado)
                                        <option value="{{ $empleado->id }}">
                                            {{ $empleado->name }} ({{ $empleado->specialty }})
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-asignar" title="Asignar empleado">✔</button>
                            </form>
                        @else
                            {{-- Cualquier otro caso → solo mostrar --}}
                            {{ $cita->employee?->name ?? '— Sin asignar —' }}
                        @endif
                    </td>


                    {{-- ESTADO --}}
                    <td>
                        <span class="estado {{ $cita->status }}">
                            {{ str_replace('_', ' ', ucfirst($cita->status)) }}
                        </span>
                    </td>

                    {{-- COMPROBANTE --}}
                    <td>
                        @if ($cita->receipt)
                            <a href="{{ asset('storage/' . $cita->receipt) }}" target="_blank">Ver</a>
                        @else
                            —
                        @endif
                    </td>

                    {{-- ACCIONES --}}
                    <td>
                        @if ($cita->status === 'pendiente_anticipo')
                            <form method="POST" action="{{ route('admin.citas.confirmar', $cita) }}">
                                @csrf
                                <button class="btn btn-confirmar">Confirmar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.citas.cancelar', $cita) }}">
                                @csrf
                                <textarea name="observaciones" rows="2" placeholder="Motivo de cancelación"
                                    style="
            width:100%;
            margin-bottom:6px;
            border-radius:6px;
            padding:6px;
            font-size:12px;
        "></textarea>

                                <button class="btn btn-cancelar">Cancelar</button>
                            </form>
                        @else
                            —
                        @endif
                    </td>
                    {{-- OBSERVACIONES --}}
                    <td style="max-width:200px; text-align:left;">
                        {{ $cita->notes ?? '—' }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    </a>
</body>
</html>
