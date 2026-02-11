<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>

    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body class="auth-login-page">

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
