<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <h2>Nueva contraseña</h2>

    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Nueva contraseña" required>
    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>

    <button type="submit">Cambiar contraseña</button>
</form>
