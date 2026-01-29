<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
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


            <button type="button" class="submit-btn" onclick="mostrarModalConfirmar()">
                AGENDAR CITA
            </button>
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
