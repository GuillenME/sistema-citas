<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>

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
            background: rgba(0, 0, 0, 0.75);
            z-index: 0;
        }

        .card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: rgba(17, 24, 39, 0.7);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            color: #fff;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fccc7c;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .error {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            background: rgba(255,255,255,0.9);
            color: #111827;
        }

        input:focus {
            outline: 2px solid #c0a799;
        }

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
            transition: 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px #cf997a;
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
        <h2>Nueva contraseña</h2>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <input
                type="email"
                name="email"
                placeholder="Correo electrónico"
                value="{{ old('email', $email ?? '') }}"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Nueva contraseña"
                required
            >

            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirmar contraseña"
                required
            >

            <button type="submit">CAMBIAR CONTRASEÑA</button>
        </form>
    </div>

</body>
</html>
