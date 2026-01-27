<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita (Recepción)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('{{ asset("imagenes/recepFon.png") }}');
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
        }

        header {
            background: rgba(100,100,100,.85);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            font-size: 32px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid red;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(17,24,39,.85);
            backdrop-filter: blur(12px);
            padding: 28px;
            border-radius: 16px;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            margin-top: 15px;
            display: block;
            font-weight: bold;
            font-size: 14px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: none;
        }

        .submit-btn {
            margin-top: 22px;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #7a7a7a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>

<header>
    <a href="{{ route('recepcionista.dashboard') }}" class="back-btn">←</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn">Cerrar sesión</button>
    </form>
</header>

<div class="container">
    <div class="card">

        <h2>Agendar cita (Recepción)</h2>

        <form method="POST" action="{{ route('recepcionista.citas.store') }}">
            @csrf

            <label>Cliente</label>
            <select name="usuario_id" required>
                <option value="">Selecciona un cliente</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">
                        {{ $usuario->nombre }} {{ $usuario->apellido }} — {{ $usuario->email }}
                    </option>
                @endforeach
            </select>

            <label>Servicio</label>
            <select name="servicio_id" id="servicio" required>
                <option value="">Selecciona un servicio</option>
                @foreach ($servicios as $servicio)
                    <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                @endforeach
            </select>

            <label>Fecha</label>
            <input type="date" name="fecha" id="fecha" min="{{ now()->toDateString() }}" required>

            <label>Horario</label>
            <select name="hora_inicio" id="horarios" required>
                <option value="">Selecciona un horario</option>
            </select>

            <label style="margin-top:18px;">
                <input type="checkbox" id="anticipo_check">
                Anticipo recibido
            </label>

            <div id="anticipo_box" style="display:none;">
                <label>Monto del anticipo</label>
                <input type="number" name="anticipo_monto" min="0" step="0.01" placeholder="Ej. 100">
            </div>

            <button class="submit-btn">AGENDAR CITA</button>
        </form>

    </div>
</div>

<script>
const servicio = document.getElementById('servicio');
const fecha = document.getElementById('fecha');
const horarios = document.getElementById('horarios');
const anticipoCheck = document.getElementById('anticipo_check');
const anticipoBox = document.getElementById('anticipo_box');

anticipoCheck.addEventListener('change', () => {
    anticipoBox.style.display = anticipoCheck.checked ? 'block' : 'none';
});

async function cargarBloques() {
    if (!servicio.value || !fecha.value) return;

    horarios.innerHTML = '<option>Cargando...</option>';

    const res = await fetch(`/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`);
    const bloques = await res.json();

    horarios.innerHTML = '';

    if (bloques.length === 0) {
        horarios.innerHTML = '<option>No hay horarios</option>';
        return;
    }

    bloques.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.inicio;
        opt.textContent = `${b.inicio} - ${b.fin}`;
        horarios.appendChild(opt);
    });
}

servicio.addEventListener('change', cargarBloques);
fecha.addEventListener('change', cargarBloques);
</script>

</body>
</html>
