<h2>Iniciar sesión</h2>

<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>

    <button type="submit">Entrar</button>
</form>

<a href="/register">Registrarse</a>
