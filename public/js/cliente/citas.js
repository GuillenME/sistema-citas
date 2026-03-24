const servicio = document.getElementById('servicio');
const fecha = document.getElementById('fecha');
const horarios = document.getElementById('horarios');
const fechaInput = document.getElementById('fecha');
const dateField = document.querySelector('.date-field');
const privacidadCheckbox = document.querySelector('input[name="acepta_privacidad"]');
const horaInicioOld = document.getElementById('horaInicioOld');
const serviceDropdown = document.getElementById('serviceDropdown');
const serviceTrigger = document.getElementById('serviceDropdownTrigger');
const serviceLabel = document.getElementById('serviceDropdownLabel');
const servicePanel = document.getElementById('serviceDropdownPanel');
const serviceFilter = document.getElementById('serviceFilter');
const serviceOptionsWrap = document.getElementById('serviceDropdownOptions');
const serviciosBase = servicio
    ? Array.from(servicio.options)
        .filter((opt) => opt.value)
        .map((opt) => opt.cloneNode(true))
    : [];

let servicioConfirmado = false;
let serviciosDisponiblesFecha = [];

function normalizarTexto(texto) {
    return String(texto || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim()
        .toLowerCase();
}

function setServiceDropdownOpen(open) {
    if (!servicePanel || !serviceTrigger) return;
    servicePanel.hidden = !open;
    serviceTrigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open && serviceFilter) {
        window.setTimeout(() => serviceFilter.focus(), 0);
    }
}

function syncServiceLabel() {
    if (!serviceLabel) return;
    const opt = servicio?.options?.[servicio.selectedIndex];
    serviceLabel.textContent = opt && opt.value ? opt.textContent.trim() : 'Selecciona un servicio';
}

function getFilteredServices() {
    const termino = normalizarTexto(serviceFilter?.value || '');

    return serviciosBase.filter((opt) => {
        const coincideTexto = termino === '' || normalizarTexto(opt.textContent).includes(termino);
        const coincideDisponibilidad = serviciosDisponiblesFecha.length === 0 ||
            serviciosDisponiblesFecha.includes(String(opt.value));

        return coincideTexto && coincideDisponibilidad;
    });
}

function syncServiceSelect(filteredOptions) {
    const selectedValue = servicio.value;
    servicio.innerHTML = '<option value="">Selecciona un servicio</option>';

    filteredOptions.forEach((opt) => {
        const clone = opt.cloneNode(true);
        if (String(clone.value) === String(selectedValue)) {
            clone.selected = true;
        }
        servicio.appendChild(clone);
    });

    if (selectedValue && String(servicio.value) !== String(selectedValue)) {
        servicio.value = '';
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        servicioConfirmado = false;
    }
}

function renderServiceOptions() {
    if (!serviceOptionsWrap) return;

    const filtered = getFilteredServices();
    syncServiceSelect(filtered);
    serviceOptionsWrap.innerHTML = '';

    const selectedValue = servicio.value;

    if (!filtered.length) {
        const empty = document.createElement('div');
        empty.className = 'searchable-select-empty';
        empty.textContent = 'No se encontraron servicios.';
        serviceOptionsWrap.appendChild(empty);
        return;
    }

    const placeholder = document.createElement('button');
    placeholder.type = 'button';
    placeholder.className = 'searchable-select-option';
    placeholder.textContent = 'Selecciona un servicio';
    if (!selectedValue) {
        placeholder.classList.add('is-selected');
    }
    placeholder.addEventListener('click', () => {
        servicio.value = '';
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        servicioConfirmado = false;
        syncServiceLabel();
        setServiceDropdownOpen(false);
    });
    serviceOptionsWrap.appendChild(placeholder);

    filtered.forEach((opt) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'searchable-select-option';
        button.textContent = opt.textContent.trim();
        if (String(opt.value) === String(selectedValue)) {
            button.classList.add('is-selected');
        }
        button.addEventListener('click', () => {
            servicio.value = opt.value;
            syncServiceLabel();
            setServiceDropdownOpen(false);
            servicio.dispatchEvent(new Event('change'));
        });
        serviceOptionsWrap.appendChild(button);
    });
}

function applyServiceFilter() {
    renderServiceOptions();
    syncServiceLabel();
}

function formatHora12(hora24) {
    if (!hora24) return '';
    const partes = hora24.split(':');
    const h = parseInt(partes[0], 10);
    const m = partes[1] || '00';
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12 = ((h + 11) % 12) + 1;
    return `${h12}:${m} ${ampm}`;
}

servicio.addEventListener('change', function () {
    if (!this.value) {
        syncServiceLabel();
        renderServiceOptions();
        return;
    }

    servicioConfirmado = false;
    const opt = this.options[this.selectedIndex];

    document.getElementById('msNombre').textContent = opt.textContent;

    const img = document.getElementById('msImagen');
    img.src = opt.dataset.imagen;
    img.style.display = 'block';

    document.getElementById('msDescripcion').textContent =
        opt.dataset.descripcion || 'Sin descripcion disponible';

    document.getElementById('msDuracion').textContent = opt.dataset.duracion;

    if (opt.dataset.tienePromocion === '1') {
        document.getElementById('msPrecio').innerHTML = `
            <span style="text-decoration: line-through; opacity:.6">
                $${parseFloat(opt.dataset.precio).toFixed(2)}
            </span>
            <strong style="color:#22c55e; margin-left:6px">
                $${parseFloat(opt.dataset.precioDescuento).toFixed(2)}
            </strong>
        `;
    } else {
        document.getElementById('msPrecio').textContent = `$${parseFloat(opt.dataset.precio).toFixed(2)}`;
    }

    syncServiceLabel();
    renderServiceOptions();
    document.getElementById('modalServicioConfirmar').classList.add('active');
});

function confirmarServicio() {
    servicioConfirmado = true;
    document.getElementById('modalServicioConfirmar').classList.remove('active');
    if (fecha.value) {
        cargarHorarios();
    }
}

function cancelarServicio() {
    servicio.value = '';
    fecha.value = '';
    horarios.innerHTML = '<option value="">Selecciona un horario</option>';
    servicioConfirmado = false;
    if (serviceFilter) {
        serviceFilter.value = '';
    }
    applyServiceFilter();
    document.getElementById('modalServicioConfirmar').classList.remove('active');
}

async function cargarHorarios() {
    if (!servicioConfirmado || !fecha.value) return;

    const res = await fetch(`/cliente/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`);
    const data = await res.json();

    horarios.innerHTML = '';
    data.forEach((h) => {
        const opt = document.createElement('option');
        opt.value = h.inicio;
        opt.textContent = `${formatHora12(h.inicio)} - ${formatHora12(h.fin)}`;
        horarios.appendChild(opt);
    });

    if (!Array.isArray(data) || !data.length) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'No hay horarios disponibles para esa fecha (horario laboral, comida o cupo lleno).';
        horarios.appendChild(opt);
    }

    if (horaInicioOld && horaInicioOld.value) {
        const existeHorarioOld = Array.from(horarios.options).some((o) => o.value === horaInicioOld.value);
        if (existeHorarioOld) {
            horarios.value = horaInicioOld.value;
        }
    }

    horarios.dispatchEvent(new Event('change'));
}

async function actualizarServiciosDisponibles() {
    if (!fecha.value) return;

    try {
        const res = await fetch(`/cliente/citas/servicios-disponibles?fecha=${encodeURIComponent(fecha.value)}`);
        const data = await res.json();
        serviciosDisponiblesFecha = Array.isArray(data) ? data.map((v) => String(v)) : [];
    } catch (e) {
        serviciosDisponiblesFecha = [];
    }

    applyServiceFilter();
}

if (window.flatpickr && fechaInput) {
    flatpickr(fechaInput, {
        inline: true,
        appendTo: dateField || undefined,
        locale: 'es',
        dateFormat: 'Y-m-d',
        firstDayOfWeek: 1,
        minDate: 'today',
        disableMobile: true,
        onChange: function () {
            actualizarServiciosDisponibles().then(cargarHorarios);
        }
    });
} else if (fechaInput) {
    fechaInput.removeAttribute('readonly');
    fechaInput.type = 'date';
    fechaInput.min = new Date().toISOString().split('T')[0];
    fechaInput.addEventListener('focus', function () {
        if (fechaInput.showPicker) fechaInput.showPicker();
    });
    fechaInput.addEventListener('change', function () {
        actualizarServiciosDisponibles().then(cargarHorarios);
    });
} else {
    fecha.addEventListener('change', function () {
        actualizarServiciosDisponibles().then(cargarHorarios);
    });
}

function mostrarModalConfirmar() {
    if (privacidadCheckbox && !privacidadCheckbox.checked) {
        let errorNode = document.getElementById('privacyInlineError');
        if (!errorNode) {
            errorNode = document.createElement('div');
            errorNode.id = 'privacyInlineError';
            errorNode.className = 'error-text';
            const privacyContainer = privacidadCheckbox.closest('.summary-privacy');
            (privacyContainer || privacidadCheckbox.parentElement || document.body).appendChild(errorNode);
        }
        errorNode.textContent = 'Debes aceptar la politica de privacidad.';
        return;
    }

    if (!servicioConfirmado || !fecha.value || !horarios.value) {
        alert('Completa y confirma todo primero');
        return;
    }

    const selectedOption = servicio.options[servicio.selectedIndex];
    document.getElementById('mcServicio').textContent = selectedOption.textContent;
    document.getElementById('mcFecha').textContent = fecha.value;
    document.getElementById('mcHorario').textContent = horarios.options[horarios.selectedIndex].textContent;
    document.getElementById('mcDuracion').textContent = `${selectedOption.dataset.duracion || '-'} minutos`;

    document.getElementById('modalConfirmar').classList.add('active');
}

function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').classList.remove('active');
}

function confirmarAgendar() {
    document.getElementById('formAgendarCita').submit();
}

if (privacidadCheckbox) {
    privacidadCheckbox.addEventListener('change', function () {
        if (!this.checked) return;
        const errorNode = document.getElementById('privacyInlineError');
        if (errorNode) {
            errorNode.remove();
        }
    });
}

if (serviceTrigger) {
    serviceTrigger.addEventListener('click', () => {
        const nextOpen = servicePanel?.hidden;
        renderServiceOptions();
        setServiceDropdownOpen(!!nextOpen);
    });
}

if (serviceFilter) {
    serviceFilter.addEventListener('input', applyServiceFilter);
}

document.addEventListener('click', (event) => {
    if (!serviceDropdown) return;
    if (serviceDropdown.contains(event.target)) return;
    setServiceDropdownOpen(false);
});

if (servicio && servicio.value) {
    servicioConfirmado = true;
}

syncServiceLabel();
if (servicioConfirmado && fecha && fecha.value) {
    actualizarServiciosDisponibles().then(cargarHorarios);
} else {
    applyServiceFilter();
}
