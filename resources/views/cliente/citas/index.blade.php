<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;

            background-image: url('{{ asset('imagenes/SalaEsperaa.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            position: relative;
        }

        /* Overlay oscuro */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 0;
        }

        /* ===== HEADER ===== */
        header {
            position: relative;
            z-index: 2;
            background: rgba(42, 22, 218, 0.75);
            color: #fff;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;

            box-shadow: 0 0 25px rgba(42, 22, 218, 0.6);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        /* Flecha */
        .back-btn {
            background: transparent;
            color: #ffffff;
            text-decoration: none;
            font-size: 34px;
            font-weight: bold;
            cursor: pointer;

            text-shadow:
                0 0 6px rgba(255, 255, 255, 0.8),
                0 0 16px rgba(42, 22, 218, 0.8),
                0 0 32px rgba(42, 22, 218, 0.8);

            transition: transform .2s;
        }

        .back-btn:hover {
            transform: scale(1.2);
        }

        /* Logout */
        .logout-btn {
            background: transparent;
            border: 2px solid #ff2d2d;
            padding: 8px 18px;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;

            box-shadow:
                0 0 12px rgba(255, 45, 45, 0.9),
                inset 0 0 6px rgba(255, 45, 45, 0.4);

            transition: transform .2s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
        }

        /* Modal de confirmación */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: rgba(17, 24, 39, 0.95);
            padding: 30px;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            color: #fff;
            box-shadow: 0 0 25px rgba(255, 45, 45, 0.6);
            border: 2px solid rgba(255, 45, 45, 0.5);
        }

        .modal-content h3 {
            margin-bottom: 20px;
            font-size: 20px;
            color: #fff;
        }

        .modal-content p {
            margin-bottom: 25px;
            color: #e5e7eb;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: transform .2s;
        }

        .modal-btn:hover {
            transform: scale(1.05);
        }

        .modal-btn-confirm {
            background: #ef4444;
            color: #fff;
            box-shadow: 0 0 14px rgba(239, 68, 68, 0.7);
        }

        .modal-btn-cancel {
            background: #6b7280;
            color: #fff;
        }

        /* ===== CONTENEDOR ===== */
        .container {
            position: relative;
            z-index: 2;
            padding: 30px 20px;
            max-width: 100%;
            overflow-x: auto;
        }

        .table-card {
            max-width: 95%;
            width: 100%;
            margin: auto;

            background: rgba(17, 24, 39, 0.65);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px;

            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.45),
                0 0 20px rgba(42, 22, 218, 0.6);
            
            overflow-x: auto;
        }

        .table-card h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            color: #fff;
            letter-spacing: 1px;
            font-size: 28px;
        }

        /* ===== TABLA ===== */
        .table-wrapper {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
            color: #e5e7eb;
            font-size: 15px;
        }

        thead {
            background: rgba(31, 41, 55, 0.9);
        }

        th,
        td {
            padding: 16px 12px;
            text-align: center;
            white-space: nowrap;
        }

        th {
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
            color: #fff;
            font-weight: bold;
        }

        /* Permitir que el voucher tenga texto que se ajuste */
        td[data-label="Voucher"] {
            white-space: normal;
            min-width: 250px;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Estado */
        .estado {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .estado.pendiente {
            background: rgba(234, 179, 8, 0.2);
            color: #fde68a;
        }

        .estado.confirmada {
            background: rgba(34, 197, 94, 0.2);
            color: #bbf7d0;
        }

        .estado.cancelada {
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
        }

        /* Voucher styles */
        .voucher-box {
            background: rgba(42, 22, 218, 0.2);
            border: 1px solid rgba(42, 22, 218, 0.4);
            border-radius: 8px;
            padding: 16px;
            font-size: 13px;
            text-align: left;
            min-width: 250px;
            max-width: 300px;
        }

        .voucher-section {
            margin-bottom: 8px;
        }

        .voucher-section:not(:last-child) {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 8px;
        }

        .voucher-label {
            color: #93c5fd;
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }

        .voucher-value {
            color: #e5e7eb;
        }

        .voucher-price-original {
            text-decoration: line-through;
            opacity: 0.6;
            color: #9ca3af;
        }

        .voucher-price-discount {
            color: #22c55e;
            font-weight: bold;
        }

        .voucher-anticipo {
            color: #fde68a;
            font-weight: bold;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            table {
                min-width: 900px;
            }
        }

        @media (max-width: 768px) {
            .table-card {
                padding: 20px 15px;
            }

            table {
                min-width: 800px;
                font-size: 14px;
            }

            th, td {
                padding: 12px 8px;
            }

            .voucher-box {
                min-width: 200px;
                max-width: 250px;
                padding: 12px;
                font-size: 12px;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px 10px;
            }

            .table-card {
                max-width: 100%;
                padding: 15px 10px;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead {
                display: none;
            }

            tbody tr {
                margin-bottom: 20px;
                padding: 15px;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            td {
                text-align: left;
                position: relative;
                padding: 12px 12px 12px 50%;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            td:last-child {
                border-bottom: none;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 12px;
                top: 12px;
                font-weight: bold;
                color: #93c5fd;
                text-align: left;
                font-size: 12px;
            }

            .voucher-box {
                min-width: auto;
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="header-left">
            <a href="{{ route('cliente.dashboard') }}" class="back-btn">←</a>
        </div>

        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" class="logout-btn" onclick="mostrarModalLogout()">Cerrar sesión</button>
        </form>
    </header>

    <div class="container">

        <div class="table-card">
            <h2>Historial de citas</h2>
            @if (session('info'))
                <div
                    style="
        margin-bottom:18px;
        padding:14px;
        border-radius:10px;
        background:rgba(234,179,8,.15);
        border:1px solid rgba(234,179,8,.5);
        color:#fde68a;
        font-size:14px;
        text-align:center;
        box-shadow:0 0 12px rgba(234,179,8,.35);
    ">
                    {{ session('info') }}
                </div>
            @endif


            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Voucher</th>
                            <th>Estado</th>
                            <th>Comprobante</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($citas as $cita)
                        <tr>
                            <td data-label="Servicio">
                                {{ $cita->service->name }}
                            </td>

                            <td data-label="Fecha">
                                {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}
                            </td>

                            <td data-label="Hora">
                                {{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($cita->end_time)->format('H:i') }}
                            </td>

                            <td data-label="Voucher">
                                @php
                                    $precioOriginal = $cita->service->price;
                                    $promocionActiva = $cita->service->promocionActiva();
                                    $precioFinal = $cita->service->precioConDescuento();
                                    $porcentajeDecimal = $porcentajeAnticipo / 100;
                                    $anticipo = $precioFinal * $porcentajeDecimal;
                                    $restante = $precioFinal - $anticipo;
                                @endphp
                                
                                <div class="voucher-box">
                                    <div class="voucher-section">
                                        <span class="voucher-label">📋 Servicio:</span>
                                        <span class="voucher-value">{{ $cita->service->name }}</span>
                                    </div>
                                    
                                    <div class="voucher-section">
                                        <span class="voucher-label">💰 Precio a pagar:</span>
                                        @if($promocionActiva)
                                            <div>
                                                <span class="voucher-price-original">
                                                    ${{ number_format($precioOriginal, 2) }}
                                                </span><br>
                                                <span class="voucher-price-discount">
                                                    ${{ number_format($precioFinal, 2) }}
                                                </span>
                                                <span style="color: #22c55e; font-size: 10px;">
                                                    ({{ $promocionActiva->discount }}% desc.)
                                                </span>
                                            </div>
                                        @else
                                            <span class="voucher-value" style="font-weight: bold;">
                                                ${{ number_format($precioFinal, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="voucher-section">
                                        <span class="voucher-label">💳 Anticipo requerido ({{ $porcentajeAnticipo }}%):</span>
                                        <span class="voucher-anticipo">
                                            ${{ number_format($anticipo, 2) }}
                                        </span>
                                    </div>
                                    
                                    <div class="voucher-section">
                                        <span class="voucher-label">💰 Restante a pagar ({{ $porcentajeRestante }}%):</span>
                                        <span class="voucher-value" style="color: #93c5fd; font-weight: bold;">
                                            ${{ number_format($restante, 2) }}
                                        </span>
                                        <span style="color: #9ca3af; font-size: 11px; display: block; margin-top: 4px;">
                                            (Se paga después de la cita)
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td data-label="Estado">
                                @php
                                    $estadoClase = match ($cita->status) {
                                        'pendiente_anticipo' => 'pendiente',
                                        'confirmada' => 'confirmada',
                                        'cancelada' => 'cancelada',
                                        default => 'pendiente',
                                    };

                                    $estadoTexto = match ($cita->status) {
                                        'pendiente_anticipo' => 'Pendiente de anticipo',
                                        'confirmada' => 'Confirmada',
                                        'cancelada' => 'Cancelada',
                                        default => ucfirst($cita->status),
                                    };
                                @endphp

                                <span class="estado {{ $estadoClase }}">
                                    {{ $estadoTexto }}
                                </span>

                                @if ($cita->status === 'cancelada' && $cita->notes)
                                    <div style="margin-top:6px;font-size:12px;color:#fecaca;">
                                        {{ $cita->notes }}
                                    </div>
                                @endif
                            </td>

                            <td data-label="Comprobante">
                                @if ($cita->status === 'pendiente_anticipo')
                                    <div
                                        style="
            margin-bottom:10px;
            padding:10px;
            border-radius:8px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.2);
            font-size:12px;
            text-align:left;
        ">
                                        <strong style="color:#fde68a;">Datos para anticipo ({{ $porcentajeAnticipo }}%)</strong><br>
                                        Banco: {{ config('citas.banco.nombre') }}<br>
                                        Cuenta: {{ config('citas.banco.cuenta') }}<br>
                                        CLABE: {{ config('citas.banco.clabe') }}<br>
                                        <small style="color:#9ca3af; margin-top:4px; display:block;">
                                            El {{ $porcentajeRestante }}% restante se pagará después de la cita
                                        </small>
                                    </div>

                                    @if ($cita->receipt)
                                        <a href="{{ asset('storage/' . $cita->receipt) }}" target="_blank"
                                            style="color:#22c55e;font-weight:bold;">
                                            ✔ Ver comprobante
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('cliente.citas.comprobante', $cita) }}"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <input type="file" name="comprobante" accept="image/*" required>

                                            <button type="submit"
                                                style="
                        margin-top:6px;
                        background:#eab308;
                        border:none;
                        padding:6px 10px;
                        border-radius:6px;
                        font-weight:bold;
                        cursor:pointer;
                    ">
                                                Subir comprobante
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

    </div>

<script>
// Modal de confirmación de logout
function mostrarModalLogout() {
    document.getElementById('modalLogout').classList.add('active');
}

function cerrarModalLogout() {
    document.getElementById('modalLogout').classList.remove('active');
}

function confirmarLogout() {
    document.getElementById('logoutForm').submit();
}
</script>

<!-- Modal de confirmación de logout -->
<div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro de que deseas cerrar sesión?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Sí, cerrar sesión</button>
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
        </div>
    </div>
</div>

</body>

</html>
