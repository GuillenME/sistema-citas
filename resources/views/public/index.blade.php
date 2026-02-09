@extends('layouts.public')

@section('title', 'Barbería & Spa')

@section('styles')
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
    overflow-x: hidden;
}

/* ================= HERO ================= */
.hero{
    height: 100vh;
    background:
        linear-gradient(rgba(0,0,0,.15), rgba(0,0,0,.30)),
        url("{{ $homeSetting && $homeSetting->hero_image ? asset('storage/' . $homeSetting->hero_image) : asset('imagenes/registro_fondo3.png') }}") center/cover no-repeat;
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
    width: 260px;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
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
    border-top: 4px solid #e48815;
    background: #8c4030;
    padding: 30px 20px;
    text-align: center;
    font-size: 14px;
    margin-top: 0;
}

footer span{
    color: #fccc7c;
}
/* ================= NOTICIAS ================= */
.news-header{
    text-align: center;
    margin-bottom: 30px;
}

.news-subtitle{
    opacity: .9;
    margin-top: 6px;
}

.news-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 28px;
}

.news-card{
    background: transparent;
    border-radius: 0;
    border: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: .3s;
}

.news-card.up{
    margin-top: 0;
}

.news-card.down{
    margin-top: 50px;
}

.news-card:hover{
    transform: translateY(-6px);
    filter: drop-shadow(0 10px 18px rgba(0,0,0,.25));
}

.news-thumb img{
    width: 90%;
    height: 230px;
    object-fit: contain;
    background: rgba(0,0,0,.35);
    padding: 8px;
    display: block;
}

.news-body{
    padding: 12px 10px 6px;
}

.news-date{
    font-size: 12px;
    display: inline-block;
    margin-bottom: 6px;
    color: #ffffff;
    opacity: .85;
}

.news-body h3{
    font-size: 18px;
    margin: 0 0 6px;
    color: #fff;
}

.news-body p{
    font-size: 14px;
    opacity: .9;
}

.news-more{
    border: 1px solid rgba(255,255,255,.35);
    background: rgba(0,0,0,.2);
    color: #ffffff;
    font-weight: bold;
    padding: 8px 12px;
    text-align: center;
    border-radius: 10px;
    cursor: pointer;
}

.news-more:hover{
    filter: brightness(1.1);
}

.news-actions{
    margin-top: 18px;
    text-align: center;
}

.news-actions a{
    display: inline-block;
    padding: 10px 18px;
    border-radius: 999px;
    background: #8c4030;
    color: #fccc7c;
    text-decoration: none;
    font-weight: bold;
    letter-spacing: 1px;
}

.news-actions a:hover{
    filter: brightness(1.1);
}

.news-modal{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.65);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 20px;
    opacity: 0;
    transition: opacity .2s ease;
}

.news-modal.active{
    display: flex;
    opacity: 1;
}

.news-modal-content{
    background: #5f4636;
    border: 1px solid #c0a799;
    border-radius: 16px;
    max-width: 560px;
    width: 100%;
    padding: 20px;
    box-shadow: 0 0 30px rgba(0,0,0,.45);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
    transform: translateY(10px) scale(.98);
    opacity: 0;
    transition: transform .2s ease, opacity .2s ease;
}

.news-modal.active .news-modal-content{
    transform: translateY(0) scale(1);
    opacity: 1;
}

.news-modal-content img{
    width: 100%;
    height: 320px;
    object-fit: contain;
    background: rgba(0,0,0,.35);
    padding: 8px;
    border-radius: 12px;
    margin-bottom: 14px;
}

.news-modal-date{
    color: #fccc7c;
    font-size: 12px;
}

.news-modal-content h3{
    margin: 8px 0 10px;
}

.news-modal-close{
    position: absolute;
    top: 10px;
    right: 14px;
    background: transparent;
    border: 0;
    color: #fff;
    font-size: 28px;
    cursor: pointer;
}
/* ================= CONTACTO NUEVO ================= */
.contact-section{
    max-width: 100%;
    padding: 80px 20px 0;
    background: #e48815;
}

.contact-section h2{
    text-align: center;
    letter-spacing: 4px;
    margin-bottom: 50px;
}

/* FORMULARIO */
.contact-form{
    max-width: 900px;
    margin: 0 auto 60px;
}

.contact-grid{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}

.field label{
    font-size: 13px;
    color: #fff;
}

.field input,
.field textarea{
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 1px solid #fff;
    padding: 10px 4px;
    color: #fff;
    outline: none;
}

.field.full{
    grid-column: 1 / -1;
}

.contact-actions{
    text-align: right;
    margin-top: 30px;
}

.contact-actions button{
    padding: 12px 36px;
    background: #fff;
    color: #000;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

/* MAPA */
.contact-map iframe{
    width: 100%;
    height: 300px;
    border: none;
    display: block;
}

/* RESPONSIVE */
@media(max-width: 768px){
    .contact-grid{
        grid-template-columns: 1fr;
    }

    .contact-actions{
        text-align: center;
    }
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
    padding:12px 22px;
    border-radius:999px;
    border:2px solid #fccc7c;
    color:#ffffff;
    text-decoration:none;
    font-size:13px;
    font-weight:bold;
    letter-spacing:1px;
    background:linear-gradient(135deg, #e48815, #8c4030);
    box-shadow:0 10px 22px rgba(0,0,0,.45), 0 0 16px rgba(252,204,124,.85);
}

.home-actions .btn:hover{
    transform:translateY(-2px);
    filter:brightness(1.05);
    box-shadow:0 14px 28px rgba(0,0,0,.5), 0 0 22px rgba(252,204,124,1);
}

/* ================= RESENAS ================= */
.reviews-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));
    gap:20px;
}

.review-card{
    background:#5f4636;
    border-radius:16px;
    padding:18px;
    border:1px solid #c0a799;
}

.review-meta{
    font-size:12px;
    opacity:.85;
    margin-top:8px;
}

.review-rating{
    color:#fccc7c;
    font-weight:bold;
    margin-top:6px;
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
/* ================= CARRUSEL SERVICIOS ================= */
.services-wrapper{
    position: relative;
    display: flex;
    align-items: center;
}

.services-slider{
    display: flex;
    gap: 0px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 0px;
}

.services-slider::-webkit-scrollbar{
    display: none;
}
.services-slider{
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE/Edge */
}

.service-card{
    min-width: 320px;
    max-width: 320px;
    height: 220px;
    flex-shrink: 0;
    border-radius: 0;
}

/* BOTONES */
.nav-btn{
    background: rgba(255,255,255,0.12);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.25);
    font-size: 26px;
    width: 44px;
    height: 44px;
    border-radius: 999px;
    cursor: pointer;
    box-shadow: 0 10px 22px rgba(0,0,0,.35);
    transition: .2s ease;
    backdrop-filter: blur(6px);
}

.nav-btn:hover{
    background: #e48815;
    border-color: #e48815;
    transform: translateY(-1px);
}

.nav-btn.left{
    margin-right: 10px;
}

.nav-btn.right{
    margin-left: 10px;
}

/* TARJETAS MÁS COMPACTAS */
.home-card img{
    height: 120px;
}

.home-card p{
    font-size: 12px;
}
/* ===== TARJETA SOLO IMAGEN ===== */
.service-card{
    padding: 0;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}

.service-card img{
    width: 100%;
    height: 160px;
    object-fit: cover; /*  llena toda la tarjeta */
    border-radius: 0px;
    margin: 0;
}
.service-card:hover{
    transform: none;
    box-shadow: none;
}
/* efecto hover */
.service-card:hover img{
    transform: scale(1.04);
    transition: .3s;
}

.service-name{
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 10px 12px;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(0deg, rgba(0,0,0,.7), rgba(0,0,0,0));
    text-transform: uppercase;
    letter-spacing: .6px;
}
/* ===== MODAL BASE ===== */
.modal{
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.75);
    z-index: 999;
    justify-content: center;
    align-items: center;
}

/* CONTENEDOR */
.modal-box{
    background: #6b4f3f;
    width: 90%;
    max-width: 780px;
    height: 420px;
    display: flex;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

/* X */
.modal-close{
    position: absolute;
    top: 12px;
    right: 16px;
    background: none;
    border: none;
    font-size: 26px;
    color: #fff;
    cursor: pointer;
    z-index: 2;
}

/* IZQUIERDA (IMAGEN) */
.modal-left{
    flex: 1.2;
}

.modal-left img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* DERECHA (INFO) */
.modal-right{
    flex: 1;
    padding: 28px;
    color: #fff;
    text-align: center;
}

.modal-right h2{
    margin-top: 40px;
    font-size: 26px;
}

.modal-right p{
    font-size: 14px;
    margin: 18px 0;
}

.modal-right .price{
    font-size: 18px;
    font-weight: bold;
}

/* RESPONSIVE */
@media (max-width: 768px){
    .modal-box{
        flex-direction: column;
        height: auto;
    }

    .modal-left{
        height: 220px;
    }
}

.close{
    position: absolute;
    top: 10px;
    right: 14px;
    font-size: 26px;
    cursor: pointer;
    color: #fff;
}
/* ===== FIX BORDE CAFÉ ===== */
.service-card{
    background: transparent !important;
    border: none !important;
    height: 240px;
}

/* Asegura que la imagen tape TODO */
.service-card img{
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
body.modal-open{
    overflow: hidden;
}
/* ================= PROMO BANNER ================= */
.promo-section{
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
}

.promo-box{
    display: flex;
    width: 100%;
    height: 100%;
    align-items: stretch;
    border-radius: 0px;
    overflow: hidden;
    background: linear-gradient(135deg, #fccc7c, #5f4636);
}

/* IZQUIERDA */
.promo-info{
    flex: 1;
    padding: 60px 50px;
    color: #000;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.promo-badge{
    font-size: 14px;
    letter-spacing: 1px;
    margin-bottom: 20px;
}

.promo-info h2{
    font-size: 80px;
    margin: 0 0 20px;
    font-weight: 900;
}

.promo-text{
    font-size: 16px;
    margin-bottom: 12px;
}

.promo-dates{
    font-size: 14px;
    opacity: .9;
    margin-bottom: 10px;
}

.promo-code{
    font-size: 14px;
}

/* DERECHA */
.promo-image{
    flex: 1;
    height: 100%;
}

.promo-image img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* RESPONSIVE */
@media (max-width: 900px){
    .promo-box{
        flex-direction: column-reverse;
    }

    .promo-info{
        padding: 30px;
        text-align: center;
    }

    .promo-info h2{
        font-size: 48px;
    }

    .promo-image{
        height: 260px;
    }
}
#promociones{
    padding: 0 !important;
    max-width: 100% !important;
}
/* ===== CONTACTO FULL WIDTH ===== */
.contact-section{
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    padding: 80px 40px;
}
@media(min-width:1200px){
    .contact-section{
        padding: 100px 120px;
    }
}
/* ===== SEPARACIÓN NOTICIAS → CONTACTO ===== */
.contact-section{
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    padding: 80px 40px 80px;
    background: #e48815;
}
@media (max-width: 768px){
    .contact-section{
        margin-top: 80px;
    }
}
/* ================= REVIEWS GOOGLE STYLE ================= */

.reviews-grid.google-style{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 28px;
}

.reviews-actions{
    margin-top: 14px;
    text-align: center;
}

.reviews-actions a{
    display: inline-block;
    padding: 10px 18px;
    border-radius: 999px;
    background: #8c4030;
    color: #fccc7c;
    text-decoration: none;
    font-weight: bold;
    letter-spacing: 1px;
}

.reviews-actions a:hover{
    filter: brightness(1.1);
}

.google-card{
    background: #000;
    color: #fff;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 10px 30px rgba(0,0,0,.45);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 220px;
}

/* ESTRELLAS */
.google-stars{
    color: #fbbc04;
    font-size: 24px;
    margin-bottom: 14px;
    text-align: center;
    letter-spacing: 3px;
}


/* TEXTO */
.google-comment{
    min-height: 72px;
    font-size: 14px;
    line-height: 1.6;
    opacity: .95;
    margin-bottom: 20px;
}

/* USUARIO */
.google-user{
    display: flex;
    align-items: center;
    gap: 12px;
}

/* AVATAR CON INICIAL */
.google-avatar{
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #8c4030;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    color: #fff;
    flex-shrink: 0;
}

/* META */
.google-meta{
    display: flex;
    flex-direction: column;
    font-size: 13px;
}

.google-meta span{
    opacity: .7;
    font-size: 12px;
}
.google-card{
    transition: transform .25s ease, box-shadow .25s ease;
}

.google-card:hover{
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,.55);
}
/* ===== REVIEWS CARRUSEL EN MOVIL ===== */
@media (max-width: 768px){
    .reviews-grid.google-style{
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        padding-bottom: 10px;
        -webkit-overflow-scrolling: touch;
    }

    .reviews-grid.google-style::-webkit-scrollbar{
        display: none;
    }

    .google-card{
        min-width: 85%;
        flex-shrink: 0;
        scroll-snap-align: start;
    }
}
@media (max-width: 768px){
    .google-card{
        box-shadow: 0 14px 35px rgba(0,0,0,.55);
    }
}

</style>
@endsection
<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

</body>


@section('content')
<div class="hero" id="inicio">
    <h1>{{ optional($homeSetting)->hero_title ?? 'BARBERÍA & SPA' }}</h1>
    <p>{{ optional($homeSetting)->hero_subtitle ?? 'Estilo, cuidado y bienestar en un solo lugar' }}</p>

    <div class="features">
        <div class="feature">
            <h3>{{ optional($homeSetting)->feature_1_title ?? 'Cortes Modernos' }}</h3>
            <p>{{ optional($homeSetting)->feature_1_description ?? 'Técnicas actuales y tendencias' }}</p>
        </div>
        <div class="feature">
            <h3>{{ optional($homeSetting)->feature_2_title ?? 'Tratamientos Spa' }}</h3>
            <p>{{ optional($homeSetting)->feature_2_description ?? 'Relajación y cuidado personal' }}</p>
        </div>
        <div class="feature">
            <h3>{{ optional($homeSetting)->feature_3_title ?? 'Calidad Premium' }}</h3>
            <p>{{ optional($homeSetting)->feature_3_description ?? 'Productos de primera línea' }}</p>
        </div>
    </div>
</div>

<section id="servicios" class="home-section">
    <h2>Servicios</h2>

    <div class="services-wrapper">
        <button class="nav-btn left" onclick="scrollServices(-1)">‹</button>

        <div class="services-slider" id="servicesSlider">
            @forelse($homeServicios as $servicio)
                <div
                    class="home-card service-card service-click"
                    data-nombre="{{ $servicio->name }}"
                    data-descripcion="{{ $servicio->description }}"
                    data-precio="${{ number_format($servicio->price, 2) }}"
                    data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                >
                    <img
                        src="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                        alt="{{ $servicio->name }}"
                    >
                    <div class="service-name">{{ $servicio->name }}</div>
                </div>
            @empty
                <p>No hay servicios disponibles.</p>
            @endforelse
        </div>

        <button class="nav-btn right" onclick="scrollServices(1)">›</button>
    </div>
    <div class="home-actions">
        <a class="btn" href="{{ route('servicios') }}">Ver todos los servicios</a>
    </div>
</section>

<section id="promociones" class="promo-section">

    {{-- PROMOCIÓN (por ahora usa la primera, luego será la más reciente) --}}
    @if($homePromociones->count())
        @php $promo = $homePromociones->first(); @endphp

        <div class="promo-box">

            <!-- IZQUIERDA: INFO -->
            <div class="promo-info">
                <span class="promo-badge">¡DESCUENTO EN PROMOCIÓN!</span>

                <h2>
                    DEL {{ $promo->discount }}%
                </h2>

                <p class="promo-text">
                    {{ $promo->description }}
                </p>
                @if($promo->start_date && $promo->end_date)
                    <div class="promo-dates">
                        Vigente del {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                        al {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                    </div>
                @endif
            </div>

            <!-- DERECHA: IMAGEN -->
            <div class="promo-image">
                <img
                    src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                    alt="{{ $promo->title }}"
                >
            </div>

        </div>
    @endif

</section>

<section id="reviews" class="home-section">
    <h2>Comentarios</h2>

    <div class="reviews-grid google-style">
        @forelse($reviews->sortByDesc('created_at')->take(3) as $review)

            @php
            $email = $review->user_email ?? '';
            $parts = explode('@', $email, 2);
            $user = $parts[0] ?? '';
            $domain = $parts[1] ?? '';

            $userMasked = $user === ''
                ? 'Usuario'
                : substr($user, 0, 2) . str_repeat('*', max(strlen($user) - 2, 0));

            $domainParts = explode('.', $domain, 2);
            $domainName = $domainParts[0] ?? '';
            $domainTld = $domainParts[1] ?? '';

            $domainMasked = $domainName === ''
                ? ''
                : substr($domainName, 0, 1) . str_repeat('*', max(strlen($domainName) - 1, 0));

            $maskedEmail = ($domainMasked && $domainTld)
                ? $userMasked . '@' . $domainMasked . '.' . $domainTld
                : $userMasked;

            $initial = strtoupper(substr($maskedEmail, 0, 1));
        @endphp
            <div class="review-card google-card">

                <!-- ESTRELLAS -->
                <div class="google-stars">
                    @php
                $rating = max(1, min($review->rating ?? 5, 5));
                @endphp
                @for ($i = 0; $i < $rating; $i++)
                ★
                @endfor

                </div>

                <!-- TEXTO -->
                <p class="google-comment">
                    {{ $review->comment }}
                </p>

                <!-- FOOTER -->
                <div class="google-user">
                    <div class="google-avatar">
                        {{ $initial }}
                    </div>

                    <div class="google-meta">
                    <strong>{{ $maskedEmail }}</strong>
                    <span>
                    {{ $review->created_at->format('d/m/Y') }}
                    </span>
                </div>

                </div>

            </div>

        @empty
            <p>No hay comentarios aún.</p>
        @endforelse
    </div>
    <div class="reviews-actions">
        <a href="{{ route('comentarios.publicos') }}">Ver todos los comentarios</a>
    </div>
</section>

{{-- NOTICIAS --}}
<livewire:noticias />
{{-- CONTACTOS --}}
<livewire:contactos />
<div id="modalServicio" class="modal">
    <div class="modal-box">

        <button class="modal-close">&times;</button>

        <div class="modal-left">
            <img id="modalImagen">
        </div>

        <div class="modal-right">
            <h2 id="modalTitulo"></h2>
            <p id="modalDescripcion"></p>
            <div class="price" id="modalPrecio"></div>
        </div>

    </div>
</div>
<footer>
    📍 Calle Principal #123 · Guadalajara <br>
    📞 33 1234 5678 · ⏰ Lun–Sáb 9:00–20:00 <br>
    © 2026 Barbería & Spa · <span>Cybac</span>
</footer>
@endsection

@section('scripts')
<script>
    // ===== CARRUSEL =====
    function scrollServices(direction) {
        const slider = document.getElementById('servicesSlider');
        if (!slider) return;

        const cardWidth =
            slider.querySelector('.service-card').offsetWidth + 16;

        slider.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });
    }

    // ===== MODAL SERVICIO =====
    const modal = document.getElementById('modalServicio');
    const cerrar = document.querySelector('.modal-close');

    document.querySelectorAll('.service-click').forEach(card => {
        card.addEventListener('click', () => abrirModal(card));
    });

    function abrirModal(card){
        document.getElementById('modalTitulo').innerText = card.dataset.nombre;
        document.getElementById('modalDescripcion').innerText = card.dataset.descripcion;
        document.getElementById('modalPrecio').innerText = card.dataset.precio;
        document.getElementById('modalImagen').src = card.dataset.imagen;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
    }

    function cerrarModal(){
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    cerrar.addEventListener('click', cerrarModal);

    window.addEventListener('click', e => {
        if (e.target === modal) {
            cerrarModal();
        }
    });
</script>
@endsection
