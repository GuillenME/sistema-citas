const servicio = document.getElementById('servicio');
const fecha = document.getElementById('fecha');
const horarios = document.getElementById('horarios');
const fechaInput = document.getElementById('fecha');

let servicioConfirmado = false;

function formatHora12(hora24) {
    if (!hora24) return '';
    const partes = hora24.split(':');
    const h = parseInt(partes[0], 10);
    const m = partes[1] || '00';
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12 = ((h + 11) % 12) + 1;
    return `${h12}:${m} ${ampm}`;
}

/* ===== MODAL SERVICIO ===== */
servicio.addEventListener('change', function() {
    if (!this.value) return;

    servicioConfirmado = false;

    const opt = this.options[this.selectedIndex];

    // Nombre
    document.getElementById('msNombre').textContent = opt.textContent;

    // Imagen
    const img = document.getElementById('msImagen');
    img.src = opt.dataset.imagen;
    img.style.display = 'block';

    // Descripción
    document.getElementById('msDescripcion').textContent =
        opt.dataset.descripcion || 'Sin descripción disponible';

    // Duración
    document.getElementById('msDuracion').textContent =
        opt.dataset.duracion;

    // Precio
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
        document.getElementById('msPrecio').textContent =
            `$${parseFloat(opt.dataset.precio).toFixed(2)}`;
    }

    document.getElementById('modalServicioConfirmar').classList.add('active');
});

function confirmarServicio() {
    servicioConfirmado = true;
    document.getElementById('modalServicioConfirmar').classList.remove('active');
}

function cancelarServicio() {
    servicio.value = '';
    fecha.value = '';
    horarios.innerHTML = '<option value="">Selecciona un horario</option>';
    servicioConfirmado = false;
    document.getElementById('modalServicioConfirmar').classList.remove('active');
}

/* ===== HORARIOS ===== */
async function cargarHorarios() {
    if (!servicioConfirmado || !fecha.value) return;

    const res = await fetch(`/cliente/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`);
    const data = await res.json();

    horarios.innerHTML = '';
    data.forEach(h => {
        const opt = document.createElement('option');
        opt.value = h.inicio;
        opt.textContent = `${formatHora12(h.inicio)} - ${formatHora12(h.fin)}`;
        horarios.appendChild(opt);
    });
}

if (window.flatpickr && fechaInput) {
    flatpickr(fechaInput, {
        inline: true,
        locale: "es",
        dateFormat: 'Y-m-d',
        firstDayOfWeek: 1,
        minDate: 'today',
        disableMobile: true,
        onChange: function () {
            cargarHorarios();
        }
    });
} else if (fechaInput) {
    fechaInput.removeAttribute('readonly');
    fechaInput.type = 'date';
    fechaInput.min = new Date().toISOString().split('T')[0];
    fechaInput.addEventListener('focus', function () {
        if (fechaInput.showPicker) fechaInput.showPicker();
    });
    fechaInput.addEventListener('change', cargarHorarios);
} else {
    fecha.addEventListener('change', cargarHorarios);
}

/* ===== MODAL CONFIRMAR CITA ===== */
function mostrarModalConfirmar() {
    if (!servicioConfirmado || !fecha.value || !horarios.value) {
        alert('Completa y confirma todo primero');
        return;
    }

    document.getElementById('mcServicio').textContent =
        servicio.options[servicio.selectedIndex].textContent;
    document.getElementById('mcFecha').textContent = fecha.value;
    document.getElementById('mcHorario').textContent =
        horarios.options[horarios.selectedIndex].textContent;

    document.getElementById('modalConfirmar').classList.add('active');
}

function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').classList.remove('active');
}

function confirmarAgendar() {
    document.getElementById('formAgendarCita').submit();
}

/* ===== LOGOUT ===== */
function mostrarModalLogout() {
    document.getElementById('modalLogout').classList.add('active');
}

function cerrarModalLogout() {
    document.getElementById('modalLogout').classList.remove('active');
}

function confirmarLogout() {
    document.getElementById('logoutForm').submit();
}
