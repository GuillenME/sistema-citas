<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            padding: 20px;
        }
        .card {
            background: #020617;
            padding: 25px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
        }
        h1 {
            color: #facc15;
        }
        .btn {
            display: inline-block;
            background: #22c55e;
            color: #000;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🎉 Nueva Promoción</h1>

        <p>Hola <strong>{{ $user->name }}</strong>,</p>

        <p>{{ $promo->titulo }}</p>
        <p>{{ $promo->descripcion }}</p>

        @if($promo->descuento)
            <p><strong>Descuento:</strong> {{ $promo->descuento }}%</p>
        @endif

        <a href="{{ url('/promociones') }}" class="btn">Ver promoción</a>

        <p style="margin-top:20px;">Barbería & Spa ✂️</p>
    </div>
</body>
</html>
