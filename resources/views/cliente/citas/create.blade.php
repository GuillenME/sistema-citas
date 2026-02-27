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

<body class="cliente-citas-create-page">

    @include('cliente.partials.menu')

    <div class="container">
        <div class="card booking-shell">

            <div class="booking-head">
                <h2>Agendar Cita</h2>
                <p>Reserva tu experiencia premium. Selecciona el servicio, la fecha y la hora que mejor se adapte a tu estilo.</p>
            </div>

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

            <form method="POST" action="{{ route('cliente.citas.store') }}" id="formAgendarCita" class="form-grid booking-grid">
    @csrf

    <div class="booking-left">
    {{-- SERVICIO --}}
    <div class="field">
        <label><span class="step-dot">1</span>Seleccionar Servicio</label>
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
                    {{ (string) old('servicio_id') === (string) $servicio->id ? 'selected' : '' }}
                    data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}">
                    {{ $servicio->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- FECHA --}}
                <div class="field date-field">
                    <label><span class="step-dot">2</span>Seleccionar Fecha</label>
                    <input type="text" id="fecha" name="fecha" class="date-inline" placeholder="Selecciona una fecha"
                        value="{{ old('fecha') }}" onkeydown="return false;" readonly>
                </div>

                {{-- HORARIO --}}
                <div class="field horario-field">
                    <label><span class="step-dot">3</span>Seleccionar Hora</label>
                    <div id="horarioChips" class="horario-chips"></div>
	                    <select id="horarios" name="hora_inicio" class="sr-only-select">
	                        <option value="">Selecciona un horario</option>
	                    </select>
                        <input type="hidden" id="horaInicioOld" value="{{ old('hora_inicio') }}">
	                </div>
    </div>

                <div class="field anticipo-field booking-summary">
                    <h4>Resumen de Cita <span class="summary-pill">Pendiente</span></h4>
                    <div class="summary-item">
                        <span>Servicio</span>
                        <strong id="summaryService">-</strong>
                    </div>
	                    <div class="summary-item">
	                        <span>Fecha y Hora</span>
	                        <strong id="summaryDateTime">-</strong>
	                    </div>
                        <div class="summary-item">
                            <span>Duración del servicio</span>
                            <strong id="summaryDuration">-</strong>
                        </div>
                    <div class="summary-divider"></div>
                    <div class="summary-amount">
                        <span>Anticipo Requerido</span>
                        <strong id="summaryAnticipo">$0.00</strong>
                    </div>
                    <div class="summary-bank">
                        <p>Banco: <strong>{{ config('citas.banco.nombre') }}</strong></p>
                        <p>Cuenta: <strong>{{ config('citas.banco.cuenta') }}</strong></p>
                        <p>CLABE: <strong>{{ config('citas.banco.clabe') }}</strong></p>
                    </div>
                    <p class="anticipo-time-note">
                            Tiene 15 minutos para realizar el depósito y subir el comprobante. De lo contrario, la cita será cancelada automáticamente.
                    </p>
                    {{-- PRIVACIDAD --}}
                    <div class="field privacy-field summary-privacy">
                        <label class="privacy-label">
	                            <input type="checkbox" name="acepta_privacidad" class="privacy-checkbox" {{ old('acepta_privacidad') ? 'checked' : '' }}>
                            <span class="privacy-text">
                                Acepto la <a href="#" class="privacy-link">política de privacidad</a>
                            </span>
                        </label>
                    </div>

                    <div class="field summary-submit">
                        <button type="button" class="submit-btn" onclick="mostrarModalConfirmar()">
                            AGENDAR CITA
                        </button>
                    </div>
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
                <p><strong>Duracion:</strong> <span id="mcDuracion"></span></p>

            <hr class="modal-separator">

            <h4 class="modal-warning-title">⚠ Anticipo requerido</h4>

            <p>
                Se solicita un <strong>{{ $porcentajeAnticipo }}%</strong> para confirmar la cita.<br>
                El <strong>{{ $porcentajeRestante }}%</strong> restante se paga después del servicio.
            </p>

            <p class="modal-warning-note">
                ⏳ Tienes <strong>15 minutos</strong> para realizar el depósito y subir el comprobante.<br>
                Si no se recibe en ese tiempo, la cita será cancelada automáticamente.
            </p>

            <div class="modal-bank-box">
                <p class="modal-bank-text">
                    <strong>Banco:</strong> {{ config('citas.banco.nombre') }}<br>
                    <strong>Cuenta:</strong> {{ config('citas.banco.cuenta') }}<br>
                    <strong>CLABE:</strong> {{ config('citas.banco.clabe') }}
                </p>
            </div>

            <div class="modal-confirm-buttons modal-confirm-buttons-spaced">
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
    <div id="modalLogout" class="modal-overlay">

    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro de que deseas cerrar sesión?</p>

        <div class="modal-buttons">
            <button class="modal-btn modal-btn-confirm"
                    onclick="confirmarLogout()">
                Sí, cerrar sesión
            </button>

            <button class="modal-btn modal-btn-cancel"
                    onclick="cerrarModalLogout()">
                Cancelar
            </button>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="{{ asset('js/cliente/citas.js') }}"></script>
    <script>
        (function () {
            const servicio = document.getElementById('servicio');
            const fecha = document.getElementById('fecha');
            const horarios = document.getElementById('horarios');
            const chipsWrap = document.getElementById('horarioChips');
	            const summaryService = document.getElementById('summaryService');
	            const summaryDateTime = document.getElementById('summaryDateTime');
                const summaryDuration = document.getElementById('summaryDuration');
	            const summaryAnticipo = document.getElementById('summaryAnticipo');
	            const anticipoPct = {{ (float) $porcentajeAnticipo }};

            if (!servicio || !fecha || !horarios || !chipsWrap) return;

            function formatMoney(value) {
                const num = Number(value || 0);
                return '$' + num.toFixed(2);
            }

            function formatFecha(fechaIso) {
                if (!fechaIso) return '-';
                const d = new Date(fechaIso + 'T00:00:00');
                return d.toLocaleDateString('es-MX', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            function formatHora(hora24) {
                if (!hora24) return '';
                const [h, m] = hora24.split(':');
                const date = new Date();
                date.setHours(Number(h), Number(m || 0), 0, 0);
                return date.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', hour12: true });
            }

            function syncSummary() {
                const opt = servicio.options[servicio.selectedIndex];
	                const serviceName = opt && opt.value ? opt.textContent.trim() : '-';
                    const serviceDuration = opt && opt.value ? Number(opt.dataset.duracion || 0) : 0;
	                const precioBase = opt && opt.value ? Number(opt.dataset.precioDescuento || opt.dataset.precio || 0) : 0;
	                const anticipo = precioBase * (anticipoPct / 100);
	                const hora = horarios.value ? formatHora(horarios.value) : '';

	                summaryService.textContent = serviceName;
	                summaryDateTime.textContent = fecha.value ? `${formatFecha(fecha.value)}${hora ? ' - ' + hora : ''}` : '-';
                    summaryDuration.textContent = serviceDuration > 0 ? `${serviceDuration} minutos` : '-';
	                summaryAnticipo.textContent = formatMoney(anticipo);
	            }

            function renderHoraChips() {
                chipsWrap.innerHTML = '';
                const opts = Array.from(horarios.options).filter(o => o.value);

                if (!opts.length) {
                    const empty = document.createElement('div');
                    empty.className = 'horario-empty';
                    empty.textContent = 'Selecciona servicio y fecha para ver horarios.';
                    chipsWrap.appendChild(empty);
                    return;
                }

                opts.forEach((opt) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'hora-chip';
                    btn.textContent = opt.textContent;
                    btn.dataset.value = opt.value;
                    if (horarios.value === opt.value) {
                        btn.classList.add('active');
                    }
                    btn.addEventListener('click', () => {
                        horarios.value = opt.value;
                        syncSummary();
                        renderHoraChips();
                    });
                    chipsWrap.appendChild(btn);
                });
            }

            const mo = new MutationObserver(() => {
                renderHoraChips();
                syncSummary();
            });
            mo.observe(horarios, { childList: true, subtree: true, attributes: true });

            servicio.addEventListener('change', syncSummary);
            fecha.addEventListener('change', syncSummary);
            horarios.addEventListener('change', () => {
                renderHoraChips();
                syncSummary();
            });

            renderHoraChips();
            syncSummary();
        })();
    </script>

</body>

</html>

