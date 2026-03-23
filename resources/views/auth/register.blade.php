<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="auth-register-page">
    <main class="register-layout">
        <section class="register-hero" aria-hidden="true">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-brand">{{ optional($homeSetting)->hero_title ?? 'Barbería & Spa' }}</span>
                <h1>Eleva tu estilo a un nuevo nivel de <em>distinción.</em></h1>
                <p>Descubre el equilibrio perfecto entre la tradición de la barbería clásica y el relax de un spa
                    moderno.</p>
            </div>
        </section>

        <section class="register-panel">
            <a href="{{ route('login') }}" class="back-arrow" aria-label="Volver">&larr;</a>

            <div class="register-header">
                <h2>Registro de Cliente</h2>
                <p>{{ optional($homeSetting)->register_subtitle ?? 'Unete a nuestra comunidad exclusiva y reserva tu proxima experiencia de lujo.' }}</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf

                <div class="field-grid">
                    <div class="field-group">
                        <label for="nombre">Nombre</label>
                        <input id="nombre" type="text" name="nombre" placeholder="Ej. Juan"
                            value="{{ old('nombre') }}" class="@error('nombre') input-error @enderror"
                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
                    </div>

                    <div class="field-group">
                        <label for="apellido">Apellido</label>
                        <input id="apellido" type="text" name="apellido" placeholder="Ej. Perez"
                            value="{{ old('apellido') }}" class="@error('apellido') input-error @enderror"
                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
                    </div>
                </div>

                <div class="field-group">
                    <label for="telefono">Teléfono (10 dígitos)</label>
                    <input id="telefono" type="tel" name="telefono" placeholder="55 1234 5678"
                        value="{{ old('telefono') }}" class="@error('telefono') input-error @enderror" maxlength="10"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                </div>

                <div class="field-group">
                    <label for="email">Correo Electrónico</label>
                    <input id="email" type="email" name="email" placeholder="nombre@ejemplo.com"
                        value="{{ old('email') }}" class="@error('email') input-error @enderror">
                </div>

                <div class="field-group">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input id="password" type="password" name="password" placeholder="********"
                            class="@error('password') input-error @enderror">
                        <button type="button" class="toggle-password" data-target="password"
                            aria-label="Mostrar contraseña">
                            <i class="fa-regular fa-eye"></i>
                        </button>

                    </div>
                </div>

                <div class="field-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <div class="password-wrapper">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="********">

                        <button type="button" class="toggle-password" data-target="password_confirmation"
                            aria-label="Mostrar confirmación de contraseña">

                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>


                <input type="hidden" name="role_id" value="2">
                <button type="submit" class="submit-button">REGISTRARSE</button>
            </form>

            <p class="login-link">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
            </p>
        </section>
    </main>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {

                var targetId = button.getAttribute('data-target');
                var input = document.getElementById(targetId);
                if (!input) return;

                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                var icon = button.querySelector('i');

                if (isPassword) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }

            });
        });
    </script>

</body>

</html>
