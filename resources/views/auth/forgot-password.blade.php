<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;

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
            background: rgba(0, 0, 0, 0.25);
            z-index: 0;
        }


        .card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 380px;
            padding: 30px;
            background: rgba(17, 24, 39, .25);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            color: #fff;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #93c5fd;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .success {
            background: #dcfce7;
            border: 1px solid #22c55e;
            color: #166534;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 8px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 10px;
        }


        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,0.9);
            color: #111827;
        }

        input:focus {
            outline: 2px solid #1F4E79;
        }

        /* ===== BOTÓN ===== */
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
            box-shadow: 0 6px 20px rgba(42, 22, 218, 0.7);
            transition: 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(42, 22, 218, 0.8);
        }

        .back {
            text-align: center;
            margin-top: 18px;
        }

        .back a {
            color: #e5e7eb;
            font-size: 14px;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .card {
                padding: 24px;
            }
        }
    </style>
</head>

<body style="background-image: url('{{ asset('imagenes/SalaEsperaa.png') }}');">

    <div class="card">
        <h2>Recuperar contraseña</h2>

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            @if(session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            <input
                type="email"
                name="email"
                placeholder="Correo electrónico"
                value="{{ old('email') }}"
                required
            >

            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">ENVIAR ENLACE</button>
        </form>

        <div class="back">
            <a href="{{ route('login') }}">Volver al inicio de sesión</a>
        </div>
    </div>

</body>
</html>
