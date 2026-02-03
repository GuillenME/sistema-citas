<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            background-image: url('{{ asset('imagenes/registro_fondo3.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        /* Oscurecer fondo general */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.25);
            z-index: 0;
        }

        /* ================= PANEL BORROSO DERECHO ================= */
        .blur-panel {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;

            display: flex;
            justify-content: center;
            align-items: center;

            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            background: rgba(0,0,0,0.25);

            z-index: 1;
        }

        /* ================= FLECHA REGRESO ================= */
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

        /* ================= CONTENEDOR LOGIN ================= */
        .login-wrapper {
            width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .neon-text {
            margin-top: -20px;
            font-size: 45px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #ffffff;
            text-shadow:
                0 0 6px #fccc7c,
                0 0 16px rgba(218, 117, 22, 0.8),
                0 0 32px rgba(218, 117, 22, 0.8);
            animation: neon-flicker 4s infinite;
        }

        @keyframes neon-flicker {
            0%, 100% { opacity: 1; }
            48% { opacity: .95; }
            50% { opacity: .85; }
            52% { opacity: 1; }
        }

        /* ================= TARJETA LOGIN ================= */
        .login-container {
            margin-top: 90px;
            width: 360px;
            padding: 28px;
            background: rgba(17, 24, 39, .25);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 14px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .45);
            color: #fff;
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* ================= ERRORES ================= */
        .error-box {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
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

        /* ================= INPUTS ================= */
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 6px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,.9);
            color: #111827;
        }

        input:focus {
            outline: 2px solid #1F4E79;
        }

        /* ================= BOTÓN ================= */
        button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            background: #8c4030;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 6px 20px #c0a799;
        }
        
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px #cf997a;
        }


        /* ================= LINKS ================= */
        .register {
            text-align: center;
            margin-top: 14px;
        }

        .register a {
            color: #e48815;
            text-decoration: none;
            font-size: 14px;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 900px) {
            .blur-panel {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Flecha -->
    <a href="{{ route('home') }}" class="back-arrow">←</a>

    <!-- PANEL BORROSO DERECHO -->
    <div class="blur-panel">
        <div class="login-wrapper">

            <div class="led-tube"></div>
            <div class="neon-text">Barbería & Spa</div>

            <div class="login-container">
                <h2>Iniciar sesión</h2>

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

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <input
                        type="text"
                        name="email"
                        placeholder="Correo"
                        value="{{ old('email') }}"
                        class="@error('email') input-error @enderror"
                    >

                    <input
                        type="password"
                        name="password"
                        placeholder="Contraseña"
                        class="@error('password') input-error @enderror"
                    >

                    <button type="submit">ENTRAR</button>
                </form>

                <div class="register">
                    <a href="{{ route('register') }}">Registrarse</a>
                </div>
                <div class="register">
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
