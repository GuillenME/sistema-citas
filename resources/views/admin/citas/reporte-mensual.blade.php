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
        .rm-form input[type="month"] {
            background: #0d0a06;
            border: 1px solid rgba(244, 211, 138, .25);
            color: #f5e7cf;
            border-radius: 10px;
            padding: 8px 10px;
        }
        .rm-btn {
            border: 1px solid rgba(244, 211, 138, .35);
            background: rgba(244, 211, 138, .12);
            color: #f4d38a;
            border-radius: 999px;
            padding: 8px 12px;
            text-decoration: none;
            font-weight: 600;
        }
        .rm-stats { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: 12px; }
        .rm-stat { background: rgba(0,0,0,.25); border: 1px solid rgba(244, 211, 138, .16); border-radius: 12px; padding: 12px; }
        .rm-stat p { margin: 0; color: #e9d5b1; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; }
        .rm-stat strong { color: #fff9ef; font-size: 24px; }
        .rm-chart-wrap { height: 280px; overflow-x: auto; }
        .rm-chart {
            min-width: 820px;
            height: 240px;
            display: grid;
            grid-template-columns: repeat({{ count($labels) }}, minmax(16px, 1fr));
            align-items: end;
            gap: 6px;
            padding: 10px 0;
        }
        .rm-bar-col { display: grid; gap: 6px; justify-items: center; }
        .rm-bar {
            width: 100%;
            max-width: 18px;
            min-height: 2px;
            border-radius: 8px 8px 4px 4px;
            background: linear-gradient(180deg, #f4d38a, #dca74e);
            border: 1px solid rgba(255,255,255,.20);
        }
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
        <section class="rm-card">
            <div class="rm-head">
                <h3>Reporte mensual - {{ $inicio->translatedFormat('F Y') }}</h3>
                <form method="GET" action="{{ route('admin.citas.reporte-mensual') }}" class="rm-form">
                    <input type="month" name="mes" value="{{ $mesSeleccionado }}">
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
                <p>Promedio diario</p>
                <strong>{{ number_format($promedioDiario, 2) }}</strong>
            </article>
            <article class="rm-stat">
                <p>Día con más citas</p>
                <strong>{{ $diaPico ? $diaPico : '-' }}</strong>
            </article>
            <article class="rm-stat">
                <p>Máximo en un día</p>
                <strong>{{ $maxCitas }}</strong>
            </article>
        </section>

        <section class="rm-card">
            <h3 style="margin:0 0 10px 0;color:#fff7e6;">Citas por día</h3>
            <div class="rm-chart-wrap">
                <div class="rm-chart">
                    @foreach ($labels as $idx => $label)
                        @php
                            $valor = $valores[$idx] ?? 0;
                            $altura = $maxCitas > 0 ? max(2, round(($valor / $maxCitas) * 180)) : 2;
                        @endphp
                        <div class="rm-bar-col" title="Día {{ $label }}: {{ $valor }} cita(s)">
                            <span class="rm-total">{{ $valor > 0 ? $valor : '' }}</span>
                            <div class="rm-bar" style="height: {{ $altura }}px;"></div>
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
