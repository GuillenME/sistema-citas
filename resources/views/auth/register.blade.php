<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            min-height: 100vh;

            background-image: url('{{ asset('imagenes/registro_fondo.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 0;
        }

        /* ================= FLECHA REGRESO ================= */
        .back-arrow {
            position: absolute;
            top: 25px;
            left: 25px;
            z-index: 3;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(17, 24, 39, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93c5fd;
            text-decoration: none;
            font-size: 22px;
            backdrop-filter: blur(8px);
            box-shadow: 0 0 15px rgba(42, 22, 218, 0.6);
            transition: 0.25s ease;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
            background: rgba(31, 78, 121, 0.85);
            box-shadow: 0 0 25px rgba(42, 22, 218, 0.9);
            color: #ffffff;
        }

        /* ================= CARD ================= */
        .register-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            padding: 28px;
            background: rgba(17, 24, 39, .65);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 14px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .45);
            color: #fff;
        }

        .register-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 24px;
            right: 24px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #1F4E79, transparent);
        }

        h2 {
            text-align: center;
            margin-bottom: 18px;
            font-weight: 600;
        }

        /* ================= ERRORES ================= */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
             f4da8bc3d565cd1aa69a27bcafce1b9469b0e818
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        /* ================= INPUTS ================= */
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 6px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255, 255, 255, .9);
            color: #111827;
        }

        input::placeholder {
            color: #6b7280;
        }

        input:focus {
            outline: 2px solid #1F4E79;
        }

        .input-error {
            outline: 2px solid #ef4444 !important;
            background: #fee2e2;
        }

        .field-error {
            color: #fecaca;
            font-size: 13px;
            margin-bottom: 10px;
            display: block;
        }

        /* ================= BUTTON ================= */
        button {
            width: 100%;
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
            transition: .2s;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(42, 22, 218, 0.8);
        }

        .login {
            text-align: center;
            margin-top: 16px;
        }

        .login a {
            color: #e5e7eb;
            text-decoration: none;
            font-size: 14px;
        }

        .login a:hover {
            text-decoration: underline;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            body {
                padding: 20px;
                height: auto;
            }

            .register-container {
                padding: 22px;
            }

            h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 18px;
                border-radius: 12px;
            }

            input {
                font-size: 13px;
                padding: 11px;
            }

            button {
                font-size: 14px;
                padding: 11px;
            }

            .login a {
                font-size: 13px;
            }
        }

        @media (max-height: 600px) {
            body {
                align-items: flex-start;
                padding-top: 40px;
                padding-bottom: 40px;
            }
        }
    </style>
</head>

<body>

    <!-- FLECHA REGRESO -->
    <a href="{{ url('/') }}" class="back-arrow" title="Volver al inicio">←</a>

    <div class="register-container">

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

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}"
                class="@error('nombre') input-error @enderror">
            @error('nombre')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="text" name="apellido" placeholder="Apellidos" value="{{ old('apellido') }}"
                class="@error('apellido') input-error @enderror">

            <input type="tel" name="telefono" placeholder="Teléfono (10 dígitos)" maxlength="10"
                inputmode="numeric"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)"
                value="{{ old('telefono') }}"
                class="@error('telefono') input-error @enderror">
            @error('telefono')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}"
                class="@error('email') input-error @enderror">
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="password" name="password" placeholder="Contraseña"
                class="@error('password') input-error @enderror">
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña">

            <!-- Rol fijo -->
            <input type="hidden" name="rol_id" value="2">

            <button type="submit">REGISTRARSE</button>
        </form>

        <div class="login">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
        </div>

    </div>

</body>

</html>
