<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo servicio</title>

    @livewireStyles
</head>

<style>
    /* RESET */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /* FONDO */
    body {
        min-height: 100vh;
        font-family: Arial, sans-serif;
        background: radial-gradient(circle at top, #1e1b4b, #020617);
        color: #e5e7eb;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* CONTENEDOR */
    .dashboard {
        width: 100%;
        max-width: 900px;
        padding: 40px;
    }

    /* TITULO */
    h1 {
        font-size: 32px;
        letter-spacing: 1px;
        margin-bottom: 35px;
        text-shadow:
            0 0 10px rgba(99, 102, 241, .8),
            0 0 25px rgba(99, 102, 241, .6);
    }

    /* CARD */
    .card {
        background: rgba(17, 24, 39, .75);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, .15);
        box-shadow:
            0 0 25px rgba(99, 102, 241, .35),
            inset 0 0 10px rgba(99, 102, 241, .25);
    }

    /* FORM */
    label {
        display: block;
        margin-top: 18px;
        margin-bottom: 6px;
        font-size: 13px;
        letter-spacing: 1px;
        color: #c7d2fe;
    }

    input,
    textarea,
    select {
        width: 100%;
        background: rgba(2, 6, 23, .9);
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 10px;
        padding: 12px;
        color: #e5e7eb;
        font-size: 14px;
    }

    textarea {
        resize: vertical;
        min-height: 90px;
    }

    /* BOTONES */
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
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* GUARDAR */
    .btn-save {
        border: 2px solid #22c55e;
        box-shadow:
            0 0 14px rgba(34, 197, 94, .7),
            inset 0 0 8px rgba(34, 197, 94, .4);
    }

    .btn-save:hover {
        transform: scale(1.05);
        box-shadow:
            0 0 25px rgba(34, 197, 94, 1),
            inset 0 0 12px rgba(34, 197, 94, .6);
    }

    /* CANCELAR */
    .btn-cancel {
        border: 2px solid #ef4444;
        box-shadow:
            0 0 14px rgba(239, 68, 68, .7),
            inset 0 0 8px rgba(239, 68, 68, .4);
    }

    .btn-cancel:hover {
        transform: scale(1.05);
        box-shadow:
            0 0 25px rgba(239, 68, 68, 1),
            inset 0 0 12px rgba(239, 68, 68, .6);
    }

    /* MODAL */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .65);
        backdrop-filter: blur(4px);
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-box {
        background: rgba(17, 24, 39, .95);
        border-radius: 16px;
        padding: 28px;
        max-width: 420px;
        width: 90%;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, .2);
        box-shadow: 0 0 25px rgba(99, 102, 241, .6);
    }

    .modal-box h3 {
        margin-bottom: 15px;
        text-shadow: 0 0 10px rgba(99, 102, 241, .8);
    }

    .modal-box p {
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
    }
</style>


<body>

    <div class="dashboard">

        <div class="header">
            <a href="{{ route('admin.servicios.index') }}" class="back-arrow">←</a>
            <h1>Nuevo servicio</h1>
        </div>

        <div class="card">
            <livewire:admin.servicio-create />
        </div>

    </div>

    @livewireScripts
</body>

</html>
