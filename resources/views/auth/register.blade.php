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
            background: url('{{ asset("imagenes/registro_fondo3.png") }}') center/cover no-repeat;
            padding: 40px;
            position: relative;
            color: #e48815;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.35);
            z-index: -1;
        }

        /* Flecha */
        .back-arrow {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 5;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(17, 24, 39, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fccc7c;
            text-decoration: none;
            font-size: 22px;
            backdrop-filter: blur(8px);
            box-shadow: 0 0 15px #e48815;
            transition: .25s ease;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            background: #f88b07;
            box-shadow: 0 0 25px #e48815;
            color: #ffffff;
        }

        .register-container {
            max-width: 1100px;
            margin: 80px auto 0;
            background: rgba(80, 73, 34, 0.589);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(12px);
        }

        h2 { margin-bottom: 30px; }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .card {
            background: #be743b;
            border-radius: 14px;
            padding: 20px;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 14px;
            color: #000000;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
        }

        .field-error {
            color: #fecaca;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .submit-wrapper {
            margin-top: 35px;
            text-align: center;
        }

        .submit-wrapper button {
            width: 220px;
            padding: 12px;
            background: #8c4030;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 6px 20px #c0a799;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px #cf997a;
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

    @if ($errors->any())
        <div class="field-error" style="margin-bottom:20px;">
            Por favor corrige los campos marcados en rojo.
        </div>
    @endif

    <div class="grid">

        <div class="card">
            <h3>Nombre</h3>
            <input type="text"
                   name="nombre"
                   value="{{ old('nombre') }}"
                   required
                   inputmode="text"
                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                   oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
            @error('nombre') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="card">
            <h3>Apellidos</h3>
            <input type="text"
                   name="apellido"
                   value="{{ old('apellido') }}"
                   required
                   inputmode="text"
                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                   oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
            @error('apellido') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="card">
            <h3>Teléfono</h3>
            <input type="tel"
                   name="telefono"
                   value="{{ old('telefono') }}"
                   required
                   inputmode="numeric"
                   maxlength="10"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
            @error('telefono') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="card">
            <h3>Correo</h3>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="card">
            <h3>Contraseña</h3>
            <input type="password" name="password" required>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="card">
            <h3>Confirmar contraseña</h3>
            <input type="password" name="password_confirmation" required>
        </div>

    </div>

    <input type="hidden" name="role_id" value="2">

    <div class="submit-wrapper">
        <button type="submit">REGISTRARSE</button>
    </div>

</form>

</body>
</html>
