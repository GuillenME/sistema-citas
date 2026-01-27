<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Promoción</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

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
            max-width: 900px;
            padding: 40px;
        }

        /* HEADER */
        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 35px;
        }

        .back-arrow {
            font-size: 28px;
            text-decoration: none;
            color: #a5b4fc;
            text-shadow: 0 0 10px rgba(99,102,241,.7);
        }

        h1 {
            font-size: 32px;
            letter-spacing: 1px;
            text-shadow:
                0 0 10px rgba(99,102,241,.8),
                0 0 25px rgba(99,102,241,.6);
        }

        /* CARD */
        .card {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow:
                0 0 25px rgba(99,102,241,.35),
                inset 0 0 10px rgba(99,102,241,.25);
        }

        /* FORM */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        label {
            font-size: 13px;
            letter-spacing: 1px;
            color: #c7d2fe;
        }

        input, textarea {
            background: rgba(2, 6, 23, .9);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 10px;
            padding: 12px;
            color: #e5e7eb;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
            grid-column: 1 / -1;
        }

        /* CHECKBOX */
        .checkbox {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        /* ACTIONS */
        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .btn {
            background: transparent;
            border-radius: 10px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            color: #fff;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-save {
            border: 2px solid #22c55e;
            box-shadow: 0 0 14px rgba(34,197,94,.7), inset 0 0 8px rgba(34,197,94,.4);
        }

        .btn-cancel {
            border: 2px solid #ef4444;
            box-shadow: 0 0 14px rgba(239,68,68,.7), inset 0 0 8px rgba(239,68,68,.4);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* MODAL */
        #confirmModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.65);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-box {
            background: rgba(17,24,39,.95);
            border-radius: 16px;
            padding: 25px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            border: 1px solid rgba(255,255,255,.2);
            box-shadow: 0 0 25px rgba(99,102,241,.6);
        }

        .modal-box h3 {
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(99,102,241,.8);
        }

        .modal-box p {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        @media (max-width: 700px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <!-- HEADER -->
    <div class="header">
        <a href="{{ route('admin.promociones.index') }}" class="back-arrow">←</a>
        <h1>Crear Promoción</h1>
    </div>

    <!-- CARD -->
    <div class="card">

        <form id="promoForm" method="POST" action="{{ route('admin.promociones.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo" required>
                </div>

                <div class="form-group">
                    <label>Descuento (%)</label>
                    <input type="number" name="descuento" min="1" max="100" required>
                </div>

                <div class="form-group">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio" required>
                </div>

                <div class="form-group">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" required></textarea>
                </div>

                <div class="checkbox">
                    <input type="checkbox" name="publicada" id="publicada">
                    <label for="publicada">Publicar promoción</label>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('admin.promociones.index') }}" class="btn btn-cancel">
                    Cancelar
                </a>

                <button type="button" class="btn btn-save" onclick="confirmarPromocion()">
                    Guardar promoción
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL -->
<div id="confirmModal">
    <div class="modal-box">
        <h3>¿Confirmar promoción?</h3>
        <p id="resumenPromo"></p>

        <div class="modal-actions">
            <button class="btn btn-cancel" onclick="cerrarModal()">Seguir editando</button>
            <button class="btn btn-save" onclick="enviarFormulario()">Sí, guardar</button>
        </div>
    </div>
</div>

<script>
    function confirmarPromocion() {
        const titulo = document.querySelector('[name="titulo"]').value;
        const descuento = document.querySelector('[name="descuento"]').value;
        const inicio = document.querySelector('[name="fecha_inicio"]').value;
        const fin = document.querySelector('[name="fecha_fin"]').value;
        const publicada = document.querySelector('[name="publicada"]').checked ? 'Sí' : 'No';

        if (!titulo || !descuento || !inicio || !fin) {
            alert('Completa todos los campos.');
            return;
        }

        document.getElementById('resumenPromo').innerHTML = `
            <strong>Título:</strong> ${titulo}<br>
            <strong>Descuento:</strong> ${descuento}%<br>
            <strong>Inicio:</strong> ${inicio}<br>
            <strong>Fin:</strong> ${fin}<br>
            <strong>Publicar:</strong> ${publicada}
        `;

        document.getElementById('confirmModal').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    function enviarFormulario() {
        document.getElementById('promoForm').submit();
    }
</script>

</body>
</html>
