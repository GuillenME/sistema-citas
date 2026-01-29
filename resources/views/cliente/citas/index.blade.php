<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/cliente-mis-citas.css') }}">


</head>

<body style="--bg-url: url('{{ asset('imagenes/SalaEsperaa.png') }}')">


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
                                            @if ($promocionActiva)
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
                                            <span class="voucher-label">💳 Anticipo requerido
                                                ({{ $porcentajeAnticipo }}%):</span>
                                            <span class="voucher-anticipo">
                                                ${{ number_format($anticipo, 2) }}
                                            </span>
                                        </div>

                                        <div class="voucher-section">
                                            <span class="voucher-label">💰 Restante a pagar
                                                ({{ $porcentajeRestante }}%):</span>
                                            <span class="voucher-value" style="color: #93c5fd; font-weight: bold;">
                                                ${{ number_format($restante, 2) }}
                                            </span>
                                            <span
                                                style="color: #9ca3af; font-size: 11px; display: block; margin-top: 4px;">
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
                                            <strong style="color:#fde68a;">Datos para anticipo
                                                ({{ $porcentajeAnticipo }}%)</strong><br>
                                            Banco: {{ config('citas.banco.nombre') }}<br>
                                            Cuenta: {{ config('citas.banco.cuenta') }}<br>
                                            CLABE: {{ config('citas.banco.clabe') }}<br>
                                            <small style="color:#9ca3af; margin-top:4px; display:block;">
                                                El {{ $porcentajeRestante }}% restante se pagará después de la cita
                                            </small>
                                            <small style="color:#fde68a; display:block; margin-top:6px;">
                                                ⏳ Tienes <strong>15 minutos</strong> desde que se creó la cita para
                                                subir el comprobante.
                                            </small>

                                        </div>

                                        @if ($cita->receipt)
                                            <a href="{{ asset('storage/' . $cita->receipt) }}" target="_blank"
                                                style="color:#22c55e;font-weight:bold;">
                                                ✔ Ver comprobante
                                            </a>
                                        @else
                                            <form method="POST"
                                                action="{{ route('cliente.citas.comprobante', $cita) }}"
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
