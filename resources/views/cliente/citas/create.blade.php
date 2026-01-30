<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente.css') }}">
</head>

<body style="--bg-url: url('{{ asset('imagenes/SalaEsperaa.png') }}')">

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

            {{-- ERRORES --}}
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

                {{-- SERVICIO --}}
                <label>Servicio</label>
                <select name="servicio_id" id="servicio" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach ($servicios as $servicio)
                        @php
                            $promo = $servicio->promocionActiva();
                            $precioFinal = $servicio->precioConDescuento();
                        @endphp
                        <option value="{{ $servicio->id }}" data-precio="{{ $servicio->price }}"
                            data-precio-descuento="{{ $precioFinal }}" data-tiene-promocion="{{ $promo ? '1' : '0' }}"
                            data-descripcion="{{ $servicio->description }}"
                            data-duracion="{{ $servicio->duration_minutes }}"
                            data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}">
                            {{ $servicio->name }}
                        </option>
                    @endforeach
                </select>

                {{-- FECHA --}}
                <label>Fecha</label>
                <input type="date" id="fecha" name="fecha" min="{{ now()->toDateString() }}"
                    onkeydown="return false;">

                {{-- HORARIO --}}
                <label>Horario</label>
                <select id="horarios" name="hora_inicio">
                    <option value="">Selecciona un horario</option>
                </select>

                {{-- PRIVACIDAD --}}
                 <label class="privacy-label">
                <input type="checkbox" name="acepta_privacidad" class="privacy-checkbox">
                <span class="privacy-text">
                    Acepto la <a href="#" class="privacy-link">política de privacidad</a>
                </span>
            </label>

                <button type="button" class="submit-btn" onclick="mostrarModalConfirmar()">
                    AGENDAR CITA
                </button>
            </form>
            <div class="anticipo">
                <h4>⚠ Anticipo requerido</h4>
                <p>Se solicita un <strong>{{ $porcentajeAnticipo }}%</strong> para confirmar la cita</p>
                <p>El <strong>{{ $porcentajeRestante }}%</strong> restante se pagará después de la cita</p>

                <p style="margin-top:10px;color:#fde68a;font-weight:bold;">
                    ⏳ Tienes <strong>15 minutos</strong> para realizar la transferencia y subir el comprobante.
                    De lo contrario, la cita se cancelará automáticamente.
                </p>

                <p>
                    Banco: {{ config('citas.banco.nombre') }}<br>
                    Cuenta: {{ config('citas.banco.cuenta') }}<br>
                    CLABE: {{ config('citas.banco.clabe') }}
                </p>
            </div>

        </div>
    </div>

    <!-- ================= MODAL CONFIRMAR SERVICIO ================= -->
    <div id="modalServicioConfirmar" class="modal-confirm-overlay"
        onclick="if(event.target === this) cancelarServicio()">
        <div class="modal-confirm-content modal-servicio">

            <img id="msImagen" class="modal-servicio-img" src="" alt="Servicio">

            <h3 id="msNombre"></h3>

            <p id="msDescripcion" class="modal-servicio-desc"></p>

            <p class="modal-servicio-info">
                ⏱ <strong>Duración:</strong> <span id="msDuracion"></span> minutos
            </p>

            <p class="modal-servicio-info">
                💰 <strong>Precio:</strong> <span id="msPrecio"></span>
            </p>

            <div class="modal-confirm-buttons">
                <button class="modal-confirm-btn modal-confirm-btn-submit" onclick="confirmarServicio()">Confirmar
                    servicio</button>

                <button class="modal-confirm-btn modal-confirm-btn-cancel" onclick="cancelarServicio()">Cambiar
                    servicio</button>
            </div>
        </div>
    </div>


    <!-- ================= MODAL CONFIRMAR CITA ================= -->
    <div id="modalConfirmar" class="modal-confirm-overlay" onclick="if(event.target === this) cerrarModalConfirmar()">
        <div class="modal-confirm-content">
            <h3>📅 Confirmar cita</h3>

            <p><strong>Servicio:</strong> <span id="mcServicio"></span></p>
            <p><strong>Fecha:</strong> <span id="mcFecha"></span></p>
            <p><strong>Horario:</strong> <span id="mcHorario"></span></p>

            <div class="modal-confirm-buttons">
                <button class="modal-confirm-btn modal-confirm-btn-submit" onclick="confirmarAgendar()">Agendar</button>
                <button class="modal-confirm-btn modal-confirm-btn-cancel"
                    onclick="cerrarModalConfirmar()">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL LOGOUT ================= -->
    <div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
        <div class="modal-content">
            <h3>¿Cerrar sesión?</h3>
            <div class="modal-buttons">
                <button onclick="confirmarLogout()">Sí</button>
                <button onclick="cerrarModalLogout()">No</button>
            </div>
        </div>
    </div>

    <script>
        const servicio = document.getElementById('servicio');
        const fecha = document.getElementById('fecha');
        const horarios = document.getElementById('horarios');

        let servicioConfirmado = false;

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
                opt.textContent = `${h.inicio} - ${h.fin}`;
                horarios.appendChild(opt);
            });
        }

        fecha.addEventListener('change', cargarHorarios);

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
            document.querySelector('form[action="{{ route('cliente.citas.store') }}"]').submit();
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
    </script>

</body>

</html>