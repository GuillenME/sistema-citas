<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita (Recepción)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('{{ asset("imagenes/recepFon.png") }}');
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
        }

        header {
            background: rgba(100,100,100,.85);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            font-size: 32px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid red;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
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

        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(17,24,39,.85);
            backdrop-filter: blur(12px);
            padding: 28px;
            border-radius: 16px;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(280px, 1fr));
            gap: 16px 64px;
            align-items: start;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            justify-self: start;
            width: 100%;
            max-width: 460px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .anticipo-field {
            grid-column: 1 / 2;
        }

        .privacy-field {
            align-items: flex-start;
        }

        .field.center {
            align-items: center;
            justify-self: center;
        }

        .field.center > * {
            width: 100%;
            max-width: 460px;
        }

        .anticipo {
            margin-top: 6px;
            padding: 18px;
            background: rgba(255, 255, 255, .08);
            border-radius: 12px;
            max-width: 460px;
            text-align: center;
            font-size: 12px;
        }

        .anticipo h4 {
            color: #fde68a;
        }

        .anticipo p {
            line-height: 1.35;
        }

        .date-field {
            grid-row: span 2;
            position: relative;
        }

        label {
            margin-top: 15px;
            display: block;
            font-weight: bold;
            font-size: 14px;
        }

        .form-grid label {
            margin-top: 0;
        }

        .form-grid select,
        .form-grid input:not([type="checkbox"]) {
            width: 100%;
            max-width: 460px;
            padding: 10px;
            margin-top: 4px;
            margin-right: auto;
            margin-left: 0;
            border-radius: 8px;
            border: none;
        }

        input[type="date"] {
            background: #f4f4f6;
            color: #111;
            padding-right: 42px;
            position: relative;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%236b4a36' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='4' width='18' height='18' rx='2' ry='2'/><line x1='16' y1='2' x2='16' y2='6'/><line x1='8' y1='2' x2='8' y2='6'/><line x1='3' y1='10' x2='21' y2='10'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 18px 18px;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            color: transparent;
            cursor: pointer;
            width: 20px;
            height: 20px;
            margin-right: 6px;
        }

        .date-inline {
            background: #f4f4f6;
            color: #111;
            padding-right: 12px;
        }

        .flatpickr-calendar {
            background: linear-gradient(180deg, #5b3b2c 0%, #4b3226 100%);
            border: 1px solid rgba(252, 204, 124, 0.25);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
            border-radius: 14px;
            padding: 10px 8px;
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            width: min(360px, 100%);
            z-index: 20;
        }

        .flatpickr-months .flatpickr-month,
        .flatpickr-weekdays,
        .flatpickr-day,
        .flatpickr-current-month {
            color: #f3e8d9;
        }

        .flatpickr-day.today {
            border-color: rgba(252, 204, 124, 0.7);
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #fccc7c;
            border-color: #fccc7c;
            color: #111;
        }

        .flatpickr-current-month {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .flatpickr-weekdays {
            text-transform: uppercase;
            font-size: 11px;
            opacity: 0.85;
        }

        .flatpickr-day {
            border-radius: 10px;
            transition: background .15s, color .15s, transform .15s;
        }

        .flatpickr-day:hover {
            background: rgba(252, 204, 124, 0.2);
            transform: translateY(-1px);
        }

        .submit-btn {
            margin-top: 22px;
            width: 100%;
            max-width: 520px;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #7a7a7a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>

<header>
    <a href="{{ route('recepcionista.dashboard') }}" class="back-btn">←</a>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">Cerrar sesión</button>
    </form>
</header>

<div class="container">
    <div class="card">

        <h2>Agendar cita (Recepción)</h2>

        <form method="POST" action="{{ route('recepcionista.citas.store') }}" class="form-grid">
    @csrf

    <div class="field">
        <label>Cliente</label>
        <select name="usuario_id" required>
            <option value="">Selecciona un cliente</option>
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->id }}">
                    {{ $usuario->name }} {{ $usuario->last_name }} — {{ $usuario->email }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="field">
        <label>Servicio</label>
        <select name="servicio_id" id="servicio" required>
            <option value="">Selecciona un servicio</option>
            @foreach ($servicios as $servicio)
                @php
                    $promocion = $servicio->promocionActiva();
                    $precioFinal = $servicio->precioConDescuento();
                @endphp
                <option value="{{ $servicio->id }}">
                    {{ $servicio->name }} -
                    @if ($promocion)
                        <span style="text-decoration: line-through; opacity: 0.7;">${{ number_format($servicio->price, 2) }}</span>
                        <strong style="color: #22c55e;">${{ number_format($precioFinal, 2) }}</strong>
                    @else
                        ${{ number_format($servicio->price, 2) }}
                    @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="field date-field">
        <label>Fecha <small style="color: #ccc; font-weight: normal;">(Los domingos no están disponibles)</small></label>
            <input type="text" name="fecha" id="fecha" class="date-inline" placeholder="Selecciona una fecha" required onkeydown="return false;" readonly>
    </div>

    <div class="field">
        <label>Horario</label>
        <select name="hora_inicio" id="horarios" required>
            <option value="">Selecciona un horario</option>
        </select>
    </div>

    <div class="field anticipo-field">
        <label style="margin-top:18px;">
            <input type="checkbox" id="anticipo_check">
            Anticipo recibido
        </label>

        <div id="anticipo_box" style="display:none;">
            <label>Monto del anticipo</label>
            <input type="number" name="anticipo_monto" min="0" step="0.01" placeholder="Ej. 100">
        </div>
    </div>

    <div class="field full center">
        <button class="submit-btn">AGENDAR CITA</button>
    </div>
</form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
const servicio = document.getElementById('servicio');
const fecha = document.getElementById('fecha');
const horarios = document.getElementById('horarios');
const anticipoCheck = document.getElementById('anticipo_check');
const anticipoBox = document.getElementById('anticipo_box');

function formatHora12(hora24) {
    if (!hora24) return '';
    const partes = hora24.split(':');
    const h = parseInt(partes[0], 10);
    const m = partes[1] || '00';
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12 = ((h + 11) % 12) + 1;
    return `${h12}:${m} ${ampm}`;
}

anticipoCheck.addEventListener('change', () => {
    anticipoBox.style.display = anticipoCheck.checked ? 'block' : 'none';
});

// Función para verificar si una fecha es domingo
function esDomingo(fechaString) {
    if (!fechaString) return false;
    const fecha = new Date(fechaString + 'T00:00:00');
    return fecha.getDay() === 0; // 0 = domingo
}

// Bloquear domingos
fecha.addEventListener('input', () => {
    if (!fecha.value) return;
    if (esDomingo(fecha.value)) {
        alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
    }
});

fecha.addEventListener('change', () => {
    if (!fecha.value) return;
    if (esDomingo(fecha.value)) {
        alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        return;
    }
    cargarBloques();
});

async function cargarBloques() {
    if (!servicio.value || !fecha.value) return;

    horarios.innerHTML = '<option>Cargando...</option>';

    const res = await fetch(`/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`);
    const bloques = await res.json();

    horarios.innerHTML = '';

    if (bloques.length === 0) {
        horarios.innerHTML = '<option>No hay horarios</option>';
        return;
    }

    bloques.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.inicio;
        opt.textContent = `${formatHora12(b.inicio)} - ${formatHora12(b.fin)}`;
        horarios.appendChild(opt);
    });
}

servicio.addEventListener('change', cargarBloques);
// El listener 'change' de fecha ya está arriba con validación de domingos

if (window.flatpickr) {
    flatpickr(fecha, {
        inline: true,
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
        onChange: function () {
            if (!fecha.value) return;
            if (esDomingo(fecha.value)) {
                alert('⚠️ Los domingos no se atiende. Por favor selecciona otro día.');
                fecha.value = '';
                horarios.innerHTML = '<option value="">Selecciona un horario</option>';
                return;
            }
            cargarBloques();
        }
    });
} else {
    fecha.removeAttribute('readonly');
    fecha.type = 'date';
    fecha.min = new Date().toISOString().split('T')[0];
    fecha.addEventListener('focus', function () {
        if (fecha.showPicker) fecha.showPicker();
    });
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




