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

            background-color: #111827;
            background-image:
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 2px, transparent 2px),
                linear-gradient(rgba(255, 255, 255, 0.03) 2px, transparent 2px);
            background-size: 80px 40px;

            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 0;
        }

        /* LED */
        .led-tube {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 420px;
            height: 120px;
            border: 5px solid #ff9f1c;
            border-bottom: none;
            border-radius: 220px 220px 0 0;
            box-shadow: 0 0 12px #ff9f1c, 0 0 32px rgba(255, 159, 28, .8);
            z-index: 1;
            animation: neon-flicker 4s infinite;
        }

        .neon-text {
            position: absolute;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #ff9f1c;
            text-shadow:
                0 0 6px #ff9f1c,
                0 0 16px rgba(255, 159, 28, .8),
                0 0 32px rgba(255, 159, 28, .6);
            z-index: 2;
            animation: neon-flicker 4s infinite;
        }

        @keyframes neon-flicker {

            0%,
            100% {
                opacity: 1;
            }

            48% {
                opacity: .95;
            }

            50% {
                opacity: .85;
            }

            52% {
                opacity: 1;
            }
        }

        /* CARD */
        .login-container {
            position: relative;
            z-index: 1;
            width: 360px;
            padding: 28px;
            margin-top: 140px;
            background: rgba(17, 24, 39, .65);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 14px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .45);
            color: #fff;
        }

        .login-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 24px;
            right: 24px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ff9f1c, transparent);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* ERRORES */
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

        /* INPUTS */
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
            outline: 2px solid #ff9f1c;
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

        /* BUTTON */
        button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            background: #ff9f1c;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 159, 28, .5);
            transition: .2s;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(255, 159, 28, .6);
            background: #f89100;
        }

        .register {
            text-align: center;
            margin-top: 16px;
        }

        .register a {
            color: #e5e7eb;
            text-decoration: none;
            font-size: 14px;
        }

        .register a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="led-tube"></div>
    <div class="neon-text">BARBERÍA</div>

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

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <input type="text" name="email" placeholder="Correo" value="{{ old('email') }}"
                class="@error('email') input-error @enderror">
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input type="password" name="password" placeholder="Contraseña"
                class="@error('password') input-error @enderror">
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <button type="submit">ENTRAR</button>
        </form>


        <div class="register">
            <a href="{{ route('register') }}">Registrarse</a>
        </div>
    </div>

</body>

</html>
