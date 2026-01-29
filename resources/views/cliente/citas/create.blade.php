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
                    <input type="checkbox" name="acepta_privacidad" value="1">
                    Acepto la política de privacidad
                </label>

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
