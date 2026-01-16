<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Promoción</title>
</head>
<body>

<h1>Crear Promoción</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li style="color:red">{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('admin.promociones.store') }}">
    @csrf

    <label>Título</label><br>
    <input type="text" name="titulo" value="{{ old('titulo') }}" required>
    <br><br>

    <label>Descripción</label><br>
    <textarea name="descripcion" required>{{ old('descripcion') }}</textarea>
    <br><br>

    <label>Descuento (%)</label><br>
    <input type="number" name="descuento" min="1" max="100" value="{{ old('descuento') }}" required>
    <br><br>

    <label>Fecha inicio</label><br>
    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
    <br><br>

    <label>Fecha fin</label><br>
    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
    <br><br>

    <label>
        <input type="checkbox" name="publicada" {{ old('publicada') ? 'checked' : '' }}>
        Publicar promoción
    </label>

    <br><br>

    <button type="submit">Guardar Promoción</button>
</form>

<br>
<a href="{{ route('admin.promociones.index') }}">← Volver</a>

</body>
</html>
