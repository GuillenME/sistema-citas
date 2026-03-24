<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/citas-create-enhancements.css') }}">
</head>

<body class="cliente-citas-create-page recepcionista-citas-create-page"
    data-anticipo-pct="{{ (float) config('citas.porcentaje_anticipo', 50) }}">

    @include('recepcionista.partials.menu')

    <div class="container">
        <div class="card booking-shell">

            <div class="booking-head">
                <h2>Agendar Cita</h2>
                <p>Registra una cita para cliente. Selecciona cliente, servicio, fecha y hora disponible.</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('recepcionista.citas.store') }}" id="formAgendarCita"
                class="form-grid booking-grid">
                @csrf

                <div class="booking-left">
                    <div class="field">
                        <label><span class="step-dot">1</span>Seleccionar Cliente</label>
                        <div class="searchable-select" id="clientDropdown">
                            <button type="button" class="searchable-select-trigger" id="clientDropdownTrigger"
                                aria-haspopup="listbox" aria-expanded="false">
                                <span id="clientDropdownLabel">Selecciona un cliente</span>
                            </button>
                            <div class="searchable-select-panel" id="clientDropdownPanel" hidden>
                                <input type="text" id="clientFilter" class="booking-filter-input searchable-select-input"
                                    placeholder="Buscar cliente">
                                <div class="searchable-select-options" id="clientDropdownOptions" role="listbox"></div>
                            </div>
                        </div>
                        <select name="usuario_id" id="usuario_id" class="sr-only-select" required>
                            <option value="">Selecciona un cliente</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} {{ $usuario->last_name }} - {{ $usuario->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label><span class="step-dot">2</span>Seleccionar Servicio</label>
                        <div class="searchable-select" id="serviceDropdown">
                            <button type="button" class="searchable-select-trigger" id="serviceDropdownTrigger"
                                aria-haspopup="listbox" aria-expanded="false">
                                <span id="serviceDropdownLabel">Selecciona un servicio</span>
                            </button>
                            <div class="searchable-select-panel" id="serviceDropdownPanel" hidden>
                                <input type="text" id="serviceFilter" class="booking-filter-input searchable-select-input"
                                    placeholder="Buscar servicio">
                                <div class="searchable-select-options" id="serviceDropdownOptions" role="listbox"></div>
                            </div>
                        </div>
                        <select name="servicio_id" id="servicio" class="sr-only-select" required>
                            <option value="">Selecciona un servicio</option>
                            @foreach ($servicios as $servicio)
                                @php
                                    $promo = $servicio->promocionActiva();
                                    $precioFinal = $servicio->precioConDescuento();
                                @endphp
                                <option value="{{ $servicio->id }}" data-precio="{{ $servicio->price }}"
                                    data-precio-descuento="{{ $precioFinal }}"
                                    data-tiene-promocion="{{ $promo ? '1' : '0' }}"
                                    data-descripcion="{{ $servicio->description }}"
                                    data-duracion="{{ $servicio->duration_minutes }}"
                                    data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                                    {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                    {{ $servicio->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field date-field">
                        <label><span class="step-dot">3</span>Seleccionar Fecha</label>
                        <input type="text" id="fecha" name="fecha" class="date-inline"
                            placeholder="Selecciona una fecha" value="{{ old('fecha') }}" onkeydown="return false;" readonly required>
                    </div>

                    <div class="field horario-field">
                        <label><span class="step-dot">4</span>Seleccionar Hora</label>
                        <div id="horarioChips" class="horario-chips"></div>
                        <select id="horarios" name="hora_inicio" class="sr-only-select" required>
                            <option value="">Selecciona un horario</option>
                        </select>
                        <input type="hidden" id="horaInicioOld" value="{{ old('hora_inicio') }}">
                    </div>
                </div>

                <div class="field anticipo-field booking-summary">
                    <h4>Resumen de cita <span class="summary-pill">Recepción</span></h4>
                    <div class="summary-item">
                        <span>Cliente</span>
                        <strong id="summaryCliente">-</strong>
                    </div>
                    <div class="summary-item">
                        <span>Servicio</span>
                        <strong id="summaryService">-</strong>
                    </div>
                    <div class="summary-item">
                        <span>Fecha y Hora</span>
                        <strong id="summaryDateTime">-</strong>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-amount">
                        <span>Anticipo sugerido ({{ (float) config('citas.porcentaje_anticipo', 50) }}%)</span>
                        <strong id="summaryAnticipo">$0.00</strong>
                    </div>
                    <div class="field">
                        <label class="privacy-label">
                            <input type="checkbox" id="anticipo_check" name="anticipo_recibido" value="1"
                                class="privacy-checkbox" required {{ old('anticipo_recibido') ? 'checked' : '' }}>
                            <span class="privacy-text">Se recibio anticipo en recepcion</span>
                        </label>
                    </div>
                    <div class="field" id="anticipo_box" hidden>
                        <label for="anticipo_monto">Monto recibido</label>
                        <input type="number" id="anticipo_monto" name="anticipo_monto" min="0.01" step="0.01"
                            required disabled placeholder="Ej. 100.00" value="{{ old('anticipo_monto') }}">
                    </div>

                    <div class="field summary-submit">
                        <button id="btnAgendar" type="button" class="submit-btn" onclick="mostrarModalConfirmar()">
                            AGENDAR CITA
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalServicioConfirmar" class="modal-confirm-overlay"
        onclick="if(event.target === this) cancelarServicio()">
        <div class="modal-confirm-content modal-servicio">
            <img id="msImagen" class="modal-servicio-img" src="" alt="Servicio">
            <h3 id="msNombre"></h3>
            <p id="msDescripcion" class="modal-servicio-desc"></p>
            <p class="modal-servicio-info">
                <strong>Duracion:</strong> <span id="msDuracion"></span> minutos
            </p>
            <p class="modal-servicio-info">
                <strong>Precio:</strong> <span id="msPrecio"></span>
            </p>
            <div class="modal-confirm-buttons">
                <button class="modal-confirm-btn modal-confirm-btn-submit" onclick="confirmarServicio()">Confirmar
                    servicio</button>
                <button class="modal-confirm-btn modal-confirm-btn-cancel" onclick="cancelarServicio()">Cambiar
                    servicio</button>
            </div>
        </div>
    </div>

    <div id="modalConfirmar" class="modal-confirm-overlay"
        onclick="if(event.target === this) cerrarModalConfirmar()">
        <div class="modal-confirm-content">
            <h3>Confirmar cita</h3>
            <p><strong>Cliente:</strong> <span id="mcCliente"></span></p>
            <p><strong>Servicio:</strong> <span id="mcServicio"></span></p>
            <p><strong>Fecha:</strong> <span id="mcFecha"></span></p>
            <p><strong>Horario:</strong> <span id="mcHorario"></span></p>
            <hr class="modal-separator">
            <p class="modal-warning-note">
                Si registras anticipo, la cita se guardara como confirmada.
            </p>
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

    <div id="warningToast" class="warning-toast" role="alert" aria-live="assertive">
        <div class="warning-toast-head">
            <p class="warning-toast-title">Atención requerida</p>
            <button type="button" class="warning-toast-close" onclick="cerrarWarningToast()">x</button>
        </div>
        <div class="warning-toast-body" id="warningToastText"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="{{ asset('js/recepcionista/citas-create.js') }}"></script>

</body>

</html>
