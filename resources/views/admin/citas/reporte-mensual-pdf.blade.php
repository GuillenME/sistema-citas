<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte mensual de citas</title>
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
            width: 25%;
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
        .chart { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .chart th, .chart td { border: 1px solid #e3d3b9; padding: 6px; text-align: center; }
        .chart th { background: #f8f1e6; color: #6f4f2a; }
        .chart tfoot th { background: #f1e1c7; color: #4a3317; }
        .bars { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .bars th, .bars td { border: 1px solid #e3d3b9; padding: 6px; }
        .bars th { background: #f8f1e6; color: #6f4f2a; }
        .bar-track {
            width: 100%;
            height: 12px;
            border: 1px solid #dfccb0;
            background: #fff;
        }
        .bar-fill {
            height: 12px;
            background: #c99a5d;
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
        <div class="brand">Barberia & Spa</div>
        <h1>Reporte mensual de citas</h1>
        <div class="meta">
            <strong>Mes:</strong> {{ $inicio->translatedFormat('F Y') }}
        </div>
    </div>

    <table class="kpis">
        <tr>
            <td>
                <div class="k-label">Total de citas</div>
                <div class="k-value">{{ $totalCitas }}</div>
            </td>
            <td>
                <div class="k-label">Ingresos del mes</div>
                <div class="k-value">${{ number_format($ingresosMes, 2) }}</div>
            </td>
            <td>
                <div class="k-label">Dia pico</div>
                <div class="k-value">{{ $diaPico ?: '-' }}</div>
            </td>
            <td>
                <div class="k-label">Ingreso diario promedio</div>
                <div class="k-value">${{ number_format($promedioIngresosDiario, 2) }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Detalle por dia</div>
    <table class="chart">
        <thead>
            <tr>
                <th>Dia</th>
                <th>Citas canceladas</th>
                <th>Citas confirmadas</th>
                <th>Citas completadas</th>
                <th>No asistio</th>
                <th>Totales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalleDiario as $fila)
                <tr>
                    <td>{{ $fila['dia'] }}</td>
                    <td>{{ $fila['canceladas'] }}</td>
                    <td>{{ $fila['confirmadas'] }}</td>
                    <td>{{ $fila['completadas'] }}</td>
                    <td>{{ $fila['no_asistio'] }}</td>
                    <td>{{ $fila['totales'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total mes</th>
                <th>{{ $totalCanceladas ?? 0 }}</th>
                <th>{{ $totalConfirmadas ?? 0 }}</th>
                <th>{{ $totalCompletadas ?? 0 }}</th>
                <th>{{ $totalNoAsistio ?? 0 }}</th>
                <th>{{ $totalCitas }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Grafica de actividad diaria</div>
    <table class="bars">
        <thead>
            <tr>
                <th style="width: 10%;">Dia</th>
                <th style="width: 75%;">Grafica</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $idx => $label)
                @php
                    $valor = (int) ($valores[$idx] ?? 0);
                    $percent = $maxCitas > 0 ? (int) round(($valor / $maxCitas) * 100) : 0;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $label }}</td>
                    <td>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ $percent }}%;"></div>
                        </div>
                    </td>
                    <td style="text-align: center;">{{ $valor }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="status">
        <strong>Resumen por estado:</strong>
        <ul>
            @forelse ($statusResumen as $status => $total)
                <li>{{ ucfirst(str_replace('_', ' ', $status)) }}: {{ $total }}</li>
            @empty
                <li>Sin citas en este mes.</li>
            @endforelse
        </ul>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
