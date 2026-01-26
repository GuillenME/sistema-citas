<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo servicio</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #1e1b4b, #020617);
            color: #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard {
            width: 100%;
            max-width: 700px;
            padding: 40px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-arrow {
            font-size: 28px;
            text-decoration: none;
            color: #a5b4fc;
            text-shadow: 0 0 10px rgba(99,102,241,.7);
            transition: transform .2s, text-shadow .2s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            text-shadow: 0 0 20px rgba(99,102,241,1);
        }

        h1 {
            font-size: 30px;
            letter-spacing: 1px;
            text-shadow:
                0 0 10px rgba(99,102,241,.8),
                0 0 25px rgba(99,102,241,.6);
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn-logout {
            background: transparent;
            border: 2px solid #ef4444;
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            letter-spacing: 1px;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(239,68,68,.7),
                inset 0 0 8px rgba(239,68,68,.4);
        }

        .btn-logout:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 25px rgba(239,68,68,1),
                inset 0 0 12px rgba(239,68,68,.6);
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
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.6);
            border: 2px solid rgba(239, 68, 68, 0.5);
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

        /* FORM */
        .form-container {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow:
                0 0 25px rgba(99,102,241,.35),
                inset 0 0 10px rgba(99,102,241,.25);
        }

        form label {
            display: block;
            margin-top: 18px;
            margin-bottom: 6px;
            font-size: 14px;
            letter-spacing: 1px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(2, 6, 23, .8);
            color: #fff;
            font-size: 14px;
            outline: none;
        }

        form textarea {
            resize: vertical;
            min-height: 90px;
        }

        form input:focus,
        form textarea:focus {
            border-color: #818cf8;
            box-shadow: 0 0 10px rgba(129,140,248,.7);
        }

        .btn-submit {
            margin-top: 30px;
            width: 100%;
            background: transparent;
            border: 2px solid #22c55e;
            color: #fff;
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            letter-spacing: 1px;
            transition: transform .2s, box-shadow .2s;
            box-shadow:
                0 0 14px rgba(34,197,94,.7),
                inset 0 0 8px rgba(34,197,94,.4);
        }

        .btn-submit:hover {
            transform: scale(1.03);
            box-shadow:
                0 0 25px rgba(34,197,94,1),
                inset 0 0 12px rgba(34,197,94,.6);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <div class="header">
        <div class="header-left">
            <!-- Volver a servicios -->
            <a href="{{ route('admin.servicios.index') }}" class="back-arrow">←</a>
            <h1>Nuevo servicio</h1>
        </div>

        <div class="header-actions">
            <!-- Cerrar sesión -->
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="button" class="btn-logout" onclick="mostrarModalLogout()">Cerrar sesión</button>
            </form>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('admin.servicios.store') }}">
            @csrf

            <label>Nombre</label>
            <input type="text" name="nombre">

            <label>Descripción</label>
            <textarea name="descripcion"></textarea>

            <label>Duración (min)</label>
            <input type="number" name="duracion_minutos">

            <label>Precio</label>
            <input type="number" step="0.01" name="precio">

            <button type="submit" class="btn-submit">
                Guardar
            </button>
        </form>
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
