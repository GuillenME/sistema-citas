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
    background: rgba(6, 13, 46, 0.95);
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

.login-icon{
    font-size: 30px;
    color: #22c55e;
    text-decoration: none;
}

/* ================= HERO ================= */
.hero{
    height: 100vh;
    background:
        linear-gradient(rgba(0,0,0,.25), rgba(0,0,0,.85)),
        url("{{ asset('imagenes/registro_fondo.png') }}") center/cover no-repeat;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding-top: 70px;
}

.hero h1{
    font-size: 120px;
    letter-spacing: 15px;
    line-height: 1.1;
    margin-bottom: 20px;
}
.hero p{
    font-size: 32px;
    max-width: 800px;
    color: #cbd5f5;
    margin-bottom: 30px;
}
.hero .subtitle{
    font-size: 24px;
    color: #93c5fd;
    margin-bottom: 40px;
    font-style: italic;
}

.hero .features{
    display: flex;
    gap: 40px;
    margin-top: 40px;
}

.hero .feature{
    background: rgba(17, 24, 39, 0.8);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
}

.hero .feature h3{
    color: #22c55e;
    margin-bottom: 10px;
    font-size: 18px;
}

.hero .feature p{
    font-size: 14px;
    color: #e5e7eb;
}
/* ================= SECTIONS ================= */
section{
    padding: 90px 20px;
    max-width: 1200px;
    margin: auto;
}

h2{
    text-align: center;
    font-size: 32px;
    margin-bottom: 50px;
}

/* ================= CARDS ================= */
.card{
    background: rgba(17, 24, 39, .9);
    padding: 30px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,.1);
    transition: .3s;
}

.card:hover{
    transform: translateY(-6px);
}

.card h3{
    color: #93c5fd;
}

/* ================= FOOTER ================= */
footer{
    background: #020617;
    padding: 30px 20px;
    text-align: center;
    font-size: 14px;
    margin-top: 60px;
}

footer span{
    color: #22c55e;
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
       <a href="{{ route('promociones') }}">Promociones</a>
        <a href="#contacto">Contacto</a>
        <a href="#noticias">Noticias & Novedades</a>
    </nav>

    <a href="{{ route('login') }}" class="login-icon">👤</a>

</header>
<div class="hero" id="inicio">
    <h1>BARBERÍA & SPA</h1>
    <p>Estilo, cuidado y bienestar en un solo lugar</p>

    <div class="features">
        <div class="feature">
            <h3>✂️ Cortes Modernos</h3>
            <p>Técnicas actuales y tendencias</p>
        </div>
        <div class="feature">
            <h3>💇‍♂️ Tratamientos Spa</h3>
            <p>Relajación y cuidado personal</p>
        </div>
        <div class="feature">
            <h3>⭐ Calidad Premium</h3>
            <p>Productos de primera línea</p>
        </div>
    </div>
</div>

<section>
    <h2>Nuestros Servicios</h2>

    <div class="services-wrapper">
        <button class="nav-btn left" onclick="scrollServices(-1)">‹</button>

        <div class="services-slider" id="servicesSlider">
            @forelse($servicios as $servicio)
                <div class="card service-card">
                    <h3>{{ $servicio->nombre }}</h3>
                    <p>{{ $servicio->descripcion }}</p>
                    <p><strong>Duración:</strong> {{ $servicio->duracion_minutos }} min</p>
                    <p><strong>Precio:</strong> ${{ number_format($servicio->precio, 2) }}</p>
                </div>
            @empty
                <p>No hay servicios disponibles.</p>
            @endforelse
        </div>

        <button class="nav-btn right" onclick="scrollServices(1)">›</button>
    </div>
</section>


{{-- NOTICIAS --}}
<section>
    <h2>Noticias & Novedades</h2>

    <div class="services">
        @forelse($noticias as $noticia)
            <div class="card">
                <h3>{{ $noticia->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($noticia->content), 120) }}</p>
                <small>Publicado: {{ $noticia->publication_date }}</small>
            </div>
        @empty
            <p>No hay noticias publicadas.</p>
        @endforelse
    </div>
</section>

<footer>
     © 2026 Barbería & Spa <br>
    Desarrollado por <span>Cybac</span>
</footer>

<script>
    function scrollServices(direction) {
        const slider = document.getElementById('servicesSlider');
        const cardWidth = slider.querySelector('.service-card').offsetWidth + 20;
        slider.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });
    }

    function scrollPromos(direction) {
        const slider = document.getElementById('promoSlider');
        const cardWidth = slider.querySelector('.service-card').offsetWidth + 20;
        slider.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });
    }
</script>

@livewireScripts

</body>
</html>
