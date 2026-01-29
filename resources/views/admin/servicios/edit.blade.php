<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @livewireStyles

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
            max-width: 720px;
            padding: 40px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 30px;
            text-shadow:
                0 0 10px rgba(99,102,241,.8),
                0 0 25px rgba(99,102,241,.6);
        }

        /* BOTONES */
        .btn {
            background: transparent;
            border-radius: 12px;
            padding: 14px 26px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-save {
            border: 2px solid #22c55e;
            box-shadow: 0 0 14px rgba(34,197,94,.7),
                        inset 0 0 8px rgba(34,197,94,.4);
        }

        .btn-cancel {
            border: 2px solid #ef4444;
            box-shadow: 0 0 14px rgba(239,68,68,.7),
                        inset 0 0 8px rgba(239,68,68,.4);
        }

        .btn:hover { transform: scale(1.05); }

        /* FORM */
        .form-container {
            background: rgba(17,24,39,.75);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,.15);
            box-shadow:
                0 0 25px rgba(99,102,241,.35),
                inset 0 0 10px rgba(99,102,241,.25);
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(2,6,23,.8);
            color: #fff;
        }

        textarea { min-height: 90px; resize: vertical; }

        .preview {
            max-width: 200px;
            border-radius: 12px;
            margin-top: 10px;
            box-shadow: 0 0 15px rgba(99,102,241,.6);
        }

        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.65);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: rgba(17,24,39,.96);
            border-radius: 18px;
            padding: 30px;
            max-width: 420px;
            width: 92%;
            text-align: center;
            border: 1px solid rgba(255,255,255,.18);
            box-shadow:
                0 0 35px rgba(99,102,241,.45),
                inset 0 0 15px rgba(99,102,241,.25);
        }

        .modal-box h3 {
            font-size: 22px;
            margin-bottom: 14px;
        }

        .modal-box p {
            font-size: 14px;
            margin-bottom: 26px;
            color: #c7d2fe;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
    </style>
</head>

<body>

<div class="dashboard">
    <h1>Editar servicio</h1>

    <livewire:admin.servicio-edit :servicio="$servicio" />
</div>

@livewireScripts
</body>
</html>
