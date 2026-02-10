<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente.css') }}">
</head>

<body style="--bg-url: url('{{ asset('imagenes/SalaEsperaa.png') }}')">

    @include('cliente.partials.menu')

    <div class="container">
        <div class="card">

            <h2>Agendar cita</h2>

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('cliente.citas.store') }}" id="formAgendarCita" class="form-grid">
    @csrf

    {{-- SERVICIO --}}
    <div class="field">
        <label>Servicio</label>
        <select name="servicio_id" id="servicio" required>
            <option value="">Selecciona un servicio</option>
            @foreach ($servicios as $servicio)
                @php
                    $promo = $servicio->promocionActiva();
                    $precioFinal = $servicio->precioConDescuento();
                @endphp
                <option value="{{ $servicio->id }}" data-precio="{{ $servicio->price }}"
                    data-precio-descuento="{{ $precioFinal }}" data-tiene-promocion="{{ $promo ? '1' : '0' }}"
                    data-descripcion="{{ $servicio->description }}"
                    data-duracion="{{ $servicio->duration_minutes }}"
                    data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}">
                    {{ $servicio->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- FECHA --}}
                <div class="field date-field">
                    <label>Fecha</label>
                    <input type="text" id="fecha" name="fecha" class="date-inline" placeholder="Selecciona una fecha"
                        onkeydown="return false;" readonly>
                </div>

                {{-- HORARIO --}}
                <div class="field horario-field">
                    <label>Horario</label>
                    <select id="horarios" name="hora_inicio">
                        <option value="">Selecciona un horario</option>
                    </select>
                </div>

                <div class="field anticipo-field">
                    <div class="anticipo">
                        <h4>Anticipo requerido</h4>
                        <p>Se solicita un <strong>{{ $porcentajeAnticipo }}%</strong> para confirmar la cita</p>
                        <p>El <strong>{{ $porcentajeRestante }}%</strong> restante se pagara despues de la cita</p>

                        <p style="margin-top:10px;color:#fde68a;font-weight:bold;">
                            Tienes <strong>15 minutos</strong> para realizar la transferencia y subir el comprobante.
                            De lo contrario, la cita se cancelara automaticamente.
                        </p>

                        <p>
                            Banco: {{ config('citas.banco.nombre') }}<br>
                            Cuenta: {{ config('citas.banco.cuenta') }}<br>
                            CLABE: {{ config('citas.banco.clabe') }}
                        </p>
                    </div>
                </div>

{{-- PRIVACIDAD --}}
    <div class="field full privacy-field">
        <label class="privacy-label">
            <input type="checkbox" name="acepta_privacidad" class="privacy-checkbox">
            <span class="privacy-text">
                Acepto la <a href="#" class="privacy-link">política de privacidad</a>
            </span>
        </label>
    </div>

    <div class="field full center">
        <button type="button" class="submit-btn" onclick="mostrarModalConfirmar()">
            AGENDAR CITA
        </button>
    </div>
</form>


        </div>
    </div>

    <!-- ================= MODAL CONFIRMAR SERVICIO ================= -->
    <div id="modalServicioConfirmar" class="modal-confirm-overlay"
        onclick="if(event.target === this) cancelarServicio()">
        <div class="modal-confirm-content modal-servicio">

            <img id="msImagen" class="modal-servicio-img" src="" alt="Servicio">

            <h3 id="msNombre"></h3>

            <p id="msDescripcion" class="modal-servicio-desc"></p>

            <p class="modal-servicio-info">
                ⏱ <strong>Duración:</strong> <span id="msDuracion"></span> minutos
            </p>

            <p class="modal-servicio-info">
                💰 <strong>Precio:</strong> <span id="msPrecio"></span>
            </p>

            <div class="modal-confirm-buttons">
                <button class="modal-confirm-btn modal-confirm-btn-submit" onclick="confirmarServicio()">Confirmar
                    servicio</button>

                <button class="modal-confirm-btn modal-confirm-btn-cancel" onclick="cancelarServicio()">Cambiar
                    servicio</button>
            </div>
        </div>
    </div>


    <!-- ================= MODAL CONFIRMAR CITA ================= -->
    <div id="modalConfirmar" class="modal-confirm-overlay" onclick="if(event.target === this) cerrarModalConfirmar()">
        <div class="modal-confirm-content">

            <h3>📅 Confirmar cita</h3>

            <p><strong>Servicio:</strong> <span id="mcServicio"></span></p>
            <p><strong>Fecha:</strong> <span id="mcFecha"></span></p>
            <p><strong>Horario:</strong> <span id="mcHorario"></span></p>

            <hr style="margin:15px 0; opacity:.3">

            <h4 style="color:#fde68a;">⚠ Anticipo requerido</h4>

            <p>
                Se solicita un <strong>{{ $porcentajeAnticipo }}%</strong> para confirmar la cita.<br>
                El <strong>{{ $porcentajeRestante }}%</strong> restante se paga después del servicio.
            </p>

            <p style="margin-top:10px; color:#fca5a5; font-weight:bold;">
                ⏳ Tienes <strong>15 minutos</strong> para realizar el depósito y subir el comprobante.<br>
                Si no se recibe en ese tiempo, la cita será cancelada automáticamente.
            </p>

            <div style="margin-top:10px; background:#111827; padding:10px; border-radius:8px;">
                <p style="margin:0; font-size:14px;">
                    <strong>Banco:</strong> {{ config('citas.banco.nombre') }}<br>
                    <strong>Cuenta:</strong> {{ config('citas.banco.cuenta') }}<br>
                    <strong>CLABE:</strong> {{ config('citas.banco.clabe') }}
                </p>
            </div>

            <div class="modal-confirm-buttons" style="margin-top:15px;">
                <button class="modal-confirm-btn modal-confirm-btn-submit" onclick="confirmarAgendar()">
                    Confirmar y agendar
                </button>

                <button class="modal-confirm-btn modal-confirm-btn-cancel" onclick="cerrarModalConfirmar()">
                    Cancelar
                </button>
            </div>
        </div>
    </div>


    <!-- ================= MODAL LOGOUT ================= -->
    <div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
        <div class="modal-content">
            <h3>¿Cerrar sesión?</h3>
            <div class="modal-buttons">
                <button onclick="confirmarLogout()">Sí</button>
                <button onclick="cerrarModalLogout()">No</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/cliente/citas.js') }}"></script>

</body>

</html>

