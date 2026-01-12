<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>

    <style>
        * {
            box-sizing: border-box;
        }
body {
    font-family: Arial, sans-serif;
    background-image: url('{{ asset("imagenes/registro_fondo.png") }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

        /* Tarjeta */
        .barber-card {
            background: rgba(15,15,15,.95);
            width: 380px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 25px 60px rgba(0,0,0,.6);
            color: #fff;
        }

        /* Título */
        .barber-card h2 {
            text-align: center;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        /* Inputss */
        .barber-card input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #444;
            background: transparent;
            color: #fff;
            font-size: 14px;
        }

        .barber-card input::placeholder {
            color: #aaa;
        }

        .barber-card input:focus {
            outline: none;
            border-color: #ff8c00;
            box-shadow: 0 0 6px rgba(255,140,0,.4);
        }

        /* Botón */
        .barber-card button {
            width: 100%;
            padding: 12px;
            background: #ff8c00;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .barber-card button:hover {
            background: #e67e00;
        }
    </style>
</head>
<body>

    <div class="barber-card">

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

    </div>

</body>
</html>
