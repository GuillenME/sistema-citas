<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body class="auth-login-page">
    <a href="{{ route('home') }}" class="back-arrow" aria-label="Volver al inicio">←</a>

    <main class="login-layout">
        <section class="hero-panel">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <div class="brand-led">{{ optional($homeSetting)->hero_title ?? 'BARBERÍA & SPA' }}</div>
                <h1 class="hero-subtitle">
                    <span class="accent-text">Estilo</span>, cuidado y bienestar en 
                    <span class="accent-text">un solo lugar</span>
                </h1>
                <p>Reserva tu lugar en el santuario de la elegancia y la tradición. Donde cada detalle cuenta.</p>
            </div>
        </section>

        <section class="form-panel">
            <div class="login-container">
                <h2>Bienvenido</h2>
                <p class="subtitle">Tu experiencia de cuidado personal comienza aquí.</p>

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

                    <label for="email">Correo electrónico</label>
                    <input
                        id="email"
                        type="text"
                        name="email"
                        placeholder="nombre@ejemplo.com"
                        value="{{ old('email') }}"
                        class="@error('email') input-error @enderror"
                    >

                    <div class="password-label-row">
                        <label for="password">Contraseña</label>
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div class="password-field">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Contraseña"
                            class="@error('password') input-error @enderror"
                        >
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Mostrar contraseña" aria-pressed="false">
                            <svg class="eye-icon eye-open" id="eyeOpen" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <svg class="eye-icon eye-closed hidden" id="eyeClosed" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M3 3L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M10.58 10.58C10.21 10.95 10 11.46 10 12C10 13.1 10.9 14 12 14C12.54 14 13.05 13.79 13.42 13.42" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9.88 5.09C10.56 4.9 11.27 4.8 12 4.8C19 4.8 23 12 23 12C22.39 13.14 21.62 14.19 20.7 15.11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.61 6.61C4.46 8.07 2.89 10.2 1 12C1.61 13.14 2.38 14.19 3.3 15.11C5.39 17.2 8.15 18.4 12 18.4C13.17 18.4 14.24 18.24 15.21 17.93" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <button type="submit" class="submit-btn">Entrar</button>
                </form>

                <div class="register">
                    ¿No tienes una cuenta? <a href="{{ route('register') }}">Registrarse</a>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (!passwordInput || !toggleButton || !eyeOpen || !eyeClosed) return;

            toggleButton.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleButton.setAttribute('aria-pressed', String(isPassword));
                toggleButton.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
                eyeOpen.classList.toggle('hidden', isPassword);
                eyeClosed.classList.toggle('hidden', !isPassword);
            });
        });
    </script>
</body>

</html>
