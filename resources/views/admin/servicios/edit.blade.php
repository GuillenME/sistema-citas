<h1>Editar servicio</h1>

<form method="POST" action="{{ route('admin.servicios.update', $servicio) }}">
    @csrf
    @method('PUT')

    <label>Nombre</label>
    <input type="text" name="nombre" value="{{ $servicio->nombre }}">

    <label>Descripción</label>
    <textarea name="descripcion">{{ $servicio->descripcion }}</textarea>

    <label>Duración (min)</label>
    <input type="number" name="duracion_minutos" value="{{ $servicio->duracion_minutos }}">

    <label>Precio</label>
    <input type="number" step="0.01" name="precio" value="{{ $servicio->precio }}">

    <label>Activo</label>
    <select name="activo">
        <option value="1" {{ $servicio->activo ? 'selected' : '' }}>Sí</option>
        <option value="0" {{ !$servicio->activo ? 'selected' : '' }}>No</option>
    </select>

    <button type="submit">Actualizar</button>
</form>
