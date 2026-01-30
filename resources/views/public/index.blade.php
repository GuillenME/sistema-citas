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

.login-icon {
    display: inline-flex;
    align-items: center;
}

.icon-img {
    width: 55px;
    height: 55px;
    object-fit: contain;
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
/* ================= NOTICIAS ================= */
.news-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.news-card{
    overflow: hidden;
    padding: 0;
}

.news-card img{
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.news-content{
    padding: 20px;
}

.news-content h3{
    color: #93c5fd;
    margin-bottom: 10px;
}

.news-content p{
    font-size: 14px;
    color: #e5e7eb;
    margin-bottom: 12px;
}

.news-date{
    font-size: 12px;
    color: #22c55e;
}
/* ===== CONTACTO ESTILO CENTRADO ===== */
.contact-info{
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    padding: 50px 80px;   /* espacio lateral grande */
}

/* DATOS → hacia el centro-derecha */
.contact-left{
    justify-self: center;
    margin-left: 80px;   /* empuja hacia la derecha */
    max-width: 340px;
}

/* IMAGEN → hacia el centro-izquierda */
.contact-right{
    justify-self: center;
    margin-right: 80px;  /* empuja hacia la izquierda */
    display: flex;
    justify-content: center;
}

.contact-right img{
    width: 100%;
    max-width: 260px;
    object-fit: contain;
    opacity: 0.95;
}

/* Responsive */
@media (max-width: 900px){
    .contact-info{
        grid-template-columns: 1fr;
        padding: 30px;
        text-align: center;
    }

    .contact-left{
        margin-left: 0;
        max-width: 100%;
    }

    .contact-right{
        margin-right: 0;
        margin-top: 25px;
    }

    .contact-right img{
        max-width: 200px;
    }
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

  <a href="{{ route('login') }}" class="login-icon">
    <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesión" class="icon-img">
</a>


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

{{-- CONTACTOS --}}
<livewire:contactos />
{{-- NOTICIAS --}}
<section>
<livewire:noticias />

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
