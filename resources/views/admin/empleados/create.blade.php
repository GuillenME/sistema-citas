<h2>Nuevo empleado</h2>

<form method="POST" action="{{ route('admin.empleados.store') }}">
    @csrf

    <input type="text" name="nombre" placeholder="Nombre" required>
    <br><br>

    <input type="text" name="telefono" placeholder="Teléfono" required>
    <br><br>

    <input type="text" name="especialidad" placeholder="Especialidad" required>
    <br><br>

    <button type="submit">Guardar</button>
</form>

<a href="{{ route('admin.empleados.index') }}">⬅ Volver</a>
