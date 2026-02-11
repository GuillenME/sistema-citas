<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-mis-citas.css') }}">


</head>

<body class="cliente-citas-index-page">


    @include('cliente.partials.menu')

    <div class="container">

        <div class="table-card">
            <h2>Historial de citas</h2>
            @if (session('info'))
                <div class="citas-info-alert">
                    {{ session('info') }}
                </div>
            @endif


            @php
                $citasOrdenadas =
                    $citas instanceof \Illuminate\Pagination\LengthAwarePaginator
                        ? $citas->getCollection()->sortByDesc('date')
                        : $citas->sortByDesc('date');
            @endphp

            <div class="citas-grid">
                @foreach ($citasOrdenadas as $cita)
                    @php
                        $precioOriginal = $cita->service->price;
                        $promocionActiva = $cita->service->promocionActiva();
                        $precioFinal = $cita->service->precioConDescuento();
                        $porcentajeDecimal = $porcentajeAnticipo / 100;
                        $anticipo = $precioFinal * $porcentajeDecimal;
                        $restante = $precioFinal - $anticipo;

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

                    <div class="cita-card">
                        <div class="cita-header">
                            <div class="cita-title">{{ $cita->service->name }}</div>
                            <span class="estado {{ $estadoClase }}">{{ $estadoTexto }}</span>
                        </div>

                        <div class="cita-when">
                            <div>Fecha: {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}</div>
                            <div>Hora: {{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($cita->end_time)->format('H:i') }}</div>
                        </div>

                        <div class="cita-price">
                            <span class="price-label">Total:</span>
                            @if ($promocionActiva)
                                <span class="price-original">${{ number_format($precioOriginal, 2) }}</span>
                                <span class="price-final">${{ number_format($precioFinal, 2) }}</span>
                            @else
                                <span class="price-final">${{ number_format($precioFinal, 2) }}</span>
                            @endif
                        </div>

                        @if ($cita->status === 'cancelada' && $cita->notes)
                            <div class="cita-notes">{{ $cita->notes }}</div>
                        @endif

                        <details class="cita-details">
                            <summary>Ver detalles</summary>
                            <div class="cita-details-body">
                                <div>Anticipo ({{ $porcentajeAnticipo }}%):
                                    <strong>${{ number_format($anticipo, 2) }}</strong></div>
                                <div>Restante ({{ $porcentajeRestante }}%): <strong
                                        class="price-restante">${{ number_format($restante, 2) }}</strong></div>

                                @if ($cita->status === 'pendiente_anticipo')
                                    <div class="anticipo-info">
                                        <div>Banco: {{ config('citas.banco.nombre') }}</div>
                                        <div>Cuenta: {{ config('citas.banco.cuenta') }}</div>
                                        <div>CLABE: {{ config('citas.banco.clabe') }}</div>
                                        <div class="anticipo-hint">El {{ $porcentajeRestante }}% restante se paga
                                            despues de la cita.</div>
                                        <div class="anticipo-hint">Tienes 15 minutos para subir el comprobante.</div>
                                    </div>

                                    @if ($cita->receipt)
                                        <a class="link-green" href="{{ asset('storage/' . $cita->receipt) }}"
                                            target="_blank">
                                            Ver comprobante
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('cliente.citas.comprobante', $cita) }}"
                                            enctype="multipart/form-data" class="upload-form">
                                            @csrf
                                            <input type="file" name="comprobante" accept="image/*" required>
                                            <button type="submit" class="btn-upload">Subir comprobante</button>
                                        </form>
                                    @endif
                                @elseif ($cita->receipt)
                                    <a class="link-green" href="{{ asset('storage/' . $cita->receipt) }}"
                                        target="_blank">
                                        Ver comprobante
                                    </a>
                                @endif
                            </div>
                        </details>
                    </div>
                @endforeach
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
