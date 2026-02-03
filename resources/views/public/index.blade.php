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
    background: #b28562;
    color: #ffffff;
}

/* ================= HEADER ================= */
header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    background:#8c4030;
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
    color: #e48815;
}

nav a{
    margin: 0 14px;
    color: #e5e7eb;
    text-decoration: none;
    font-weight: bold;
    transition: .3s;
}

nav a:hover{
    color: #e48815;
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
        linear-gradient(rgba(0,0,0,.15), rgba(0,0,0,.30)),
        url("{{ asset('imagenes/registro_fondo3.png') }}") center/cover no-repeat;
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
      color: #fccc7c;
}
.hero h1{
    text-shadow:
        -2px -2px 0 #000,
         2px -2px 0 #000,
        -2px  2px 0 #000,
         2px  2px 0 #000,
         6px  6px 0 rgba(0,0,0,.4);
}

.hero p{
    font-size: 32px;
    max-width: 800px;
    color: #fccc7c;
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
    background: #5f4636;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
}

.hero .feature h3{
    color: #fccc7c;
    margin-bottom: 10px;
    font-size: 18px;
}

.hero .feature p{
    font-size: 14px;
    color: #e5e7eb;
}

/* ================= SECTIONS ================= */
section{
 padding: 40px 25px; /* antes 90px */
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
    background: #5f4636;
    padding: 30px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,.1);
    transition: .3s;
}

.card:hover{
    transform: translateY(-6px);
}

.card h3{
    color: #e48815;

}

/* ================= FOOTER ================= */
footer{
    background: #8c4030;
    padding: 30px 20px;
    text-align: center;
    font-size: 14px;
    margin-top: 60px;
}

footer span{
    color: #fccc7c;
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
    object-fit: contain;
    background: rgba(0,0,0,.25);
    display: block;
}

.news-content{
    padding: 20px;
}

.news-content h3{
    color: #fccc7c;
    margin-bottom: 10px;
}

.news-content p{
    font-size: 14px;
    color: #e5e7eb;
    margin-bottom: 12px;
}

.news-date{
    font-size: 12px;
    color: #e48815;
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

/* ================= HOME SECTIONS ================= */
.home-section{
    max-width:1200px;
    margin: 0 auto;
    padding:60px 20px;
}

.home-section h2{
    text-align:center;
    margin-bottom:30px;
    text-shadow:0 0 15px #fccc7c;
}

.home-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:22px;
}

.home-card{
    background:#5f4636;
    border-radius:16px;
    padding:14px;
    border:1px solid #c0a799;
    transition:.3s;
}

.home-card:hover{
    transform: translateY(-4px);
    box-shadow:0 0 20px rgba(0,0,0,.2);
}

.home-card img{
    width:100%;
    height:140px;
    object-fit:contain;
    object-position:center;
    border-radius:12px;
    margin-bottom:10px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(0,0,0,.25);
}

.home-card h3{
    color:#fff;
    margin:6px 0 6px;
    font-size:17px;
}

.home-card p{
    font-size:13px;
    opacity:.9;
}

.home-card .price{
    color:#e48815;
    font-weight:bold;
    margin-top:8px;
}

.home-actions{
    text-align:center;
    margin-top:20px;
}

.home-actions .btn{
    display:inline-block;
    padding:10px 16px;
    border-radius:10px;
    border:1px solid #e48815;
    color:#fff;
    text-decoration:none;
    font-size:13px;
    box-shadow:0 0 12px #e48815;
}

.home-actions .btn:hover{
    box-shadow:0 0 20px #e48815;
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
        <a href="{{ route('home') }}">Inicio</a>
        <a href="#servicios">Servicios</a>
        <a href="#promociones">Promociones</a>
        <a href="#noticias">Noticias & Novedades</a>
        <a href="#contacto">Contacto</a>
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

<section id="servicios" class="home-section">
    <h2>Servicios</h2>
    <div class="home-grid">
        @forelse($homeServicios as $servicio)
            <div class="home-card">
                <img
                    src="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                    alt="{{ $servicio->name }}"
                >
                <h3>{{ $servicio->name }}</h3>
                <p>{{ \Illuminate\Support\Str::limit($servicio->description, 80) }}</p>
                <div class="price">${{ number_format($servicio->price, 2) }}</div>
            </div>
        @empty
            <p>No hay servicios disponibles.</p>
        @endforelse
    </div>
    <div class="home-actions">
        <a href="{{ route('servicios') }}" class="btn">Ver más</a>
    </div>
</section>

<section id="promociones" class="home-section">
    <h2>Promociones</h2>
    <div class="home-grid">
        @forelse($homePromociones as $promo)
            <div class="home-card">
                <img
                    src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                    alt="{{ $promo->title }}"
                >
                <h3>{{ $promo->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit($promo->description, 80) }}</p>
                <div class="price">{{ $promo->discount }}% OFF</div>
            </div>
        @empty
            <p>No hay promociones activas por el momento.</p>
        @endforelse
    </div>
    <div class="home-actions">
        <a href="{{ route('promociones') }}" class="btn">Ver más</a>
    </div>
</section>

{{-- NOTICIAS --}}
<livewire:noticias />
{{-- CONTACTOS --}}
<livewire:contactos />

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
