<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('{{ asset('imagenes/RegistrarSala.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        /* ===== OSCURECER FONDO ===== */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 0;
            pointer-events: none;
        }

        /* ===== BARRA SUPERIOR ===== */
        header {
            background: rgba(115, 114, 126, 0.85);
            color: #fff;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;

            position: relative;
            z-index: 1;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Flecha */
        .back-btn {
            background: transparent;
            color: #ffffff;
            text-decoration: none;
            font-size: 38px;
            font-weight: bold;
            padding: 6px 10px;
            cursor: pointer;

            text-shadow:
                0 0 6px rgba(255, 255, 255, .8),
                0 0 14px rgba(255, 255, 255, .6);

            transition: transform .2s ease, text-shadow .3s ease;
        }

        .back-btn:hover {
            transform: scale(1.2);
            text-shadow:
                0 0 12px rgba(255, 255, 255, 1),
                0 0 22px rgba(255, 255, 255, .8);
        }

        /* Logout */
        .logout-btn {
            background: transparent;
            border: 2px solid #ff0000;
            color: #fff;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;

            box-shadow:
                0 0 16px rgba(255, 0, 0, 1),
                inset 0 0 8px rgba(255, 45, 45, .4);

            transition: .3s;
        }

        .logout-btn:hover {
            background: rgba(255, 45, 45, .15);
            transform: scale(1.05);
        }

        /* ===== CONTENEDOR ===== */
        .container {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;

            position: relative;
            z-index: 1;
        }

        /* ===== TARJETA ===== */
        .card {
            width: 100%;
            max-width: 520px;
            background: rgba(17, 24, 39, .8);
            backdrop-filter: blur(12px);
            padding: 28px;
            border-radius: 16px;
            color: #fff;

            box-shadow:
                0 0 25px rgba(42, 22, 218, .6),
                inset 0 0 20px rgba(42, 22, 218, .25);
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        select:focus,
        input:focus {
            outline: 2px solid #1F4E79;
        }

        .submit-btn {
            margin-top: 22px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #1F4E79;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;

            box-shadow: 0 6px 20px rgba(42, 22, 218, .8);
            transition: .2s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(42, 22, 218, .9);
        }

        /* ===== ANTICIPO ===== */
        .anticipo {
            margin-top: 28px;
            padding: 18px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .2);
            font-size: 14px;
        }

        .anticipo h4 {
            margin-top: 0;
            color: #fde68a;
            text-align: center;
        }

        .anticipo p {
            margin: 6px 0;
            text-align: center;
            color: #f1f1f1;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                text-align: center;
            }

            .back-btn {
                font-size: 32px;
            }

            .card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="header-left">
            <a href="{{ route('cliente.dashboard') }}" class="back-btn">←</a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn" type="submit">Cerrar sesión</button>
        </form>
    </header>

    <div class="container">

        <div class="card">

            <h2>AGENDAR CITAS</h2>
            <form method="POST" action="{{ route('cliente.citas.store') }}">
                @csrf

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

                <button type="submit" class="submit-btn">
                    AGENDAR CITA
                </button>
            </form>

            <div class="anticipo">
                <h4>⚠ Anticipo requerido</h4>
                <p>
                    Para confirmar tu cita es necesario realizar un <strong>anticipo del 50%</strong>.
                </p>

                <p>
                    <strong>Banco:</strong> BBVA<br>
                    <strong>Cuenta:</strong> 1234567890<br>
                    <strong>CLABE:</strong> 012345678901234567 <br>
                    <strong>Numero tel:</strong> 9614633455 <br>
                </p>

                <p style="font-size:13px; opacity:.85;">
                    Envía tu comprobante por WhatsApp para confirmar tu cita.
                </p>
            </div>

            <script>
        const servicio = document.getElementById('servicio');
        const fecha = document.getElementById('fecha');
        const horarios = document.getElementById('horarios');


        fecha.addEventListener('input', () => {
            if (!fecha.value) return;

            const d = new Date(fecha.value + 'T00:00:00').getDay();
            if (d === 0) {
                alert('Los domingos no se atiende');
                fecha.value = '';
                horarios.innerHTML = '<option value="">Selecciona un horario</option>';
            }
        });

        async function cargarBloques() {
            horarios.innerHTML = '<option>Cargando horarios...</option>';

            if (!servicio.value || !fecha.value) return;

            try {
                const res = await fetch(
                    `/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`
                );

                if (!res.ok) {
                    horarios.innerHTML = '<option>Error al cargar horarios</option>';
                    return;
                }

                const bloques = await res.json();
                horarios.innerHTML = '';

                if (bloques.length === 0) {
                    horarios.innerHTML = '<option>No hay horarios disponibles</option>';
                    return;
                }

                bloques.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.inicio;
                    opt.textContent = `${b.inicio} - ${b.fin}`;
                    horarios.appendChild(opt);
                });

            } catch (error) {
                horarios.innerHTML = '<option>Error al cargar horarios</option>';
            }
        }

        servicio.addEventListener('change', cargarBloques);
        fecha.addEventListener('change', cargarBloques);
        </script>




</body>

</html>
