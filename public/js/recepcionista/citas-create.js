(function () {
    const usuario = document.getElementById('usuario_id');
    const servicio = document.getElementById('servicio');
    const fecha = document.getElementById('fecha');
    const horarios = document.getElementById('horarios');
    const chipsWrap = document.getElementById('horarioChips');
    const anticipoCheck = document.getElementById('anticipo_check');
    const anticipoBox = document.getElementById('anticipo_box');
    const anticipoMonto = document.getElementById('anticipo_monto');
    const summaryCliente = document.getElementById('summaryCliente');
    const summaryService = document.getElementById('summaryService');
    const summaryDateTime = document.getElementById('summaryDateTime');
    const summaryAnticipo = document.getElementById('summaryAnticipo');
    const warningToast = document.getElementById('warningToast');
    const warningToastText = document.getElementById('warningToastText');
    const anticipoPct = parseFloat(document.body?.dataset?.anticipoPct || '50');

    if (!usuario || !servicio || !fecha || !horarios || !chipsWrap) {
        return;
    }

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

    function syncSummary() {
        const userOpt = usuario.options[usuario.selectedIndex];
        const serviceOpt = servicio.options[servicio.selectedIndex];
        const nombreCliente = userOpt && userOpt.value ? userOpt.textContent.trim() : '-';
        const serviceName = serviceOpt && serviceOpt.value ? serviceOpt.textContent.trim() : '-';
        const precioBase = serviceOpt && serviceOpt.value
            ? Number(serviceOpt.dataset.precioDescuento || serviceOpt.dataset.precio || 0)
            : 0;
        const anticipo = precioBase * (anticipoPct / 100);
        const hora = horarios.value ? formatHora12(horarios.value) : '';

        summaryCliente.textContent = nombreCliente;
        summaryService.textContent = serviceName;
        summaryDateTime.textContent = fecha.value
            ? `${formatFecha(fecha.value)}${hora ? ' - ' + hora : ''}`
            : '-';
        summaryAnticipo.textContent = formatMoney(anticipo);
    }

    function esDomingo(fechaString) {
        if (!fechaString) return false;
        const d = new Date(fechaString + 'T00:00:00');
        return d.getDay() === 0;
    }

    function renderHoraChips(mensajeVacio = 'Selecciona servicio y fecha para ver horarios.') {
        chipsWrap.innerHTML = '';
        const opts = Array.from(horarios.options).filter((o) => o.value);
        if (!opts.length) {
            const empty = document.createElement('div');
            empty.className = 'horario-empty';
            empty.textContent = mensajeVacio;
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
                renderHoraChips();
                syncSummary();
            });
            chipsWrap.appendChild(btn);
        });
    }

    servicio.addEventListener('change', function () {
        if (!this.value) return;

        servicioConfirmado = false;
        const opt = this.options[this.selectedIndex];

        document.getElementById('msNombre').textContent = opt.textContent;
        document.getElementById('msDescripcion').textContent = opt.dataset.descripcion || 'Sin descripcion disponible';
        document.getElementById('msDuracion').textContent = opt.dataset.duracion;

        const img = document.getElementById('msImagen');
        img.src = opt.dataset.imagen;
        img.style.display = 'block';

        if (opt.dataset.tienePromocion === '1') {
            document.getElementById('msPrecio').innerHTML =
                `<span style="text-decoration: line-through; opacity:.6">$${parseFloat(opt.dataset.precio).toFixed(2)}</span><strong style="color:#22c55e; margin-left:6px">$${parseFloat(opt.dataset.precioDescuento).toFixed(2)}</strong>`;
        } else {
            document.getElementById('msPrecio').textContent = `$${parseFloat(opt.dataset.precio).toFixed(2)}`;
        }

        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        renderHoraChips();
        syncSummary();
        document.getElementById('modalServicioConfirmar').classList.add('active');
    });

    async function cargarBloques() {
        if (!servicioConfirmado || !servicio.value || !fecha.value) return;
        horarios.innerHTML = '<option value="">Cargando...</option>';
        renderHoraChips('Cargando horarios...');

        try {
            const res = await fetch(`/citas/bloques?servicio_id=${encodeURIComponent(servicio.value)}&fecha=${encodeURIComponent(fecha.value)}`);
            const bloques = await res.json();

            horarios.innerHTML = '<option value="">Selecciona un horario</option>';

            if (!Array.isArray(bloques) || !bloques.length) {
                renderHoraChips('No hay horarios disponibles para esa fecha. Revisa horario laboral, hora de comida o saturacion de empleados.');
                syncSummary();
                return;
            }

            bloques.forEach((b) => {
                const opt = document.createElement('option');
                opt.value = b.inicio;
                opt.textContent = `${formatHora12(b.inicio)} - ${formatHora12(b.fin)}`;
                horarios.appendChild(opt);
            });

            renderHoraChips();
            syncSummary();
        } catch (e) {
            horarios.innerHTML = '<option value="">Error al cargar horarios</option>';
            renderHoraChips('No se pudieron cargar horarios. Intenta nuevamente.');
            syncSummary();
        }
    }

    anticipoCheck.addEventListener('change', () => {
        anticipoBox.hidden = !anticipoCheck.checked;
        anticipoMonto.disabled = !anticipoCheck.checked;
        if (!anticipoCheck.checked) {
            anticipoMonto.value = '';
        }
    });

    usuario.addEventListener('change', syncSummary);
    fecha.addEventListener('change', () => {
        if (!fecha.value) return;
        if (esDomingo(fecha.value)) {
            alert('Los domingos no se atiende. Por favor selecciona otro dia.');
            fecha.value = '';
            horarios.innerHTML = '<option value="">Selecciona un horario</option>';
            renderHoraChips();
            syncSummary();
            return;
        }
        cargarBloques();
        syncSummary();
    });

    horarios.addEventListener('change', () => {
        renderHoraChips();
        syncSummary();
    });

    if (window.flatpickr) {
        flatpickr(fecha, {
            inline: true,
            locale: 'es',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            onChange: function () {
                if (!fecha.value) return;
                if (esDomingo(fecha.value)) {
                    alert('Los domingos no se atiende. Por favor selecciona otro dia.');
                    fecha.value = '';
                    horarios.innerHTML = '<option value="">Selecciona un horario</option>';
                    renderHoraChips();
                    syncSummary();
                    return;
                }
                cargarBloques();
                syncSummary();
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

    function mostrarWarningToast(texto) {
        warningToastText.textContent = texto;
        warningToast.classList.add('active');
        clearTimeout(window.warningToastTimer);
        window.warningToastTimer = setTimeout(() => {
            warningToast.classList.remove('active');
        }, 4200);
    }

    function cerrarWarningToast() {
        warningToast.classList.remove('active');
    }

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
        renderHoraChips();
        syncSummary();
    }

    function mostrarModalConfirmar() {
        if (!usuario.value || !servicioConfirmado || !fecha.value || !horarios.value) {
            mostrarWarningToast('Completa y confirma cliente, servicio, fecha y horario antes de continuar.');
            return;
        }

        if (!anticipoCheck.checked || !anticipoMonto.value || Number(anticipoMonto.value) <= 0) {
            mostrarWarningToast('Marca "Se recibio anticipo en recepcion" y captura un monto valido para poder agendar.');
            return;
        }

        document.getElementById('mcCliente').textContent = usuario.options[usuario.selectedIndex].textContent;
        document.getElementById('mcServicio').textContent = servicio.options[servicio.selectedIndex].textContent;
        document.getElementById('mcFecha').textContent = fecha.value;
        document.getElementById('mcHorario').textContent = horarios.options[horarios.selectedIndex].textContent;
        document.getElementById('modalConfirmar').classList.add('active');
    }

    function cerrarModalConfirmar() {
        document.getElementById('modalConfirmar').classList.remove('active');
    }

    function confirmarAgendar() {
        document.getElementById('formAgendarCita').submit();
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

    window.confirmarServicio = confirmarServicio;
    window.cancelarServicio = cancelarServicio;
    window.mostrarModalConfirmar = mostrarModalConfirmar;
    window.cerrarModalConfirmar = cerrarModalConfirmar;
    window.confirmarAgendar = confirmarAgendar;
    window.mostrarModalLogout = mostrarModalLogout;
    window.cerrarModalLogout = cerrarModalLogout;
    window.confirmarLogout = confirmarLogout;
    window.cerrarWarningToast = cerrarWarningToast;

    anticipoBox.hidden = !anticipoCheck.checked;
    anticipoMonto.disabled = !anticipoCheck.checked;
    renderHoraChips();
    syncSummary();
})();
