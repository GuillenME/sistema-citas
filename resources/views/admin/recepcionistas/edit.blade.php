<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar recepcionista</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            padding: 40px;
        }

        .card {
            max-width: 420px;
            margin: auto;
            background: rgba(17,24,39,.9);
            padding: 25px;
            border-radius: 14px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
        }

        input {
            width: 100%;
            margin-top: 6px;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }

        button {
            margin-top: 22px;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #3b82f6;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #93c5fd;
            text-decoration: none;
        }

        .success {
            background: #16a34a;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            text-align: center;
        }

        .error {
            background: #dc2626;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Editar recepcionista</h2>

    {{-- Mensajes --}}
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.recepcionistas.update', $usuario) }}">
        @csrf
        @method('PUT')

        <label>Nombre</label>
        <input
            type="text"
            name="nombre"
            value="{{ old('nombre', $usuario->nombre) }}"
            required
        >

        <label>Apellido</label>
        <input
            type="text"
            name="apellido"
            value="{{ old('apellido', $usuario->apellido) }}"
        >

        <label>Email</label>
        <input
            type="email"
            value="{{ $usuario->email }}"
            disabled
        >

        <label>Teléfono</label>
        <input
            type="text"
            name="telefono"
            value="{{ old('telefono', $usuario->telefono) }}"
        >

        <button>Actualizar datos</button>
    </form>

    <a href="{{ route('admin.recepcionistas.index') }}" class="back">
        ⬅ Volver al listado
    </a>
</div>

</body>
</html>
