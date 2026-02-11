<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/auth/reset-password.css') }}">
</head>

<body class="auth-reset-password-page">

    <div class="card">
        <h2>Nueva contraseña</h2>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="error">
                <ul>
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
