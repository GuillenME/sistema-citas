<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-mis-citas.css') }}">


</head>

<body class="cliente-citas-index-page">


    @include('cliente.partials.menu')

    <div class="container">

        <div class="table-card">
            <h2>Historial de citas</h2>
            @if (session('success'))
                <div class="citas-success-alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="citas-error-alert">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('info'))
                <div class="citas-info-alert">
                    {{ session('info') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="citas-error-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="citas-grid">
                @foreach ($citas as $cita)
                    @php
                        $precioOriginal = $cita->service->price;
                        $promocionActiva = $cita->service->promocionActiva();
                        $precioFinal = $cita->service->precioConDescuento();
                        $porcentajeDecimal = $porcentajeAnticipo / 100;
                        $anticipo = $precioFinal * $porcentajeDecimal;
                        $restante = $precioFinal - $anticipo;
                        $fechaCita = \Carbon\Carbon::parse($cita->date)->format('Y-m-d');
                        $inicioCita = \Carbon\Carbon::parse($fechaCita . ' ' . $cita->getRawOriginal('start_time'));
                        $puedeReagendarPorTiempo = now()->diffInMinutes($inicioCita, false) >= (24 * 60);
                        $esReenvioTrasRechazo =
                            $cita->status === 'pendiente_anticipo' &&
                            ($cita->payment_attempts ?? 0) > 0;
                        $minutosRestantesReenvio = null;
                        if ($esReenvioTrasRechazo && $cita->payment_deadline) {
                            $minutosRestantesReenvio = max(0, now()->diffInMinutes($cita->payment_deadline, false));
                        }

                        $estadoClase = match ($cita->status) {
                            'pendiente_anticipo' => 'pendiente',
                            'confirmada' => 'confirmada',
                            'completada' => 'confirmada',
                            'no_asistio' => 'cancelada',
                            'cancelada' => 'cancelada',
                            default => 'pendiente',
                        };

	                        $estadoTexto = match ($cita->status) {
	                            'pendiente_anticipo' => $cita->receipt ? 'Pendiente de confirmacion' : 'Pendiente de anticipo',
	                            'confirmada' => 'Confirmada',
	                            'completada' => 'Completada',
	                            'no_asistio' => 'No asistio',
	                            'cancelada' => 'Cancelada',
	                            default => ucfirst($cita->status),
                        };
                    @endphp

                    <div class="cita-card">
                        <div class="cita-header">
                            <div class="cita-eyebrow">Servicio</div>
                            <div class="cita-head-row">
                                <div class="cita-title">{{ $cita->service->name }}</div>
                                <span class="estado {{ $estadoClase }}">{{ $estadoTexto }}</span>
                            </div>
                        </div>

                        <div class="cita-when">
                            <div class="cita-when-box">
                                <span class="when-label">Fecha</span>
                                <strong>{{ \Carbon\Carbon::parse($cita->date)->format('d M, Y') }}</strong>
                            </div>
                            <div class="cita-when-box">
                                <span class="when-label">Hora</span>
                                <strong>{{ \Carbon\Carbon::parse($cita->start_time)->format('h:i A') }}</strong>
                            </div>
                        </div>

                        <div class="cita-price">
                            <div class="price-row">
                                <span>Precio total</span>
                                <strong class="price-final">${{ number_format($precioFinal, 2) }}</strong>
                            </div>
                            @if ($promocionActiva)
                                <div class="price-row">
                                    <span>Antes</span>
                                    <strong class="price-original">${{ number_format($precioOriginal, 2) }}</strong>
                                </div>
                            @endif
                            <div class="price-row">
                                <span>Anticipo pagado</span>
                                <strong class="price-discount">-${{ number_format($anticipo, 2) }}</strong>
                            </div>
                            <div class="price-row">
                                <span>Restante en sucursal</span>
                                <strong class="price-restante">${{ number_format($restante, 2) }}</strong>
                            </div>
                        </div>

                        @if ($esReenvioTrasRechazo)
                            <div class="cita-notes">
                                Tu comprobante fue rechazado.
                                Se permiten maximo 2 intentos.
                                @if (!is_null($minutosRestantesReenvio) && $minutosRestantesReenvio > 0)
                                    Te quedan {{ $minutosRestantesReenvio }} minutos para reenviar uno nuevo.
                                @elseif (!is_null($minutosRestantesReenvio))
                                    El tiempo para reenviar comprobante ya vencio.
                                @endif
                            </div>
                        @endif

                        @if ($cita->status === 'cancelada' && $cita->notes)
                            <div class="cita-notes">{{ $cita->notes }}</div>
                        @endif

                        <details class="cita-details">
                            <summary>Ver detalles</summary>
                            <div class="cita-details-body">
                                <div>Anticipo ({{ $porcentajeAnticipo }}%):
                                    <strong>${{ number_format($anticipo, 2) }}</strong></div>
                                <div>Restante ({{ $porcentajeRestante }}%): <strong
                                        class="price-restante">${{ number_format($restante, 2) }}</strong></div>

                                @if ($cita->status === 'pendiente_anticipo')
                                    <div class="anticipo-info">
                                        <div>Banco: {{ config('citas.banco.nombre') }}</div>
                                        <div>Cuenta: {{ config('citas.banco.cuenta') }}</div>
                                        <div>CLABE: {{ config('citas.banco.clabe') }}</div>
                                        <div class="anticipo-hint">El {{ $porcentajeRestante }}% restante se paga
                                            despues de la cita.</div>
                                        @if ($esReenvioTrasRechazo)
                                            <div class="anticipo-hint">
                                                Reenvio {{ $cita->payment_attempts }}/2.
                                                @if (!is_null($minutosRestantesReenvio) && $minutosRestantesReenvio > 0)
                                                    Tiempo restante: {{ $minutosRestantesReenvio }} min.
                                                @endif
                                            </div>
                                        @else
                                            <div class="anticipo-hint">Tienes 15 minutos para subir el comprobante.</div>
                                        @endif
                                    </div>
	                                @if ($cita->status === 'pendiente_anticipo')
	                                    <div class="anticipo-info">
	                                        <div>Banco: {{ config('citas.banco.nombre') }}</div>
	                                        <div>Cuenta: {{ config('citas.banco.cuenta') }}</div>
	                                        <div>CLABE: {{ config('citas.banco.clabe') }}</div>
	                                        <div class="anticipo-hint">El {{ $porcentajeRestante }}% restante se paga
	                                            despues de la cita.</div>
	                                        <div class="anticipo-hint">
	                                            {{ $cita->receipt ? 'Comprobante enviado. Estamos validando tu anticipo.' : 'Tienes 15 minutos para subir el comprobante.' }}
	                                        </div>
	                                    </div>

                                    @if ($cita->receipt)
                                        <a class="link-green" href="{{ asset('storage/' . $cita->receipt) }}"
                                            target="_blank">
                                            Ver comprobante
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('cliente.citas.comprobante', $cita) }}"
                                            enctype="multipart/form-data" class="upload-form">
                                            @csrf
                                            <div class="upload-row">
                                                <input
                                                    type="file"
                                                    name="comprobante"
                                                    accept="image/*"
                                                    required
                                                    class="comprobante-input"
                                                    data-preview-input>
                                                <button
                                                    type="button"
                                                    class="btn-preview-eye"
                                                    title="Ver imagen seleccionada"
                                                    aria-label="Ver imagen seleccionada"
                                                    data-preview-trigger>
                                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </button>
                                            </div>
                                            <button type="submit" class="btn-upload">Subir comprobante</button>
                                        </form>
                                    @endif
                                @elseif ($cita->receipt)
                                    <a class="link-green" href="{{ asset('storage/' . $cita->receipt) }}"
                                        target="_blank">
                                        Ver comprobante
                                    </a>
                                @endif
                            </div>
                        </details>

                        @if ($cita->status === 'confirmada')
                            <div class="cita-actions">
                                @if ($puedeReagendarPorTiempo)
                                    <button
                                        type="button"
                                        class="btn-cancel-cita btn-reagendar"
                                        data-reagendar-action="{{ route('cliente.citas.reagendar', $cita) }}"
                                        data-service-id="{{ $cita->service_id }}"
                                        data-current-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d') }}"
                                        onclick="abrirModalReagendarCita(this)">
                                        Reagendar
                                    </button>
                                @endif

                                <button
                                    type="button"
                                    class="btn-cancel-cita btn-cancelar"
                                    data-cancel-action="{{ route('cliente.citas.cancelar', $cita) }}"
                                    data-can-cancel="1"
                                    data-has-anticipo="{{ ($cita->status === 'confirmada' || $cita->receipt) ? '1' : '0' }}"
                                    data-can-reagendar-time="{{ $puedeReagendarPorTiempo ? '1' : '0' }}"
                                    data-reagendar-action="{{ route('cliente.citas.reagendar', $cita) }}"
                                    data-service-id="{{ $cita->service_id }}"
                                    data-current-date="{{ \Carbon\Carbon::parse($cita->date)->format('Y-m-d') }}"
                                    onclick="abrirModalCancelarCita(this)">
                                    Cancelar
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <script>
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

        function abrirModalCancelarCita(button) {
            const action = button.getAttribute('data-cancel-action');
            const canCancel = button.getAttribute('data-can-cancel') === '1';
            const hasAnticipo = button.getAttribute('data-has-anticipo') === '1';
            const canReagendarTime = button.getAttribute('data-can-reagendar-time') === '1';
            const reagendarAction = button.getAttribute('data-reagendar-action');
            const serviceId = button.getAttribute('data-service-id');
            const currentDate = button.getAttribute('data-current-date');
            const form = document.getElementById('cancelarCitaForm');
            const modalText = document.getElementById('cancelarCitaPolicyText');
            const submitBtn = document.getElementById('cancelarCitaSubmitBtn');
            const goReagendarBtn = document.getElementById('cancelarToReagendarBtn');

            form.setAttribute('action', action);

            if (canCancel) {
                modalText.textContent = hasAnticipo
                    ? 'Si cancelas, el anticipo no es reembolsable.'
                    : 'Si cancelas, esta accion no se puede deshacer.';
                submitBtn.style.display = 'inline-flex';
                if (canReagendarTime) {
                    goReagendarBtn.style.display = 'inline-flex';
                    goReagendarBtn.setAttribute('data-reagendar-action', reagendarAction);
                    goReagendarBtn.setAttribute('data-service-id', serviceId);
                    goReagendarBtn.setAttribute('data-current-date', currentDate);
                } else {
                    goReagendarBtn.style.display = 'none';
                    goReagendarBtn.removeAttribute('data-reagendar-action');
                    goReagendarBtn.removeAttribute('data-service-id');
                    goReagendarBtn.removeAttribute('data-current-date');
                }
            } else {
                modalText.textContent = canReagendarTime
                    ? 'Esta cita ya tiene anticipo/confirmacion. El anticipo no es reembolsable, pero puedes reagendar una vez con minimo 24 horas de anticipacion.'
                    : 'Esta cita ya tiene anticipo/confirmacion. El anticipo no es reembolsable y ya no se puede reagendar porque faltan menos de 24 horas.';
                submitBtn.style.display = 'none';
                if (canReagendarTime) {
                    goReagendarBtn.style.display = 'inline-flex';
                    goReagendarBtn.setAttribute('data-reagendar-action', reagendarAction);
                    goReagendarBtn.setAttribute('data-service-id', serviceId);
                    goReagendarBtn.setAttribute('data-current-date', currentDate);
                } else {
                    goReagendarBtn.style.display = 'none';
                    goReagendarBtn.removeAttribute('data-reagendar-action');
                    goReagendarBtn.removeAttribute('data-service-id');
                    goReagendarBtn.removeAttribute('data-current-date');
                }
            }

            document.getElementById('modalCancelarCita').classList.add('active');
        }

        function cerrarModalCancelarCita() {
            document.getElementById('modalCancelarCita').classList.remove('active');
        }

        const bloquesUrl = "{{ route('citas.bloques.global') }}";
        let serviceIdReagenda = null;

        async function cargarHorasReagenda() {
            const fechaInput = document.getElementById('reagendarFecha');
            const horaSelect = document.getElementById('reagendarHora');
            const btnSubmit = document.getElementById('btnSubmitReagenda');

            const fecha = fechaInput.value;
            horaSelect.innerHTML = '';

            if (!fecha || !serviceIdReagenda) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Selecciona una fecha';
                horaSelect.appendChild(opt);
                horaSelect.disabled = true;
                btnSubmit.disabled = true;
                return;
            }

            horaSelect.disabled = true;
            btnSubmit.disabled = true;

            try {
                const response = await fetch(`${bloquesUrl}?fecha=${encodeURIComponent(fecha)}&servicio_id=${encodeURIComponent(serviceIdReagenda)}`);
                const bloques = await response.json();

                if (!Array.isArray(bloques) || bloques.length === 0) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'No hay horarios disponibles';
                    horaSelect.appendChild(opt);
                    return;
                }

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Selecciona una hora';
                horaSelect.appendChild(placeholder);

                bloques.forEach((bloque) => {
                    const opt = document.createElement('option');
                    opt.value = bloque.inicio;
                    opt.textContent = `${bloque.inicio} - ${bloque.fin}`;
                    horaSelect.appendChild(opt);
                });

                horaSelect.disabled = false;
            } catch (e) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Error al cargar horarios';
                horaSelect.appendChild(opt);
            }
        }

        function abrirModalReagendarCita(button) {
            const action = button.getAttribute('data-reagendar-action');
            const serviceId = button.getAttribute('data-service-id');
            const currentDate = button.getAttribute('data-current-date');
            const form = document.getElementById('reagendarCitaForm');
            const fechaInput = document.getElementById('reagendarFecha');
            const horaSelect = document.getElementById('reagendarHora');
            const btnSubmit = document.getElementById('btnSubmitReagenda');
            const today = new Date().toISOString().split('T')[0];

            form.setAttribute('action', action);
            serviceIdReagenda = serviceId;
            fechaInput.min = today;
            fechaInput.value = currentDate >= today ? currentDate : today;
            horaSelect.innerHTML = '';
            horaSelect.disabled = true;
            btnSubmit.disabled = true;

            cargarHorasReagenda();
            document.getElementById('modalReagendarCita').classList.add('active');
        }

        function cerrarModalReagendarCita() {
            document.getElementById('modalReagendarCita').classList.remove('active');
        }

        function cerrarModalPreviewComprobante() {
            document.getElementById('modalPreviewComprobante').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const fechaInput = document.getElementById('reagendarFecha');
            const horaSelect = document.getElementById('reagendarHora');
            const btnSubmit = document.getElementById('btnSubmitReagenda');
            const btnToReagendar = document.getElementById('cancelarToReagendarBtn');
            const previewModal = document.getElementById('modalPreviewComprobante');
            const previewImage = document.getElementById('previewComprobanteImage');
            const uploadForms = document.querySelectorAll('.upload-form');

            if (fechaInput) {
                fechaInput.addEventListener('change', cargarHorasReagenda);
            }

            if (horaSelect) {
                horaSelect.addEventListener('change', () => {
                    btnSubmit.disabled = !horaSelect.value;
                });
            }

            if (btnToReagendar) {
                btnToReagendar.addEventListener('click', () => {
                    const action = btnToReagendar.getAttribute('data-reagendar-action');
                    const serviceId = btnToReagendar.getAttribute('data-service-id');
                    const currentDate = btnToReagendar.getAttribute('data-current-date');
                    if (!action || !serviceId || !currentDate) {
                        return;
                    }

                    cerrarModalCancelarCita();
                    abrirModalReagendarCita({
                        getAttribute: (key) => {
                            if (key === 'data-reagendar-action') return action;
                            if (key === 'data-service-id') return serviceId;
                            if (key === 'data-current-date') return currentDate;
                            return '';
                        }
                    });
                });
            }

            if (uploadForms.length && previewModal && previewImage) {
                uploadForms.forEach((form) => {
                    const fileInput = form.querySelector('[data-preview-input]');
                    const previewButton = form.querySelector('[data-preview-trigger]');

                    if (!fileInput || !previewButton) {
                        return;
                    }

                    previewButton.addEventListener('click', () => {
                        const [file] = fileInput.files || [];
                        if (!file) {
                            alert('Selecciona una imagen antes de previsualizar.');
                            fileInput.focus();
                            return;
                        }

                        const fileUrl = URL.createObjectURL(file);
                        previewImage.src = fileUrl;
                        previewModal.classList.add('active');
                        previewImage.onload = () => URL.revokeObjectURL(fileUrl);
                    });
                });
            }
        });
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

    <div id="modalCancelarCita" class="modal-overlay" onclick="if(event.target === this) cerrarModalCancelarCita()">
        <div class="modal-content">
            <h3>Cancelar cita</h3>
            <p id="cancelarCitaPolicyText">Si cancelas, esta accion no se puede deshacer.</p>
            <div class="modal-buttons">
                <form method="POST" id="cancelarCitaForm">
                    @csrf
                    <button type="submit" class="modal-btn modal-btn-confirm" id="cancelarCitaSubmitBtn">Si, cancelar cita</button>
                </form>
                <button type="button" class="modal-btn modal-btn-confirm" id="cancelarToReagendarBtn" style="display:none;">
                    Ir a reagendar
                </button>
                <button type="button" class="modal-btn modal-btn-cancel" onclick="cerrarModalCancelarCita()">
                    Volver
                </button>
            </div>
        </div>
    </div>

    <div id="modalReagendarCita" class="modal-overlay" onclick="if(event.target === this) cerrarModalReagendarCita()">
        <div class="modal-content">
            <h3>Reagendar cita</h3>
            <p>Si ya pagaste anticipo no hay reembolso, pero puedes reagendar una vez con minimo 24 horas de anticipacion.</p>
            <form method="POST" id="reagendarCitaForm" class="upload-form">
                @csrf
                <input type="date" id="reagendarFecha" name="fecha" required>
                <select id="reagendarHora" name="hora_inicio" required disabled>
                    <option value="">Selecciona una fecha</option>
                </select>
                <textarea name="observaciones" rows="2" placeholder="Observaciones (opcional)"></textarea>
                <div class="modal-buttons">
                    <button type="submit" class="modal-btn modal-btn-confirm" id="btnSubmitReagenda" disabled>
                        Confirmar reagenda
                    </button>
                    <button type="button" class="modal-btn modal-btn-cancel" onclick="cerrarModalReagendarCita()">
                        Volver
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalPreviewComprobante" class="modal-overlay" onclick="if(event.target === this) cerrarModalPreviewComprobante()">
        <div class="modal-content modal-content-preview">
            <h3>Vista previa del comprobante</h3>
            <img id="previewComprobanteImage" class="preview-comprobante-image" alt="Vista previa del comprobante seleccionado">
            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="cerrarModalPreviewComprobante()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

</body>

</html>
