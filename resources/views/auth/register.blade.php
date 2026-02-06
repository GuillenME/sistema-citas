<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            display: flex;
            background: url('{{ asset("imagenes/registro_fondo3.png") }}') center/cover no-repeat;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.25);
            z-index: 0;
        }

        /* PANEL IZQUIERDO BORROSO */
        .blur-panel-left {
            width: 50%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 80px;
            backdrop-filter: blur(14px);
            background: rgba(0,0,0,.25);
            z-index: 1;
            overflow-y: auto;
        }

        /* PANEL DERECHO IMAGEN */
        .image-panel-right {
            width: 30%;
            height: 100%;
            z-index: 1;
        }

        /* FLECHA */
        .back-arrow {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 5;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(17,24,39,.1);
            border: 1px solid rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fccc7c;
            text-decoration: none;
            font-size: 22px;
            backdrop-filter: blur(8px);
            box-shadow: 0 0 15px #e48815;
            transition: .25s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            background: #f88b07;
            color: #fff;
        }

        /* CONTENEDOR */
        .register-container {
            width: 80%;
            max-width: 900px;
            background: rgba(255,255,255,.08);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(12px);
        }

        h2 {
            margin-bottom: 25px;
            color: #fccc7c;
        }

        /* GRID → 2 COLUMNAS */
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .card {
            background: rgba(255,255,255,.08);
            border-radius: 16px;
            padding: 16px;
            backdrop-filter: blur(6px);
            transition: transform .25s, box-shadow .25s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,.35);
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,.9);
            color: #111827;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #8c4030, 0 0 18px rgba(228,136,21,.6);
        }

        /* ERRORES */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .input-error {
            background: #fee2e2 !important;
            box-shadow: 0 0 0 2px #ef4444 !important;
        }

        /* BOTÓN */
        .submit-wrapper {
            margin-top: 35px;
            text-align: center;
        }

        .submit-wrapper button {
            width: 240px;
            padding: 14px;
            background: linear-gradient(135deg, #642d22, #8c4030);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255,255,255,.6);
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .blur-panel-left,
            .image-panel-right {
                width: 100%;
            }

            .image-panel-right {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<a href="{{ route('login') }}" class="back-arrow">←</a>

<div class="blur-panel-left">
    <form method="POST" action="{{ route('register') }}" class="register-container">
        @csrf

        <h2>Registro de cliente</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid">
            <div class="card">
                <input type="text" name="nombre" placeholder="Nombre"
                       value="{{ old('nombre') }}"
                       class="@error('nombre') input-error @enderror"
                       oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
            </div>

            <div class="card">
                <input type="text" name="apellido" placeholder="Apellidos"
                       value="{{ old('apellido') }}"
                       class="@error('apellido') input-error @enderror"
                       oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
            </div>

            <div class="card">
                <input type="tel" name="telefono" placeholder="Teléfono (10 dígitos)"
                       value="{{ old('telefono') }}"
                       class="@error('telefono') input-error @enderror"
                       maxlength="10"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
            </div>

            <div class="card">
                <input type="email" name="email" placeholder="Correo electrónico"
                       value="{{ old('email') }}"
                       class="@error('email') input-error @enderror">
            </div>

            <div class="card">
                <input type="password" name="password" placeholder="Contraseña"
                       class="@error('password') input-error @enderror">
            </div>

            <div class="card">
                <input type="password" name="password_confirmation"
                       placeholder="Confirmar contraseña">
            </div>
        </div>

        <input type="hidden" name="role_id" value="2">

        <div class="submit-wrapper">
            <button type="submit">REGISTRARSE</button>
        </div>
    </form>
</div>

<div class="image-panel-right"></div>

</body>
</html>
