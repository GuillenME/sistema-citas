<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>

    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>

<body class="auth-register-page">

<a href="{{ route('login') }}" class="back-arrow">←</a>

<div class="blur-panel-left">
    <form method="POST" action="{{ route('register') }}" class="register-container">
        @csrf

        <h2>Registro de cliente</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid">
            <div class="card">
                <input type="text" name="nombre" placeholder="Nombre"
                       value="{{ old('nombre') }}"
                       class="@error('nombre') input-error @enderror"
                       oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
            </div>

            <div class="card">
                <input type="text" name="apellido" placeholder="Apellidos"
                       value="{{ old('apellido') }}"
                       class="@error('apellido') input-error @enderror"
                       oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
            </div>

            <div class="card">
                <input type="tel" name="telefono" placeholder="Teléfono (10 dígitos)"
                       value="{{ old('telefono') }}"
                       class="@error('telefono') input-error @enderror"
                       maxlength="10"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
            </div>

            <div class="card">
                <input type="email" name="email" placeholder="Correo electrónico"
                       value="{{ old('email') }}"
                       class="@error('email') input-error @enderror">
            </div>

            <div class="card">
                <input type="password" name="password" placeholder="Contraseña"
                       class="@error('password') input-error @enderror">
            </div>

            <div class="card">
                <input type="password" name="password_confirmation"
                       placeholder="Confirmar contraseña">
            </div>
        </div>

        <input type="hidden" name="role_id" value="2">

        <div class="submit-wrapper">
            <button type="submit">REGISTRARSE</button>
        </div>
    </form>
</div>

<div class="image-panel-right"></div>

</body>
</html>
