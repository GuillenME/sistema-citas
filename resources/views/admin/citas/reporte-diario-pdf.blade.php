<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte diario de citas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #2b241c; margin: 24px; }
        .top {
            border: 1px solid #d9c7a2;
            background: #f8f1e6;
            padding: 14px 16px;
            margin-bottom: 14px;
        }
        .brand { font-size: 11px; color: #8a6a3f; text-transform: uppercase; letter-spacing: .12em; }
        h1 { margin: 4px 0 0 0; font-size: 24px; color: #4a3317; }
        .meta { margin-top: 6px; color: #725433; }
        .kpis { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 10px; }
        .kpis td {
            border: 1px solid #e0ceb2;
            background: #fffaf2;
            padding: 10px;
            width: 50%;
            vertical-align: top;
        }
        .k-label { font-size: 10px; color: #9b7751; text-transform: uppercase; letter-spacing: .08em; }
        .k-value { font-size: 20px; color: #3b2a16; font-weight: 700; margin-top: 4px; }
        .section-title {
            margin: 14px 0 6px 0;
            font-size: 14px;
            color: #4a3317;
            border-left: 4px solid #c89b5f;
            padding-left: 8px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e3d3b9; padding: 6px; text-align: left; }
        th { background: #f8f1e6; color: #6f4f2a; }
        td.notes-col {
            font-size: 10px;
            line-height: 1.25;
        }
        .status {
            margin-top: 12px;
            border: 1px solid #e3d3b9;
            background: #fffaf2;
            padding: 10px 12px;
        }
        .status strong { color: #5a4021; }
        .status li { margin-bottom: 4px; }
        .footer {
            margin-top: 14px;
            font-size: 10px;
            color: #8b6e4a;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="top">
        <div class="brand">Barbería & Spa</div>
        <h1>Reporte diario de citas</h1>
        <div class="meta">
            <strong>Fecha:</strong> {{ $fecha->format('d/m/Y') }}
        </div>
    </div>

    <table class="kpis">
        <tr>
            <td>
                <div class="k-label">Total de citas</div>
                <div class="k-value">{{ $totalCitas }}</div>
            </td>
            <td>
                <div class="k-label">Generado</div>
                <div class="k-value" style="font-size:14px;">{{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Detalle de citas del día</div>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Servicio</th>
                <th>Estado</th>
                <th>Empleado</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($citas as $cita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }}</td>
                    <td>{{ $cita->client?->user?->name ?? '-' }}</td>
                    <td>{{ $cita->client?->user?->email ?? '-' }}</td>
                    <td>{{ $cita->service?->name ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $cita->status)) }}</td>
                    <td>{{ $cita->employee?->displayName() ?? '-' }}</td>
                    <td class="notes-col">{{ $cita->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No hay citas registradas para esta fecha.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="status">
        <strong>Resumen por estado:</strong>
        <ul>
            @forelse ($statusResumen as $estado => $total)
                <li>{{ ucfirst(str_replace('_', ' ', $estado)) }}: {{ $total }}</li>
            @empty
                <li>Sin estados registrados.</li>
            @endforelse
        </ul>
    </div>

    <div class="footer">
        Barbería & Spa
    </div>
</body>
</html>
