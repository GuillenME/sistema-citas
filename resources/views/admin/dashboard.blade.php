<h1>Administrador</h1>
<p>Acceso total</p>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>