<h1>Nuevo servicio</h1>

<form method="POST" action="{{ route('admin.servicios.store') }}">
    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre">

    <label>Descripción</label>
    <textarea name="descripcion"></textarea>

    <label>Duración (min)</label>
    <input type="number" name="duracion_minutos">

    <label>Precio</label>
    <input type="number" step="0.01" name="precio">

    <button type="submit">Guardar</button>
</form>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>