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
    font-size: 80px;
    letter-spacing: 10px;
}

.hero p{
    font-size: 30px;
    max-width: 600px;
    color: #cbd5f5;
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
        <a href="#promos">Promociones</a>
        <a href="#contacto">Contacto</a>
        <a href="#noticias">Noticias & Novedades</a>
    </nav>

    <a href="{{ route('login') }}" class="login-icon">👤</a>

</header>

<div class="hero" id="inicio">
    <h1>BARBERÍA & SPA</h1>
    <p>Estilo, cuidado y bienestar en un solo lugar</p>
</div>


{{-- PROMOCIONES --}}
<section id="promos">
    <livewire:public.promociones />
</section>

{{-- NOTICIAS --}}
<section>
    <h2>Noticias & Novedades</h2>

    <div class="services">
        @forelse($noticias as $noticia)
            <div class="card">
                <h3>{{ $noticia->titulo }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 120) }}</p>
                <small>Publicado: {{ $noticia->fecha_publicacion }}</small>
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
