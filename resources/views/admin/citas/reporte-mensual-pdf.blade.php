<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte mensual de citas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { margin: 0 0 8px 0; font-size: 20px; }
        .meta { margin-bottom: 12px; }
        .stats { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .stats th, .stats td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        .chart { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .chart th, .chart td { border: 1px solid #ddd; padding: 5px 6px; text-align: center; }
        .bars { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .bars th, .bars td { border: 1px solid #ddd; padding: 5px 6px; }
        .bars th { background: #f4f4f4; }
        .bar-track {
            width: 100%;
            height: 10px;
            border: 1px solid #d2d2d2;
            background: #fafafa;
        }
        .bar-fill {
            height: 10px;
            background: #a9742a;
        }
        .status { margin-top: 14px; }
        .status li { margin-bottom: 4px; }
    </style>
</head>
<body>
    <h1>Reporte mensual de citas</h1>
    <div class="meta">
        <strong>Mes:</strong> {{ $inicio->translatedFormat('F Y') }}
    </div>

    <table class="stats">
        <tr>
            <th>Total de citas</th>
            <th>Promedio diario</th>
            <th>Día pico</th>
            <th>Máximo en un día</th>
        </tr>
        <tr>
            <td>{{ $totalCitas }}</td>
            <td>{{ number_format($promedioDiario, 2) }}</td>
            <td>{{ $diaPico ?: '-' }}</td>
            <td>{{ $maxCitas }}</td>
        </tr>
    </table>

    <table class="chart">
        <thead>
            <tr>
                <th>Día</th>
                <th>Citas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $idx => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $valores[$idx] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="bars">
        <thead>
            <tr>
                <th style="width: 10%;">Día</th>
                <th style="width: 75%;">Gráfica</th>
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
</body>
</html>
