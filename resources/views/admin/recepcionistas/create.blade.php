<h2>Nuevo recepcionista</h2>

<form method="POST" action="{{ route('admin.recepcionistas.store') }}">
    @csrf

    <input name="nombre" placeholder="Nombre" required>
    <input name="apellido" placeholder="Apellido">
    <input name="email" placeholder="Email" required>
    <input name="telefono" placeholder="Teléfono">
    <input type="password" name="password" placeholder="Contraseña" required>

    <button>Guardar</button>
</form>
