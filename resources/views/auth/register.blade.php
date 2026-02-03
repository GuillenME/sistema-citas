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

        h2 { margin-bottom: 25px; }

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
            color: #000;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,.9);
            color: #111827;
        }

        /* ===== ERRORES (IGUAL QUE LOGIN) ===== */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .input-error {
            outline: 2px solid #ef4444 !important;
            background: #fee2e2;
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

<form method="POST"
      action="{{ route('register') }}"
      class="register-container"
      novalidate>
    @csrf

    <h2>Registro de cliente</h2>

    {{-- ERRORES GENERALES --}}
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

        <!-- NOMBRE -->
        <div class="card">
            <h3>Nombre</h3>
            <input type="text"
                   name="nombre"
                   value="{{ old('nombre') }}"
                   class="@error('nombre') input-error @enderror"
                   inputmode="text"
                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                   oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
        </div>

        <!-- APELLIDOS -->
        <div class="card">
            <h3>Apellidos</h3>
            <input type="text"
                   name="apellido"
                   value="{{ old('apellido') }}"
                   class="@error('apellido') input-error @enderror"
                   inputmode="text"
                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                   oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
        </div>

        <!-- TELÉFONO -->
        <div class="card">
            <h3>Teléfono</h3>
            <input type="tel"
                   name="telefono"
                   value="{{ old('telefono') }}"
                   class="@error('telefono') input-error @enderror"
                   inputmode="numeric"
                   maxlength="10"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
        </div>

        <!-- CORREO -->
        <div class="card">
            <h3>Correo</h3>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="@error('email') input-error @enderror">
        </div>

        <!-- CONTRASEÑA -->
        <div class="card">
            <h3>Contraseña</h3>
            <input type="password"
                   name="password"
                   class="@error('password') input-error @enderror">
        </div>

        <!-- CONFIRMAR -->
        <div class="card">
            <h3>Confirmar contraseña</h3>
            <input type="password" name="password_confirmation">
        </div>

    </div>

    <input type="hidden" name="role_id" value="2">

    <div class="submit-wrapper">
        <button type="submit">REGISTRARSE</button>
    </div>

</form>

</body>
</html>
