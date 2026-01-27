<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: url('{{ asset('imagenes/registro_fondo.png') }}') center/cover no-repeat;
            padding: 40px;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.25);
            z-index: -1;
        }

        .back-arrow {
            position: absolute;
            top: 25px;
            left: 25px;
            z-index: 10;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(17,24,39,.45);
            border: 1px solid rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93c5fd;
            text-decoration: none;
            font-size: 22px;
        }

        .register-container {
            max-width: 1100px;
            margin: 80px auto 0;
            background: rgba(17,24,39,.15);
            border-radius: 16px;
            padding: 30px;
            color: #fff;
            backdrop-filter: blur(12px);
        }

        h2 { margin-bottom: 30px; }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .card {
            background: rgba(17,24,39,.45);
            border-radius: 14px;
            padding: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
        }

        .submit-wrapper {
            margin-top: 35px;
            text-align: center;
        }

        .submit-wrapper button {
            width: 20%;
            padding: 12px;
            margin-top: 12px;
            background: #1F4E79;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(42, 22, 218, 0.8);
        }


        @media (max-width: 900px) {
            .grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<a href="{{ route('login') }}" class="back-arrow">←</a>

<form method="POST" action="{{ route('register') }}" class="register-container">
@csrf

<h2>Registro de cliente</h2>

<div class="grid">

    <div class="card">
        <h3>Nombre</h3>
        <input type="text" name="nombre" value="{{ old('nombre') }}">
    </div>

    <div class="card">
        <h3>Apellidos</h3>
        <input type="text" name="apellido" value="{{ old('apellido') }}">
    </div>

    <div class="card">
        <h3>Teléfono</h3>
        <input type="tel" name="telefono" value="{{ old('telefono') }}">
    </div>

    <div class="card">
        <h3>Correo</h3>
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="card">
        <h3>Contraseña</h3>
        <input type="password" name="password">
    </div>

    <div class="card">
        <h3>Confirmar contraseña</h3>
        <input type="password" name="password_confirmation">
    </div>

</div>

<div class="submit-wrapper">
    <input type="hidden" name="rol_id" value="2">
    <button type="submit">REGISTRARSE</button>
</div>

</form>

</body>
</html>
