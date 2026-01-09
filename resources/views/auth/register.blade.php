<h2>Registro de cliente</h2>

<form method="POST" action="/register">
    @csrf

    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>

    <!-- Rol fijo -->
    <input type="hidden" name="rol_id" value="3">

    <button type="submit">Registrarte</button>
</form>
