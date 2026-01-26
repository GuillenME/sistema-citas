<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Promoción</title>

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
            transition: transform .2s, text-shadow .2s;
            text-shadow: 0 0 10px rgba(99,102,241,.7);
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

        /* ERRORS */
        .errors {
            background: rgba(239,68,68,.15);
            border: 1px solid rgba(239,68,68,.5);
            color: #fecaca;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 20px;
            box-shadow: 0 0 12px rgba(239,68,68,.5);
        }

        .errors li { margin-left: 18px; font-size: 13px; }

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

        input, textarea, select {
            background: rgba(2, 6, 23, .9);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 10px;
            padding: 12px;
            color: #e5e7eb;
            font-size: 14px;
        }

        select {
            cursor: pointer;
        }

        select option {
            background: rgba(2, 6, 23, .9);
            color: #e5e7eb;
            padding: 8px;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
            grid-column: 1 / -1;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 10px rgba(99,102,241,.6);
        }

        /* CHECKBOX */
        .checkbox {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        /* ACTIONS */
        .actions {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            background: transparent;
            border-radius: 10px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-save {
            border: 2px solid #22c55e;
            box-shadow:
                0 0 14px rgba(34,197,94,.7),
                inset 0 0 8px rgba(34,197,94,.4);
        }

        .btn-save:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 25px rgba(34,197,94,1),
                inset 0 0 12px rgba(34,197,94,.6);
        }

        .btn-cancel {
            border: 2px solid #ef4444;
            box-shadow:
                0 0 14px rgba(239,68,68,.7),
                inset 0 0 8px rgba(239,68,68,.4);
        }

        .btn-cancel:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 25px rgba(239,68,68,1),
                inset 0 0 12px rgba(239,68,68,.6);
        }

        @media (max-width: 700px) {
            h1 { font-size: 26px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="dashboard">

    <!-- HEADER -->
    <div class="header">
        <a href="{{ route('admin.promociones.index') }}" class="back-arrow">←</a>
        <h1>Editar Promoción</h1>
    </div>

    <!-- CARD -->
    <div class="card">

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('admin.promociones.update', $promocion) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo"
                           value="{{ old('titulo', $promocion->title) }}" required>
                </div>

                <div class="form-group">
                    <label>Descuento (%)</label>
                    <input type="number" name="descuento" min="1" max="100"
                           value="{{ old('descuento', $promocion->discount) }}" required>
                </div>

                <div class="form-group">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio"
                           value="{{ old('fecha_inicio', $promocion->start_date) }}" required>
                </div>

                <div class="form-group">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin"
                           value="{{ old('fecha_fin', $promocion->end_date) }}" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" required>{{ old('descripcion', $promocion->description) }}</textarea>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Servicios aplicables</label>
                    <select name="servicios[]" multiple required style="min-height: 120px;">
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}"
                                {{ in_array($servicio->id, old('servicios', $serviciosSeleccionados)) ? 'selected' : '' }}>
                                {{ $servicio->name }} - ${{ number_format($servicio->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #9ca3af; font-size: 12px; margin-top: 4px;">
                        Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples servicios
                    </small>
                </div>

                <div class="checkbox">
                    <input type="checkbox" name="publicada"
                           {{ old('publicada', $promocion->published) ? 'checked' : '' }}>
                    <label>Publicar promoción</label>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('admin.promociones.index') }}" class="btn btn-cancel">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-save">
                    Guardar cambios
                </button>
            </div>
        </form>

    </div>

</div>

</body>
</html>
