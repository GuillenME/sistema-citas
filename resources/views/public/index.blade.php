<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Barbería & Spa</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: flex-end;
            z-index: 10;
        }

        header a {
            margin-left: 20px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .hero {
            height: 100vh;
            background:
                linear-gradient(rgba(0, 0, 0, .75), rgba(0, 0, 0, .85)),
                url("{{ asset('imagenes/registro_fondo.png') }}") center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 56px;
            letter-spacing: 6px;
            margin-bottom: 12px;
            text-shadow: 0 0 10px #1F4E79;
        }

        .hero p {
            font-size: 18px;
            max-width: 600px;
            color: #cbd5f5;
        }

        section {
            padding: 80px 20px;
            max-width: 1200px;
            margin: auto;
        }

        h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 50px;
        }

        .services-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .services-slider {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: calc(25% - 20px);
            /* 4 visibles */
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            padding: 10px 0;
        }

        .service-card {
            scroll-snap-align: start;
        }

        /* Ocultar scroll */
        .services-slider::-webkit-scrollbar {
            display: none;
        }

        /* Botones */
        .nav-btn {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff;
            font-size: 32px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 2;
        }

        .nav-btn:hover {
            background: #1e40af;
        }

        .nav-btn.left {
            margin-right: 10px;
        }

        .nav-btn.right {
            margin-left: 10px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .services-slider {
                grid-auto-columns: calc(50% - 20px);
                /* 2 */
            }
        }

        @media (max-width: 640px) {
            .services-slider {
                grid-auto-columns: 100%;
                /* 1 */
            }
        }


        .card {
            background: rgba(17, 24, 39, .9);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .1);
            box-shadow: 0 15px 40px rgba(0, 0, 0, .6);
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card h3 {
            color: #93c5fd;
        }

        .promo {
            background: linear-gradient(135deg, #1F4E79, #1e40af);
            padding: 35px;
            border-radius: 18px;
            text-align: center;
        }

        footer {
            background: #020617;
            padding: 25px;
            text-align: center;
            font-size: 14px;
        }
    </style>
    @livewireStyles
</head>

<body>

    <header>
        <a href="{{ route('login') }}">Iniciar sesión</a>
        <a href="{{ route('register') }}">Registrarse</a>
    </header>

    <div class="hero">
        <h1>BARBERÍA & SPA</h1>
        <p>Estilo, cuidado y bienestar en un solo lugar</p>
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

    {{-- PROMOCIÓN EN TIEMPO REAL --}}
    <livewire:public.promociones />



    {{-- NOTICIAS --}}
    <section>
        <h2>Noticias & Novedades</h2>

        <div class="services">
            @forelse($noticias as $noticia)
                <div class="card">
                    <h3>{{ $noticia->titulo }}</h3>

                    <p>
                        {{ \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 120) }}
                    </p>

                    <small>
                        Publicado: {{ $noticia->fecha_publicacion }}
                    </small>
                </div>
            @empty
                <p>No hay noticias publicadas.</p>
            @endforelse
        </div>
    </section>

    <footer>
        © 2026 Barbería & Spa
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
    </script>
    @livewireScripts

</body>

</html>
