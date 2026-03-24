(function () {
    const usuario = document.getElementById('usuario_id');
    const servicio = document.getElementById('servicio');
    const fecha = document.getElementById('fecha');
    const horarios = document.getElementById('horarios');
    const horaInicioOld = document.getElementById('horaInicioOld');
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
    const btnAgendar = document.getElementById('btnAgendar');
    const clientDropdown = document.getElementById('clientDropdown');
    const clientTrigger = document.getElementById('clientDropdownTrigger');
    const clientLabel = document.getElementById('clientDropdownLabel');
    const clientPanel = document.getElementById('clientDropdownPanel');
    const clientFilter = document.getElementById('clientFilter');
    const clientOptionsWrap = document.getElementById('clientDropdownOptions');
    const serviceDropdown = document.getElementById('serviceDropdown');
    const serviceTrigger = document.getElementById('serviceDropdownTrigger');
    const serviceLabel = document.getElementById('serviceDropdownLabel');
    const servicePanel = document.getElementById('serviceDropdownPanel');
    const serviceFilter = document.getElementById('serviceFilter');
    const serviceOptionsWrap = document.getElementById('serviceDropdownOptions');
    const usuariosBase = usuario
        ? Array.from(usuario.options).filter((opt) => opt.value).map((opt) => opt.cloneNode(true))
        : [];
    const serviciosBase = servicio
        ? Array.from(servicio.options).filter((opt) => opt.value).map((opt) => opt.cloneNode(true))
        : [];

    if (!usuario || !servicio || !fecha || !horarios || !chipsWrap) {
        return;
    }

    let servicioConfirmado = false;
    let fpInstance = null;

    function normalizeText(value) {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();
    }

    function setDropdownOpen(panel, trigger, open) {
        if (!panel || !trigger) return;
        panel.hidden = !open;
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    function syncDropdownLabel(select, label, placeholder) {
        if (!label) return;
        const opt = select.options[select.selectedIndex];
        label.textContent = opt && opt.value ? opt.textContent.trim() : placeholder;
    }

    function filterOptions(baseOptions, searchValue) {
        const search = normalizeText(searchValue);
        return baseOptions.filter((opt) => search === '' || normalizeText(opt.textContent).includes(search));
    }

    function syncSelectOptions(select, placeholder, options) {
        const selectedValue = select.value;
        select.innerHTML = `<option value="">${placeholder}</option>`;

        options.forEach((opt) => {
            const clone = opt.cloneNode(true);
            if (String(clone.value) === String(selectedValue)) {
                clone.selected = true;
            }
            select.appendChild(clone);
        });

        return selectedValue && String(select.value) === String(selectedValue);
    }

    function renderDropdownOptions(config) {
        const {
            select,
            baseOptions,
            filterInput,
            optionsWrap,
            placeholder,
            onPick,
        } = config;

        if (!optionsWrap) return;

        const filteredOptions = filterOptions(baseOptions, filterInput?.value || '');
        syncSelectOptions(select, placeholder, filteredOptions);
        optionsWrap.innerHTML = '';

        const placeholderBtn = document.createElement('button');
        placeholderBtn.type = 'button';
        placeholderBtn.className = 'searchable-select-option';
        placeholderBtn.textContent = placeholder;
        if (!select.value) {
            placeholderBtn.classList.add('is-selected');
        }
        placeholderBtn.addEventListener('click', () => onPick(null));
        optionsWrap.appendChild(placeholderBtn);

        if (!filteredOptions.length) {
            const empty = document.createElement('div');
            empty.className = 'searchable-select-empty';
            empty.textContent = 'No se encontraron resultados.';
            optionsWrap.appendChild(empty);
            return;
        }

        filteredOptions.forEach((opt) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'searchable-select-option';
            button.textContent = opt.textContent.trim();
            if (String(opt.value) === String(select.value)) {
                button.classList.add('is-selected');
            }
            button.addEventListener('click', () => onPick(opt));
            optionsWrap.appendChild(button);
        });
    }

    function limpiarHorarios() {
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
        renderHoraChips();
    }

    function limpiarServicioSeleccionado() {
        servicio.value = '';
        servicioConfirmado = false;
        fecha.value = '';
        limpiarHorarios();
        if (serviceFilter) {
            serviceFilter.value = '';
        }
        syncDropdownLabel(servicio, serviceLabel, 'Selecciona un servicio');
    }

    function limpiarClienteSeleccionado() {
        usuario.value = '';
        if (clientFilter) {
            clientFilter.value = '';
        }
        limpiarServicioSeleccionado();
        syncDropdownLabel(usuario, clientLabel, 'Selecciona un cliente');
    }

    function bloquearCalendario(locked) {
        if (!fpInstance || !fpInstance.calendarContainer) return;
        const cal = fpInstance.calendarContainer;
        cal.classList.toggle('is-locked', locked);
        cal.style.pointerEvents = locked ? 'none' : '';
        cal.style.opacity = locked ? '0.45' : '';
        cal.style.filter = locked ? 'grayscale(0.15)' : '';
    }

    function actualizarEstadoCampos() {
        const clienteSeleccionado = !!usuario.value;
        const servicioSeleccionado = !!servicio.value;
        const fechaSeleccionada = !!fecha.value;
        const horaSeleccionada = !!horarios.value;

        fecha.disabled = !(clienteSeleccionado && servicioSeleccionado && servicioConfirmado);
        horarios.disabled = !(clienteSeleccionado && servicioSeleccionado && servicioConfirmado && fechaSeleccionada);

        anticipoCheck.disabled = !horaSeleccionada;
        if (!horaSeleccionada) {
            anticipoCheck.checked = false;
        }

        anticipoBox.hidden = !anticipoCheck.checked;
        anticipoMonto.disabled = !anticipoCheck.checked;
        if (!anticipoCheck.checked) {
            anticipoMonto.value = '';
        }

        bloquearCalendario(fecha.disabled);

        if (btnAgendar) {
            btnAgendar.disabled = !(
                usuario.value &&
                servicioConfirmado &&
                fecha.value &&
                horarios.value &&
                anticipoCheck.checked &&
                anticipoMonto.value &&
                parseFloat(anticipoMonto.value) > 0
            );
        }
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
            if (horarios.value === opt.value) {
                btn.classList.add('active');
            }
            btn.addEventListener('click', () => {
                horarios.value = opt.value;
                renderHoraChips();
                syncSummary();
                actualizarEstadoCampos();
            });
            chipsWrap.appendChild(btn);
        });
    }

    servicio.addEventListener('change', function () {
        if (!this.value) {
            syncDropdownLabel(servicio, serviceLabel, 'Selecciona un servicio');
            syncSummary();
            actualizarEstadoCampos();
            return;
        }

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

        syncDropdownLabel(servicio, serviceLabel, 'Selecciona un servicio');
        syncSummary();
        actualizarEstadoCampos();
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

            if (horaInicioOld && horaInicioOld.value) {
                const existeHorarioOld = Array.from(horarios.options).some((o) => o.value === horaInicioOld.value);
                if (existeHorarioOld) {
                    horarios.value = horaInicioOld.value;
                }
            }

            renderHoraChips();
            syncSummary();
        } catch (e) {
            horarios.innerHTML = '<option value="">Error al cargar horarios</option>';
            renderHoraChips('No se pudieron cargar horarios. Intenta nuevamente.');
            syncSummary();
        }
    }

    anticipoCheck.addEventListener('change', actualizarEstadoCampos);
    anticipoMonto.addEventListener('input', actualizarEstadoCampos);

    usuario.addEventListener('change', () => {
        if (!usuario.value) {
            limpiarClienteSeleccionado();
        } else {
            limpiarServicioSeleccionado();
        }
        syncDropdownLabel(usuario, clientLabel, 'Selecciona un cliente');
        syncSummary();
        actualizarEstadoCampos();
    });

    if (clientTrigger) {
        clientTrigger.addEventListener('click', () => {
            renderDropdownOptions({
                select: usuario,
                baseOptions: usuariosBase,
                filterInput: clientFilter,
                optionsWrap: clientOptionsWrap,
                placeholder: 'Selecciona un cliente',
                onPick: (opt) => {
                    if (!opt) {
                        limpiarClienteSeleccionado();
                    } else {
                        usuario.value = opt.value;
                    }
                    syncDropdownLabel(usuario, clientLabel, 'Selecciona un cliente');
                    setDropdownOpen(clientPanel, clientTrigger, false);
                    usuario.dispatchEvent(new Event('change'));
                }
            });
            setDropdownOpen(clientPanel, clientTrigger, clientPanel.hidden);
            if (clientPanel.hidden === false && clientFilter) {
                window.setTimeout(() => clientFilter.focus(), 0);
            }
        });
    }

    if (serviceTrigger) {
        serviceTrigger.addEventListener('click', () => {
            renderDropdownOptions({
                select: servicio,
                baseOptions: serviciosBase,
                filterInput: serviceFilter,
                optionsWrap: serviceOptionsWrap,
                placeholder: 'Selecciona un servicio',
                onPick: (opt) => {
                    if (!opt) {
                        limpiarServicioSeleccionado();
                    } else {
                        servicio.value = opt.value;
                    }
                    syncDropdownLabel(servicio, serviceLabel, 'Selecciona un servicio');
                    setDropdownOpen(servicePanel, serviceTrigger, false);
                    servicio.dispatchEvent(new Event('change'));
                }
            });
            setDropdownOpen(servicePanel, serviceTrigger, servicePanel.hidden);
            if (servicePanel.hidden === false && serviceFilter) {
                window.setTimeout(() => serviceFilter.focus(), 0);
            }
        });
    }

    if (clientFilter) {
        clientFilter.addEventListener('input', () => {
            renderDropdownOptions({
                select: usuario,
                baseOptions: usuariosBase,
                filterInput: clientFilter,
                optionsWrap: clientOptionsWrap,
                placeholder: 'Selecciona un cliente',
                onPick: (opt) => {
                    if (!opt) {
                        limpiarClienteSeleccionado();
                    } else {
                        usuario.value = opt.value;
                    }
                    syncDropdownLabel(usuario, clientLabel, 'Selecciona un cliente');
                    setDropdownOpen(clientPanel, clientTrigger, false);
                    usuario.dispatchEvent(new Event('change'));
                }
            });
        });
    }

    if (serviceFilter) {
        serviceFilter.addEventListener('input', () => {
            renderDropdownOptions({
                select: servicio,
                baseOptions: serviciosBase,
                filterInput: serviceFilter,
                optionsWrap: serviceOptionsWrap,
                placeholder: 'Selecciona un servicio',
                onPick: (opt) => {
                    if (!opt) {
                        limpiarServicioSeleccionado();
                    } else {
                        servicio.value = opt.value;
                    }
                    syncDropdownLabel(servicio, serviceLabel, 'Selecciona un servicio');
                    setDropdownOpen(servicePanel, serviceTrigger, false);
                    servicio.dispatchEvent(new Event('change'));
                }
            });
        });
    }

    fecha.addEventListener('change', () => {
        if (!fecha.value) return;
        if (esDomingo(fecha.value)) {
            alert('Los domingos no se atiende. Por favor selecciona otro dia.');
            fecha.value = '';
            limpiarHorarios();
            syncSummary();
            actualizarEstadoCampos();
            return;
        }
        cargarBloques();
        syncSummary();
        actualizarEstadoCampos();
    });

    horarios.addEventListener('change', () => {
        renderHoraChips();
        syncSummary();
        actualizarEstadoCampos();
    });

    if (window.flatpickr) {
        fpInstance = flatpickr(fecha, {
            inline: true,
            locale: 'es',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            onChange: function () {
                if (fecha.disabled) {
                    if (fpInstance) fpInstance.clear();
                    return;
                }
                if (!fecha.value) return;
                if (esDomingo(fecha.value)) {
                    alert('Los domingos no se atiende. Por favor selecciona otro dia.');
                    fecha.value = '';
                    limpiarHorarios();
                    syncSummary();
                    actualizarEstadoCampos();
                    return;
                }
                cargarBloques();
                syncSummary();
                actualizarEstadoCampos();
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
        actualizarEstadoCampos();
    }

    function cancelarServicio() {
        limpiarServicioSeleccionado();
        document.getElementById('modalServicioConfirmar').classList.remove('active');
        syncSummary();
        actualizarEstadoCampos();
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

    document.addEventListener('click', (event) => {
        if (clientDropdown && !clientDropdown.contains(event.target)) {
            setDropdownOpen(clientPanel, clientTrigger, false);
        }
        if (serviceDropdown && !serviceDropdown.contains(event.target)) {
            setDropdownOpen(servicePanel, serviceTrigger, false);
        }
    });

    anticipoBox.hidden = !anticipoCheck.checked;
    anticipoMonto.disabled = !anticipoCheck.checked;
    syncDropdownLabel(usuario, clientLabel, 'Selecciona un cliente');
    syncDropdownLabel(servicio, serviceLabel, 'Selecciona un servicio');
    if (servicio.value) {
        servicioConfirmado = true;
    }
    renderHoraChips();
    syncSummary();
    actualizarEstadoCampos();
    if (servicioConfirmado && fecha.value) {
        cargarBloques();
    }
})();
