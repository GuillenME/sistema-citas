<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('{{ asset("imagenes/RegistrarSala.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 0;
            pointer-events: none;
        }

        header, .container {
            position: relative;
            z-index: 1;
        }

        header {
            background: rgba(115,114,126,.85);
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            font-size: 38px;
            color: #fff;
            text-decoration: none;
            text-shadow: 0 0 10px rgba(255,255,255,.8);
            transition: .2s;
        }

        .back-btn:hover { transform: scale(1.2); }

        .logout-btn {
            background: transparent;
            border: 2px solid #ff0000;
            color: #fff;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 0 14px rgba(255,0,0,1);
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
            box-shadow: 0 0 25px rgba(255, 0, 0, 0.6);
            border: 2px solid rgba(255, 0, 0, 0.5);
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

        /* Modal de confirmación de agendar cita */
        .modal-confirm-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 20px;
        }

        .modal-confirm-overlay.active {
            display: flex;
        }

        .modal-confirm-content {
            background: rgba(17, 24, 39, 0.98);
            padding: 35px;
            border-radius: 16px;
            max-width: 900px;
            width: 90%;
            color: #fff;
            box-shadow: 0 0 30px rgba(42, 22, 218, 0.8);
            border: 2px solid rgba(42, 22, 218, 0.5);
            margin: auto;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-confirm-content h3 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #fff;
            text-align: center;
            text-shadow: 0 0 10px rgba(42, 22, 218, 0.8);
        }

        .modal-confirm-info {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-confirm-info-item {
            margin-bottom: 12px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
        }

        .modal-confirm-info-item:last-child {
            margin-bottom: 0;
        }

        .modal-confirm-info-label {
            color: #93c5fd;
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }

        .modal-confirm-info-value {
            color: #e5e7eb;
        }

        .modal-confirm-payment {
            background: rgba(234, 179, 8, 0.15);
            border: 2px solid rgba(234, 179, 8, 0.5);
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .modal-confirm-payment h4 {
            color: #fde68a;
            margin-bottom: 12px;
            font-size: 16px;
            text-align: center;
        }

        .modal-confirm-payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .modal-confirm-payment-item:last-child {
            margin-bottom: 0;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-confirm-payment-label {
            color: #e5e7eb;
        }

        .modal-confirm-payment-value {
            color: #fde68a;
            font-weight: bold;
        }

        .modal-confirm-payment-restante {
            color: #93c5fd;
        }

        .modal-confirm-bank {
            background: rgba(42, 22, 218, 0.2);
            border: 2px solid rgba(42, 22, 218, 0.4);
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .modal-confirm-bank h4 {
            color: #93c5fd;
            margin-bottom: 12px;
            font-size: 15px;
            text-align: center;
        }

        .modal-confirm-bank-data {
            color: #e5e7eb;
            font-size: 14px;
            line-height: 1.8;
            text-align: center;
        }

        .modal-confirm-bank-data strong {
            color: #fde68a;
        }

        .modal-confirm-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-confirm-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            transition: transform .2s, box-shadow .2s;
        }

        .modal-confirm-btn:hover {
            transform: scale(1.05);
        }

        .modal-confirm-btn-submit {
            background: #22c55e;
            color: #fff;
            box-shadow: 0 0 14px rgba(34, 197, 94, 0.7);
        }

        .modal-confirm-btn-submit:hover {
            box-shadow: 0 0 20px rgba(34, 197, 94, 1);
        }

        .modal-confirm-btn-cancel {
            background: #6b7280;
            color: #fff;
        }

        /* Responsive para el modal */
        @media (max-width: 768px) {
            .modal-confirm-overlay {
                padding: 10px;
                align-items: flex-start;
                padding-top: 20px;
            }

            .modal-confirm-content {
                max-width: 100%;
                width: 100%;
                padding: 25px 20px;
                margin: 0;
                max-height: 95vh;
                border-radius: 12px;
            }

            .modal-confirm-content h3 {
                font-size: 20px;
                margin-bottom: 15px;
            }

            .modal-confirm-info {
                padding: 15px;
            }

            .modal-confirm-info-item {
                font-size: 13px;
                margin-bottom: 10px;
                flex-direction: column;
                gap: 4px;
            }

            .modal-confirm-info-label {
                min-width: auto;
                margin-bottom: 0;
                font-size: 12px;
                width: 100%;
            }

            .modal-confirm-info-value {
                font-size: 13px;
                word-break: break-word;
            }

            .modal-confirm-payment {
                padding: 15px;
            }

            .modal-confirm-payment h4 {
                font-size: 14px;
            }

            .modal-confirm-payment-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
                font-size: 13px;
            }

            .modal-confirm-payment-item:last-child {
                align-items: center;
            }

            .modal-confirm-bank {
                padding: 15px;
            }

            .modal-confirm-bank h4 {
                font-size: 14px;
            }

            .modal-confirm-bank-data {
                font-size: 13px;
                line-height: 1.6;
            }

            .modal-confirm-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .modal-confirm-btn {
                width: 100%;
                padding: 14px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .modal-confirm-overlay {
                padding: 5px;
                padding-top: 10px;
            }

            .modal-confirm-content {
                padding: 20px 15px;
                max-width: 100%;
                width: 100%;
                border-radius: 10px;
                max-height: 98vh;
            }

            .modal-confirm-content h3 {
                font-size: 18px;
            }

            .modal-confirm-info {
                padding: 12px;
            }

            .modal-confirm-info-item {
                font-size: 12px;
                gap: 3px;
            }

            .modal-confirm-info-label {
                font-size: 11px;
                width: 100%;
            }

            .modal-confirm-info-value {
                font-size: 12px;
            }

            .modal-confirm-payment {
                padding: 12px;
            }

            .modal-confirm-payment h4 {
                font-size: 13px;
            }

            .modal-confirm-payment-item {
                font-size: 12px;
                gap: 3px;
            }

            .modal-confirm-payment-item:last-child {
                align-items: center;
            }

            .modal-confirm-bank {
                padding: 12px;
            }

            .modal-confirm-bank h4 {
                font-size: 13px;
            }

            .modal-confirm-bank-data {
                font-size: 12px;
            }

            .modal-confirm-btn {
                padding: 12px;
                font-size: 13px;
            }
        }

        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(17,24,39,.85);
            padding: 28px;
            border-radius: 16px;
            color: #fff;
            box-shadow: 0 0 25px rgba(42,22,218,.6);
        }

        h2 { text-align: center; }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        /* ===== ERRORES ===== */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error-box ul { margin: 0; padding-left: 18px; }

        .input-error {
            outline: 2px solid #ef4444 !important;
            background: #fee2e2;
        }

        .field-error {
            display: block;
            margin-top: 4px;
            font-size: 13px;
            color: #fecaca;
        }

        .submit-btn {
            margin-top: 22px;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background: #1F4E79;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
            box-shadow: 0 6px 20px rgba(42,22,218,.8);
        }
        /* ===== PRIVACIDAD ===== */
        .privacy-box {
            margin-top: 18px;
            width: 100%;
        }

        .privacy-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            justify-content: flex-start;
            text-align: left;
        }

        .privacy-checkbox {
            margin-top: 3px;
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        .privacy-text {
            font-size: 13px;
            line-height: 1.4;
            color: #e5e7eb;
        }

        .privacy-link {
            color: #93c5fd;
            text-decoration: underline;
        }

        .privacy-link:hover {
            color: #bfdbfe;
        }

        .anticipo {
            margin-top: 25px;
            padding: 18px;
            background: rgba(255,255,255,.08);
            border-radius: 12px;
            text-align: center;
            font-size: 14px;
        }

        .anticipo h4 { color: #fde68a; }

        @media (max-width: 600px) {
            header { flex-direction: column; }
            .back-btn { font-size: 32px; }
        }
    </style>
</head>

<body>

<header>
    <a href="{{ route('cliente.dashboard') }}" class="back-btn">←</a>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">Cerrar sesión</button>
    </form>
</header>

<div class="container">
    <div class="card">

        <h2>Agendar cita</h2>

        {{-- ERRORES GENERALES --}}
        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cliente.citas.store') }}">
            @csrf

            <label>Servicio</label>
            <select name="servicio_id" id="servicio" class="@error('servicio_id') input-error @enderror">
                <option value="">Selecciona un servicio</option>
                @foreach ($servicios as $servicio)
                    @php
                        $promocion = $servicio->promocionActiva();
                        $precioFinal = $servicio->precioConDescuento();
                    @endphp
                    <option value="{{ $servicio->id }}"
                            data-precio="{{ $servicio->price }}"
                            data-precio-descuento="{{ $precioFinal }}"
                            data-tiene-promocion="{{ $promocion ? '1' : '0' }}"
                            {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->name }} -
                        @if($promocion)
                            <span style="text-decoration: line-through; opacity: 0.7;">${{ number_format($servicio->price, 2) }}</span>
                            <strong style="color: #22c55e;">${{ number_format($precioFinal, 2) }}</strong>
                            <span style="color: #fbbf24;">({{ $promocion->discount }}% OFF)</span>
                        @else
                            ${{ number_format($servicio->price, 2) }}
                        @endif
                    </option>
                @endforeach
            </select>
            <div id="info-precio" style="margin-top: 8px; font-size: 13px; color: #93c5fd; display: none;">
                <span id="texto-precio"></span>
            </div>
            @error('servicio_id')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label>Fecha <small style="color: #9ca3af; font-weight: normal;">(Los domingos no están disponibles)</small></label>
            <input type="date" name="fecha" id="fecha"
                   min="{{ now()->toDateString() }}"
                   value="{{ old('fecha') }}"
                   class="@error('fecha') input-error @enderror"
                   onkeydown="return false;">
            @error('fecha')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label>Horario</label>
            <select name="hora_inicio" id="horarios" class="@error('hora_inicio') input-error @enderror">
                <option value="">Selecciona un horario</option>
            </select>
            @error('hora_inicio')
                <span class="field-error">{{ $message }}</span>
            @enderror

           {{-- POLÍTICA DE PRIVACIDAD --}}
        <div class="privacy-box">
            <label class="privacy-label">
                <input type="checkbox"
                    name="acepta_privacidad"
                    value="1"
                    class="privacy-checkbox @error('acepta_privacidad') input-error @enderror"
                    {{ old('acepta_privacidad') ? 'checked' : '' }}>

                <span class="privacy-text">
                    Acepto la
                    <a href="{{ route('politica.privacidad') }}" target="_blank" class="privacy-link">
                        Política de privacidad
                    </a>
                    y autorizo el uso de mis datos para la gestión de mi cita.
                </span>
            </label>

            @error('acepta_privacidad')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>


            <div class="submit-wrapper">
        <button type="button" class="submit-btn" onclick="mostrarModalConfirmar()">
            AGENDAR CITA
        </button>
    </div>
        </form>

        <div class="anticipo">
            <h4>⚠ Anticipo requerido</h4>
            <p>Se solicita un <strong>{{ $porcentajeAnticipo }}%</strong> para confirmar la cita</p>
            <p>El <strong>{{ $porcentajeRestante }}%</strong> restante se pagará después de la cita</p>
            <p>Banco: {{ config('citas.banco.nombre') }}<br>Cuenta: {{ config('citas.banco.cuenta') }}<br>CLABE: {{ config('citas.banco.clabe') }}</p>
        </div>

    </div>
</div>

<script>
const servicio = document.getElementById('servicio');
const fecha = document.getElementById('fecha');
const horarios = document.getElementById('horarios');
const infoPrecio = document.getElementById('info-precio');
const textoPrecio = document.getElementById('texto-precio');

// Mostrar información de precio al seleccionar servicio
servicio.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.value && option.dataset.precio) {
        const precio = parseFloat(option.dataset.precio);
        const precioDescuento = parseFloat(option.dataset.precioDescuento);
        const tienePromocion = option.dataset.tienePromocion === '1';

        if (tienePromocion) {
            textoPrecio.innerHTML = `
                <span style="text-decoration: line-through; opacity: 0.7;">Precio original: $${precio.toFixed(2)}</span><br>
                <strong style="color: #22c55e;">Precio con descuento: $${precioDescuento.toFixed(2)}</strong>
            `;
        } else {
            textoPrecio.innerHTML = `<strong>Precio: $${precio.toFixed(2)}</strong>`;
        }
        infoPrecio.style.display = 'block';
    } else {
        infoPrecio.style.display = 'none';
    }
    cargarBloques();
});

// Función para verificar si una fecha es domingo
function esDomingo(fechaString) {
    if (!fechaString) return false;
    const fecha = new Date(fechaString + 'T00:00:00');
    return fecha.getDay() === 0; // 0 = domingo
}

// Función para obtener el siguiente día disponible (no domingo)
function obtenerSiguienteDiaDisponible(fechaString) {
    if (!fechaString) return null;
    let fecha = new Date(fechaString + 'T00:00:00');
    fecha.setDate(fecha.getDate() + 1);

    // Buscar el siguiente día que no sea domingo
    while (fecha.getDay() === 0) {
        fecha.setDate(fecha.getDate() + 1);
    }

    return fecha.toISOString().split('T')[0];
}

// Bloquear domingos - validación mejorada
fecha.addEventListener('input', () => {
    if (!fecha.value) return;

    if (esDomingo(fecha.value)) {
        alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        return;
    }
});

// Validar al cambiar la fecha también
fecha.addEventListener('change', () => {
    if (!fecha.value) return;

    if (esDomingo(fecha.value)) {
        alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        return;
    }

    // Si no es domingo, cargar los bloques
    cargarBloques();
});

// Prevenir pegado de fechas que sean domingos
fecha.addEventListener('paste', (e) => {
    setTimeout(() => {
        if (fecha.value && esDomingo(fecha.value)) {
            alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
            fecha.value = '';
            horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        }
    }, 10);
});

async function cargarBloques() {
    horarios.innerHTML = '<option>Cargando horarios...</option>';

    if (!servicio.value || !fecha.value) return;

    try {
        const res = await fetch(
            `/cliente/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`
        );

        const bloques = await res.json();
        horarios.innerHTML = '';

        if (bloques.length === 0) {
            horarios.innerHTML = '<option>No hay horarios disponibles</option>';
            return;
        }

        bloques.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.inicio;
            opt.textContent = `${b.inicio} - ${b.fin}`;
            horarios.appendChild(opt);
        });
    } catch {
        horarios.innerHTML = '<option>Error al cargar horarios</option>';
    }
}

// La validación de domingos ya está en el listener 'change' de arriba

// Mostrar precio al cargar si hay un valor seleccionado
if (servicio.value) {
    servicio.dispatchEvent(new Event('change'));
}

// Variables globales para el modal de confirmación
const porcentajeAnticipo = {{ $porcentajeAnticipo }};
const porcentajeRestante = {{ $porcentajeRestante }};

// Modal de confirmación de agendar cita
function mostrarModalConfirmar() {
    // Validar que todos los campos estén llenos
    if (!servicio.value || !fecha.value || !horarios.value) {
        alert('Por favor completa todos los campos antes de agendar la cita');
        return;
    }

    // Validar que no sea domingo
    if (esDomingo(fecha.value)) {
        alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        return;
    }

    // Validar que se haya aceptado la política de privacidad
    const aceptaPrivacidad = document.querySelector('input[name="acepta_privacidad"]');
    if (!aceptaPrivacidad.checked) {
        alert('Debes aceptar la política de privacidad para continuar');
        return;
    }

    // Obtener información del servicio seleccionado
    const option = servicio.options[servicio.selectedIndex];
    const nombreServicio = option.textContent.split(' - ')[0];
    const precioOriginal = parseFloat(option.dataset.precio);
    const precioDescuento = parseFloat(option.dataset.precioDescuento);
    const tienePromocion = option.dataset.tienePromocion === '1';
    const precioFinal = tienePromocion ? precioDescuento : precioOriginal;

    // Calcular montos
    const anticipo = precioFinal * (porcentajeAnticipo / 100);
    const restante = precioFinal - anticipo;

    // Obtener fecha y horario
    const fechaSeleccionada = new Date(fecha.value).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const horarioSeleccionado = horarios.options[horarios.selectedIndex].textContent;

    // Llenar el modal con la información
    document.getElementById('modalServicio').textContent = nombreServicio;
    document.getElementById('modalFecha').textContent = fechaSeleccionada;
    document.getElementById('modalHorario').textContent = horarioSeleccionado;

    if (tienePromocion) {
        document.getElementById('modalPrecio').innerHTML = `
            <span style="text-decoration: line-through; opacity: 0.7; margin-right: 8px;">
                $${precioOriginal.toFixed(2)}
            </span>
            <strong style="color: #22c55e;">$${precioFinal.toFixed(2)}</strong>
        `;
    } else {
        document.getElementById('modalPrecio').textContent = `$${precioFinal.toFixed(2)}`;
    }

    document.getElementById('modalAnticipo').textContent = `$${anticipo.toFixed(2)}`;
    document.getElementById('modalRestante').textContent = `$${restante.toFixed(2)}`;

    // Mostrar el modal
    document.getElementById('modalConfirmar').classList.add('active');
}

function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').classList.remove('active');
}

function confirmarAgendar() {
    // Enviar el formulario
    document.querySelector('form[action="{{ route('cliente.citas.store') }}"]').submit();
}

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

<!-- Modal de confirmación de agendar cita -->
<div id="modalConfirmar" class="modal-confirm-overlay" onclick="if(event.target === this) cerrarModalConfirmar()">
    <div class="modal-confirm-content">
        <h3>📅 Confirmar Cita</h3>

        <div class="modal-confirm-info">
            <div class="modal-confirm-info-item">
                <span class="modal-confirm-info-label">Servicio:</span>
                <span class="modal-confirm-info-value" id="modalServicio"></span>
            </div>
            <div class="modal-confirm-info-item">
                <span class="modal-confirm-info-label">Fecha:</span>
                <span class="modal-confirm-info-value" id="modalFecha"></span>
            </div>
            <div class="modal-confirm-info-item">
                <span class="modal-confirm-info-label">Horario:</span>
                <span class="modal-confirm-info-value" id="modalHorario"></span>
            </div>
            <div class="modal-confirm-info-item">
                <span class="modal-confirm-info-label">Precio total:</span>
                <span class="modal-confirm-info-value" id="modalPrecio"></span>
            </div>
        </div>

        <div class="modal-confirm-payment">
            <h4>⚠ Información de Pago</h4>
            <div class="modal-confirm-payment-item">
                <span class="modal-confirm-payment-label">Anticipo requerido ({{ $porcentajeAnticipo }}%):</span>
                <span class="modal-confirm-payment-value" id="modalAnticipo"></span>
            </div>
            <div class="modal-confirm-payment-item">
                <span class="modal-confirm-payment-label">Restante a pagar ({{ $porcentajeRestante }}%):</span>
                <span class="modal-confirm-payment-value modal-confirm-payment-restante" id="modalRestante"></span>
            </div>
            <div class="modal-confirm-payment-item">
                <small style="color: #9ca3af; font-size: 12px; display: block; text-align: center; margin-top: 8px;">
                    El {{ $porcentajeRestante }}% restante se pagará después de la cita
                </small>
            </div>
        </div>

        <div class="modal-confirm-bank">
            <h4>💳 Datos Bancarios para el Anticipo</h4>
            <div class="modal-confirm-bank-data">
                <strong>Banco:</strong> {{ config('citas.banco.nombre') }}<br>
                <strong>Cuenta:</strong> {{ config('citas.banco.cuenta') }}<br>
                <strong>CLABE:</strong> {{ config('citas.banco.clabe') }}
            </div>
        </div>

        <div class="modal-confirm-buttons">
            <button class="modal-confirm-btn modal-confirm-btn-submit" onclick="confirmarAgendar()">
                ✓ Confirmar y Agendar
            </button>
            <button class="modal-confirm-btn modal-confirm-btn-cancel" onclick="cerrarModalConfirmar()">
                Cancelar
            </button>
        </div>
    </div>
</div>

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
