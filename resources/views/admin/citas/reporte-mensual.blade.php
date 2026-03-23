@extends('layouts.admin')

@section('title', 'Reporte mensual de citas')
@section('back-url', route('admin.dashboard'))

@section('styles')
    <style>
        .rm-shell { display: grid; gap: 16px; }
        .rm-card {
            background: linear-gradient(135deg, rgba(34,26,18,.92), rgba(58,41,32,.92));
            border: 1px solid rgba(244, 211, 138, .20);
            border-radius: 16px;
            padding: 16px;
        }
        .rm-head { display: flex; justify-content: space-between; align-items: end; gap: 12px; flex-wrap: wrap; }
        .rm-head h3 { margin: 0; color: #fff7e6; }
        .rm-form { display: flex; gap: 8px; align-items: center; }
        .rm-form input[type="month"],
        .rm-form select {
            background: #0d0a06;
            border: 1px solid rgba(244, 211, 138, .25);
            color: #f5e7cf;
            border-radius: 10px;
            padding: 6px 10px;
            font-size: 14px;
        }
        .rm-btn {
            border: 1px solid rgba(244, 211, 138, .35);
            background: rgba(244, 211, 138, .12);
            color: #f4d38a;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 12px;
            line-height: 1.1;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        .rm-stats { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: 12px; }
        .rm-stat { background: rgba(0,0,0,.25); border: 1px solid rgba(244, 211, 138, .16); border-radius: 12px; padding: 12px; }
        .rm-stat p { margin: 0; color: #e9d5b1; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; }
        .rm-stat strong { color: #fff9ef; font-size: 24px; }
        .rm-chart-wrap { height: 320px; overflow-x: auto; }
        .rm-chart {
            min-width: 820px;
            height: 270px;
            display: grid;
            grid-template-columns: repeat({{ count($labels) }}, minmax(16px, 1fr));
            align-items: end;
            gap: 6px;
            padding: 10px 0;
        }
        .rm-bar-col { display: grid; gap: 6px; justify-items: center; }
        .rm-stack {
            width: 100%;
            max-width: 20px;
            min-height: 8px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.20);
            background: rgba(0,0,0,.25);
            display: flex;
            flex-direction: column-reverse;
        }
        .rm-seg { width: 100%; min-height: 0; }
        .seg-confirmada { background: #22c55e; }
        .seg-cancelada { background: #ef4444; }
        .seg-completada { background: #60a5fa; }
        .seg-pendiente { background: #fbbf24; }
        .seg-noasistio { background: #f59e0b; }
        .rm-legend {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
            color: #e9d5b1;
            font-size: 12px;
        }
        .rm-legend-item { display: inline-flex; align-items: center; gap: 6px; }
        .rm-legend-dot { width: 10px; height: 10px; border-radius: 999px; display: inline-block; }
        .rm-day { font-size: 11px; color: #ccb88e; }
        .rm-total { font-size: 10px; color: #f4d38a; min-height: 12px; }
        .rm-status { display: flex; gap: 10px; flex-wrap: wrap; }
        .rm-pill {
            border-radius: 999px;
            border: 1px solid rgba(244, 211, 138, .26);
            background: rgba(244, 211, 138, .08);
            padding: 6px 10px;
            color: #f5e7cf;
            font-size: 13px;
        }
        @media (max-width: 900px) {
            .rm-stats { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
        }
    </style>
@endsection

@section('content')
    <div class="rm-shell">
        @php
            [$yearSelected, $monthSelected] = array_map('intval', explode('-', $mesSeleccionado));
            $currentYear = (int) now()->format('Y');
            $yearFrom = $currentYear - 5;
            $yearTo = $currentYear + 2;
            $monthNames = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            ];
        @endphp
        <section class="rm-card">
            <div class="rm-head">
                <h3>Reporte mensual - {{ $inicio->translatedFormat('F Y') }}</h3>
                <form method="GET" action="{{ route('admin.citas.reporte-mensual') }}" class="rm-form">
                    <input type="hidden" name="mes" id="rmMesHidden" value="{{ $mesSeleccionado }}">
                    <select id="rmMesSelect" aria-label="Mes">
                        @foreach ($monthNames as $monthNumber => $monthName)
                            <option value="{{ str_pad((string) $monthNumber, 2, '0', STR_PAD_LEFT) }}" @selected($monthNumber === $monthSelected)>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                    <select id="rmYearSelect" aria-label="Ano">
                        @for ($year = $yearFrom; $year <= $yearTo; $year++)
                            <option value="{{ $year }}" @selected($year === $yearSelected)>{{ $year }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="rm-btn">Ver mes</button>
                    <a href="{{ route('admin.citas.reporte-mensual.pdf', ['mes' => $mesSeleccionado]) }}" class="rm-btn">Descargar PDF</a>
                </form>
            </div>
        </section>

        <section class="rm-stats">
            <article class="rm-stat">
                <p>Total de citas</p>
                <strong>{{ $totalCitas }}</strong>
            </article>
            <article class="rm-stat">
                <p>Ingresos del mes</p>
                <strong>${{ number_format($ingresosMes, 2) }}</strong>
            </article>
            <article class="rm-stat">
                <p>Dia con mas citas</p>
                <strong>{{ $diaPico ? $diaPico : '-' }}</strong>
            </article>
            <article class="rm-stat">
                <p>Ingreso diario promedio</p>
                <strong>${{ number_format($promedioIngresosDiario, 2) }}</strong>
            </article>
        </section>

        <section class="rm-card">
            <h3 style="margin:0 0 10px 0;color:#fff7e6;">Citas por día (desglose por estado)</h3>
            <div class="rm-legend">
                <span class="rm-legend-item"><span class="rm-legend-dot seg-confirmada"></span>Confirmadas</span>
                <span class="rm-legend-item"><span class="rm-legend-dot seg-completada"></span>Completadas</span>
                <span class="rm-legend-item"><span class="rm-legend-dot seg-pendiente"></span>Pendiente anticipo</span>
                <span class="rm-legend-item"><span class="rm-legend-dot seg-cancelada"></span>Canceladas</span>
                <span class="rm-legend-item"><span class="rm-legend-dot seg-noasistio"></span>No asistio</span>
            </div>
            <div class="rm-chart-wrap">
                <div class="rm-chart">
                    @foreach ($labels as $idx => $label)
                        @php
                            $valor = $valores[$idx] ?? 0;
                            $altura = $maxCitas > 0 ? max(2, round(($valor / $maxCitas) * 180)) : 2;
                            $confirmada = $valoresConfirmadas[$idx] ?? 0;
                            $completada = $valoresCompletadas[$idx] ?? 0;
                            $pendiente = $valoresPendientes[$idx] ?? 0;
                            $cancelada = $valoresCanceladas[$idx] ?? 0;
                            $noAsistio = $valoresNoAsistio[$idx] ?? 0;
                            $totalStack = max(1, $confirmada + $completada + $pendiente + $cancelada + $noAsistio);
                        @endphp
                        <div class="rm-bar-col" title="Dia {{ $label }}: {{ $valor }} cita(s)">
                            <span class="rm-total">{{ $valor > 0 ? $valor : '' }}</span>
                            <div class="rm-stack" style="height: {{ $altura }}px;">
                                <span class="rm-seg seg-confirmada" style="height: {{ round(($confirmada / $totalStack) * $altura) }}px"></span>
                                <span class="rm-seg seg-completada" style="height: {{ round(($completada / $totalStack) * $altura) }}px"></span>
                                <span class="rm-seg seg-pendiente" style="height: {{ round(($pendiente / $totalStack) * $altura) }}px"></span>
                                <span class="rm-seg seg-cancelada" style="height: {{ round(($cancelada / $totalStack) * $altura) }}px"></span>
                                <span class="rm-seg seg-noasistio" style="height: {{ round(($noAsistio / $totalStack) * $altura) }}px"></span>
                            </div>
                            <span class="rm-day">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rm-card">
            <h3 style="margin:0 0 10px 0;color:#fff7e6;">Resumen por estado</h3>
            <div class="rm-status">
                @forelse ($statusResumen as $status => $total)
                    <span class="rm-pill">{{ ucfirst(str_replace('_', ' ', $status)) }}: {{ $total }}</span>
                @empty
                    <span class="rm-pill">Sin citas en este mes</span>
                @endforelse
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script>
    (function () {
        var hidden = document.getElementById('rmMesHidden');
        var month = document.getElementById('rmMesSelect');
        var year = document.getElementById('rmYearSelect');
        if (!hidden || !month || !year) return;

        function sync() {
            hidden.value = year.value + '-' + month.value;
        }

        month.addEventListener('change', sync);
        year.addEventListener('change', sync);
        sync();
    })();
</script>
@endsection
