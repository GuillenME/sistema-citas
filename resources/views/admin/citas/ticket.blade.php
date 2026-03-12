<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket cita #{{ $cita->id }}</title>
    <style>
        :root {
            --paper: #fffdf8;
            --ink: #24170d;
            --muted: #7c664f;
            --line: #dcc29d;
            --accent: #9b5a24;
            --accent-soft: #f2e0c4;
            --success: #157347;
            --danger: #c0392b;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: #f2ede5;
            color: var(--ink);
            padding: 32px;
        }
        .ticket-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 22px;
        }
        .ticket-actions button,
        .ticket-actions a {
            border: 1px solid #b99162;
            background: linear-gradient(135deg, #f0cf9a, #d39a56);
            color: #2d180b;
            text-decoration: none;
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
        }
        .ticket-sheet {
            width: min(760px, 100%);
            margin: 0 auto;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(36, 23, 13, 0.12);
            overflow: hidden;
        }
        .ticket-head {
            padding: 28px 30px 22px;
            background: linear-gradient(145deg, #fff7ea, #f6e7cf);
            border-bottom: 1px dashed var(--line);
        }
        .ticket-kicker {
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 700;
        }
        .ticket-head h1 {
            margin: 10px 0 6px;
            font-size: 34px;
        }
        .ticket-head p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }
        .ticket-body {
            display: grid;
            gap: 18px;
            padding: 24px 30px 30px;
        }
        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .ticket-card {
            padding: 16px 18px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fffaf1;
        }
        .ticket-card span {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .ticket-card strong {
            display: block;
            font-size: 24px;
            line-height: 1.2;
        }
        .ticket-card p {
            margin: 0;
            font-size: 17px;
            line-height: 1.45;
        }
        .ticket-card.is-success strong { color: var(--success); }
        .ticket-card.is-danger strong { color: var(--danger); }
        .ticket-card.is-wide {
            grid-column: 1 / -1;
        }
        .ticket-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .ticket-footer {
            border-top: 1px dashed var(--line);
            padding-top: 18px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .ticket-actions {
                display: none;
            }
            .ticket-sheet {
                width: 100%;
                max-width: none;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }
        }
        @media (max-width: 640px) {
            body { padding: 16px; }
            .ticket-grid,
            .ticket-summary {
                grid-template-columns: 1fr;
            }
            .ticket-head,
            .ticket-body {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-actions">
        <button type="button" onclick="window.print()">Imprimir ticket</button>
        <a href="{{ route('admin.citas.index') }}">Volver a citas</a>
    </div>

    <article class="ticket-sheet">
        <header class="ticket-head">
            <span class="ticket-kicker">Barberia & Spa</span>
            <h1>Ticket de cita #{{ $cita->id }}</h1>
            <p>Resumen imprimible de la cita, pagos registrados y datos clave para control interno.</p>
        </header>

        <section class="ticket-body">
            <div class="ticket-grid">
                <div class="ticket-card">
                    <span>Cliente</span>
                    <p>{{ trim(($cita->client?->user?->name ?? '') . ' ' . ($cita->client?->user?->last_name ?? '')) ?: 'Cliente' }}</p>
                </div>
                <div class="ticket-card">
                    <span>Servicio</span>
                    <p>{{ $cita->service?->name ?? 'Servicio' }}</p>
                </div>
                <div class="ticket-card">
                    <span>Fecha</span>
                    <p>{{ optional($cita->date)->format('d/m/Y') }}</p>
                </div>
                <div class="ticket-card">
                    <span>Horario</span>
                    <p>{{ \Carbon\Carbon::parse($cita->getRawOriginal('start_time'))->format('h:i A') }} - {{ \Carbon\Carbon::parse($cita->getRawOriginal('end_time'))->format('h:i A') }}</p>
                </div>
                <div class="ticket-card">
                    <span>Empleado</span>
                    <p>{{ $cita->employee?->name ?? 'Sin asignar' }}</p>
                </div>
                <div class="ticket-card">
                    <span>Estado</span>
                    <p>{{ ucfirst(str_replace('_', ' ', $cita->status)) }}</p>
                </div>
            </div>

            <div class="ticket-summary">
                <div class="ticket-card">
                    <span>Precio servicio</span>
                    <strong>${{ number_format($precio, 2) }}</strong>
                </div>
                <div class="ticket-card is-success">
                    <span>Anticipo</span>
                    <strong>${{ number_format($anticipo, 2) }}</strong>
                </div>
                <div class="ticket-card is-danger">
                    <span>Restante</span>
                    <strong>${{ number_format($restante, 2) }}</strong>
                </div>
            </div>

            <div class="ticket-grid">
                <div class="ticket-card is-wide">
                    <span>Comentario</span>
                    <p>{{ trim((string) ($cita->notes ?? '')) ?: 'Sin comentarios adicionales.' }}</p>
                </div>
                <div class="ticket-card is-wide">
                    <span>Contacto</span>
                    <p>{{ $cita->client?->user?->email ?? 'Correo no registrado' }}{{ $cita->client?->user?->phone ? ' · ' . $cita->client->user->phone : '' }}</p>
                </div>
            </div>

            <footer class="ticket-footer">
                Emitido el {{ now()->format('d/m/Y h:i A') }}.
                Este ticket es de control interno y puede utilizarse para seguimiento de pagos o entrega al cliente.
            </footer>
        </section>
    </article>
</body>
</html>
