<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios | Barbería & Spa</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #1e1b4b, #020617);
            color:#e5e7eb;
        }

        header{
            padding:20px 40px;
            background:#020617;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        header a{
            color:#93c5fd;
            text-decoration:none;
            font-weight:bold;
        }

        section{
            max-width:1200px;
            margin:auto;
            padding:80px 20px;
        }

        h1{
            text-align:center;
            margin-bottom:50px;
            text-shadow:0 0 15px rgba(99,102,241,.8);
        }

        .services{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:30px;
        }

        .card{
            background: rgba(17, 24, 39, 0.85);
            border-radius:16px;
            padding:30px;
            border:1px solid rgba(255,255,255,.15);
            transition:.3s;
        }

        .card:hover{
            transform: translateY(-6px);
            box-shadow:0 0 25px rgba(99,102,241,.5);
        }

        .card h3{
            color:#93c5fd;
            margin-bottom:10px;
        }

        .price{
            color:#4ade80;
            font-weight:bold;
            margin-top:10px;
        }

        .duration{
            font-size:13px;
            opacity:.85;
        }

        .btn{
            display:inline-block;
            margin-top:15px;
            padding:10px 16px;
            border-radius:10px;
            border:1px solid #22c55e;
            color:#fff;
            text-decoration:none;
            font-size:13px;
            box-shadow:0 0 12px rgba(34,197,94,.6);
        }

        .btn:hover{
            box-shadow:0 0 20px rgba(34,197,94,1);
        }
    </style>
</head>

<body>

<header>
    <div>Barbería & Spa</div>
    <a href="{{ route('home') }}">← Volver al inicio</a>
</header>

<section>
    <h1>Nuestros Servicios</h1>

    <div class="services">
        @forelse($servicios as $servicio)
            <div class="card">
                <h3>{{ $servicio->nombre }}</h3>
                <p>{{ $servicio->descripcion }}</p>
                <p class="duration">Duración: {{ $servicio->duracion_minutos }} min</p>
                <p class="price">${{ number_format($servicio->precio, 2) }}</p>

                <a href="#" class="btn">Agendar servicio</a>
            </div>
        @empty
            <p>No hay servicios disponibles.</p>
        @endforelse
    </div>
</section>

</body>
</html>
