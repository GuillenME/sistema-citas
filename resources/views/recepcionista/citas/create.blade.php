<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita (Recepcion)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-create.css') }}">
</head>

<body class="recepcionista-citas-create-page">

@include('recepcionista.partials.menu')

<div class="container">
    <div class="card">

        <h2>Agendar cita (Recepcion)</h2>

        <form method="POST" action="{{ route('recepcionista.citas.store') }}" class="form-grid">
            @csrf

            <div class="field">
                <label>Cliente</label>
                <select name="usuario_id" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">
                            {{ $usuario->name }} {{ $usuario->last_name }} - {{ $usuario->email }}
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
                            @if ($promocion)
                                {{ $servicio->name }} - Antes ${{ number_format($servicio->price, 2) }} / Ahora ${{ number_format($precioFinal, 2) }}
                            @else
                                {{ $servicio->name }} - ${{ number_format($servicio->price, 2) }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field date-field">
                <label>Fecha <small class="help-text">(Los domingos no estan disponibles)</small></label>
                <input type="text" name="fecha" id="fecha" class="date-inline" placeholder="Selecciona una fecha" required onkeydown="return false;" readonly>
            </div>

            <div class="field">
                <label>Horario</label>
                <select name="hora_inicio" id="horarios" required>
                    <option value="">Selecciona un horario</option>
                </select>
            </div>

            <div class="field anticipo-field">
                <label class="anticipo-label">
                    <input type="checkbox" id="anticipo_check">
                    Recibio anticipo?
                </label>

                <div id="anticipo_box" class="is-hidden">
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
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
    anticipoBox.classList.toggle('is-hidden', !anticipoCheck.checked);
});

function esDomingo(fechaString) {
    if (!fechaString) return false;
    const fecha = new Date(fechaString + 'T00:00:00');
    return fecha.getDay() === 0;
}

fecha.addEventListener('input', () => {
    if (!fecha.value) return;
    if (esDomingo(fecha.value)) {
        alert('Los domingos no se atiende. Por favor selecciona otro dia.');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
    }
});

fecha.addEventListener('change', () => {
    if (!fecha.value) return;
    if (esDomingo(fecha.value)) {
        alert('Los domingos no se atiende. Por favor selecciona otro dia.');
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

if (window.flatpickr) {
    flatpickr(fecha, {
        inline: true,
        locale: "es",
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
        onChange: function () {
            if (!fecha.value) return;
            if (esDomingo(fecha.value)) {
                alert('Los domingos no se atiende. Por favor selecciona otro dia.');
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

<!-- Modal de confirmacion de logout -->
<div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
    <div class="modal-content">
        <h3>¿Cerrar sesion?</h3>
        <p>¿Estas seguro de que deseas cerrar sesion?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Si, cerrar sesion</button>
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
        </div>
    </div>
</div>

</body>
</html>

