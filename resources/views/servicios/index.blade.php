<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Barbería & Spa</title>

<style>
* { box-sizing: border-box; }

html{
    scroll-behavior: smooth;
}

body{
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0f172a;
    color: #e5e7eb;
}

/* ================= HEADER ================= */
header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    background: rgba(2, 6, 23, 0.95);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 40px;
    z-index: 1000;
    backdrop-filter: blur(6px);
}

.logo{
    font-weight: bold;
    font-size: 25px;
    letter-spacing: 2px;
    color: #93c5fd;
}

nav a{
    margin: 0 14px;
    color: #e5e7eb;
    text-decoration: none;
    font-weight: bold;
    transition: .3s;
}

nav a:hover{
    color: #93c5fd;
}

.login-icon {
    display: inline-flex;
    align-items: center;
}

.icon-img {
    width: 55px;
    height: 55px;
    object-fit: contain;
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
    @livewireStyles
</head>

<body>

<header>
    <div class="logo">Barbería & Spa</div>

    <nav>
        <a href="#inicio">Inicio</a>
        <a href="{{ route('servicios') }}">Servicios</a>
        <a href="#promos">Promociones</a>
        <a href="#contacto">Contacto</a>
        <a href="#noticias">Noticias & Novedades</a>
    </nav>

   <a href="{{ route('login') }}" class="login-icon">
    <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesión" class="icon-img">
</a>
</header>
<section>
    <h1>Nuestros Servicios</h1>

    <div class="services">
       @forelse($servicios as $servicio)
    <div class="card">
        <h3>{{ $servicio->name }}</h3>
        <p>{{ $servicio->description }}</p>
        <p class="duration">Duración: {{ $servicio->duration_minutes }} min</p>
        <p class="price">${{ number_format($servicio->price, 2) }}</p>
        <a href="#" class="btn">Agendar servicio</a>
    </div>
@empty
    <p>No hay servicios disponibles.</p>
@endforelse
    </div>
</section>

</body>
</html>
