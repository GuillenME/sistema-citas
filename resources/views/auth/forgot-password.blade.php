<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/auth/forgot-password.css') }}">
</head>

<body class="auth-forgot-password-page">

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
