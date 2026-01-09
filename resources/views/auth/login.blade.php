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
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;

            /* ===== FONDO DE LADRILLOS OSCUROS (GRANDES) ===== */
            background-color: #1b1b1b;
            background-image:
                linear-gradient(90deg, rgba(255,255,255,0.05) 3px, transparent 3px),
                linear-gradient(rgba(255,255,255,0.05) 3px, transparent 3px),
                linear-gradient(
                    90deg,
                    transparent 44px,
                    rgba(0,0,0,0.5) 44px,
                    rgba(0,0,0,0.5) 48px,
                    transparent 48px
                ),
                linear-gradient(
                    transparent 44px,
                    rgba(0,0,0,0.5) 44px,
                    rgba(0,0,0,0.5) 48px,
                    transparent 48px
                );

            /* LADRILLOS MÁS GRANDES */
            background-size: 96px 48px;

            /* ===== CENTRADO ===== */
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* OVERLAY OSCURO PARA MEJOR CONTRASTE */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 0;
        }
        /* TUBO LED CURVO */
        .led-tube {
            position: absolute;
            top: 30px; /* 👈 antes 130px */
            left: 50%;
            transform: translateX(-50%);

            width: 420px;
            height: 120px;

            border: 6px solid #ff9f1c;
            border-bottom: none;
            border-radius: 220px 220px 0 0;

            box-shadow:
                0 0 10px #ff9f1c,
                0 0 20px #ff9f1c,
                0 0 40px #ff9f1c,
                0 0 80px rgb(253, 144, 2);

            pointer-events: none;
            z-index: 1;
        }
        
            /* LETRERO NEÓN (LÁMPARA LED) */
        .neon-sign {
            position: absolute;
            top: 80px;          /* Altura en la pared */
            left: 50%;
            transform: translateX(-50%);

            font-size: 64px;
            font-weight: bold;
            letter-spacing: 4px;
            font-family: 'Arial', sans-serif;

            color: #ff9f1c;

            text-shadow:
                0 0 5px #ffffff,
                0 0 10px #ffffff,
                0 0 20px #ffffff,
                0 0 40px #ffffff,
                0 0 80px #ffffff;

            pointer-events: none; /* NO afecta el login */
            z-index: 1;
        }
        .neon-text {
            position: absolute;
            top: 65px; /* 👈 antes 170px */
            left: 50%;
            transform: translateX(-50%);

            font-size: 52px;
            font-weight: bold;
            letter-spacing: 4px;

            color: #ff9f1c;

            text-shadow:
                0 0 5px #ffffff,
                0 0 10px #ffffff,
                0 0 20px #ffffff,
                0 0 40px #ffffff;

            pointer-events: none;
            z-index: 2;
        }


        @keyframes neon-flicker {
        0%, 100% { opacity: 1; }
        45% { opacity: 0.85; }
        50% { opacity: 0.6; }
        55% { opacity: 0.9; }
        }

        .led-tube,
        .neon-text {
            animation: neon-flicker 4s infinite;
        }



        /* TARJETA LOGIN (GLASS EFFECT) */
        .login-container {
            position: relative;
            z-index: 1;

            width: 360px;
            padding: 25px;

            margin-top: 140px; /* 👈 CLAVE */

            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 12px;

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            box-shadow: 0 8px 32px rgba(0,0,0,0.35);
            color: #fff;
        }


        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        /* ERRORES GENERALES */
        .error-box {
            background: rgba(254, 226, 226, 0.95);
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
        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        .login-container input:focus {
            outline: 2px solid #ff9f1c;
        }

        /* ERROR POR CAMPO */
        .field-error {
            color: #fecaca;
            font-size: 13px;
            margin-bottom: 10px;
            display: block;
        }

        /* BOTÓN */
        .login-container button {
            width: 100%;
            padding: 10px;
            background: #ff9f1c;
            border: none;
            border-radius: 6px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-container button:hover {
            background: #f89100;
        }

        /* REGISTRO */
        .register {
            text-align: center;
            margin-top: 15px;
        }

        .register a {
            color: #e0e7ff;
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

        <form method="POST" action="/login">
            @csrf

            <input 
                type="email" 
                name="email" 
                placeholder="Correo"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <input 
                type="password" 
                name="password" 
                placeholder="Contraseña" 
                required
            >
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <button type="submit">Entrar</button>
        </form>

        <div class="register">
            <a href="/register">Registrarse</a>
        </div>
    </div>

</body>
</html>
