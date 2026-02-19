<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/auth/forgot-password.css') }}">
</head>

<body class="auth-forgot-password-page">
    <main class="recovery-shell">
        <section class="recovery-card">
            <div class="brand-wrap">
                @if (optional($homeSetting)->navbar_logo)
                    <img
                        src="{{ asset('storage/' . $homeSetting->navbar_logo) }}"
                        alt="Logo Barbería & Spa"
                        class="brand-logo"
                    >
                @else
                    <div class="brand-fallback">{{ optional($homeSetting)->hero_title ?? 'BARBERÍA & SPA' }}</div>
                @endif
            </div>

            <h1>Recuperar Contraseña</h1>
            <p class="lead">Ingresa tu correo electrónico para enviarte un enlace de recuperación.</p>

            @if (session('status'))
                <div class="success">{{ session('status') }}</div>
            @endif

            @if (session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf

                <label for="email">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-icon" aria-hidden="true">✉</span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="tu@correo.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                </div>

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit" class="submit-btn">Enviar enlace <span aria-hidden="true"></span></button>
            </form>

            <a class="back-link" href="{{ route('login') }}">Volver al inicio de sesión</a>
        </section>
    </main>
</body>

</html>
