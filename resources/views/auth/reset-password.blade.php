<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/auth/reset-password.css') }}">
</head>

<body class="auth-reset-password-page">
    <main class="reset-shell">
        <section class="reset-card">
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

            <h1>Nueva Contraseña</h1>
            <p class="lead">Define una contraseña segura para recuperar el acceso a tu cuenta.</p>

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

                <label for="email">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="tu@correo.com"
                    value="{{ old('email', $email ?? '') }}"
                    required
                    autocomplete="email"
                >

                <label for="password">Nueva contraseña</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Nueva contraseña"
                    required
                    autocomplete="new-password"
                >

                <label for="password_confirmation">Confirmar contraseña</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirmar contraseña"
                    required
                    autocomplete="new-password"
                >

                <button type="submit" class="submit-btn">Cambiar contraseña <span aria-hidden="true">→</span></button>
            </form>

            <a class="back-link" href="{{ route('login') }}">← Volver al inicio de sesión</a>
        </section>
    </main>
</body>

</html>
