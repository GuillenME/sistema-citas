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
            font-family: 'Arial', sans-serif;
            background: #0f172a;
            color: #e5e7eb;
        }

        /* HEADER */
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
            letter-spacing: 1px;
        }

        header a:hover {
            text-decoration: underline;
        }

        .hero {
            height: 100vh;
            background:
                linear-gradient(rgba(0, 0, 0, .75), rgba(0, 0, 0, .85)),
                url("{{ asset('imagenes/registro_fondo.png') }}") center/cover no-repeat;
        }

        .hero h1 {
            font-size: 56px;
            letter-spacing: 6px;
            margin-bottom: 12px;
            text-shadow:
                0 0 10px #1F4E79,
                0 0 30px rgba(42, 22, 218, .8);
        }

        .hero p {
            font-size: 18px;
            max-width: 600px;
            color: #cbd5f5;
        }

        /* SCROLL INDICATOR */
        .scroll-indicator {
            position: absolute;
            bottom: 25px;
            font-size: 28px;
            opacity: .7;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(10px);
            }
        }

        /* SECTIONS */
        section {
            padding: 80px 20px;
            max-width: 1200px;
            margin: auto;
        }

        h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 50px;
            color: #f8fafc;
        }

        /* SERVICES */
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
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
            box-shadow: 0 25px 60px rgba(0, 0, 0, .8);
        }

        .card h3 {
            margin-top: 0;
            color: #93c5fd;
        }

        .card p {
            font-size: 15px;
            color: #cbd5f5;
        }

        /* PROMO */
        .promo {
            background: linear-gradient(135deg, #1F4E79, #1e40af);
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 0 40px rgba(42, 22, 218, .8);
            text-align: center;
        }

        /* INFO */
        .info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
        }

        .info div {
            background: rgba(17, 24, 39, .8);
            padding: 30px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .08);
        }

        /* FOOTER */
        footer {
            background: #020617;
            padding: 25px;
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
        }

        footer a {
            color: #93c5fd;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <header>
        <a href="{{ route('login') }}">Iniciar sesión</a>
        <a href="{{ route('register') }}">Registrarse</a>
    </header>

    <!-- HERO -->
    <div class="hero">
        <h1>BARBERÍA & SPA</h1>
        <p>
            Estilo, cuidado y bienestar en un solo lugar.
            Agenda tu cita y vive la experiencia profesional.
        </p>

        <div class="scroll-indicator">⬇</div>
    </div>

    <!-- SERVICES -->
    <section>
        <h2>Nuestros Servicios</h2>

        <div class="services">
            <div class="card">
                <h3>Corte de Cabello</h3>
                <p>Estilo clásico o moderno, adaptado a tu imagen.</p>
                <p><strong>Duración:</strong> 30 min</p>
            </div>

            <div class="card">
                <h3>Corte + Barba</h3>
                <p>Servicio completo de imagen personal.</p>
                <p><strong>Duración:</strong> 45 min</p>
            </div>

            <div class="card">
                <h3>Facial Relajante</h3>
                <p>Limpieza profunda y relajación facial.</p>
                <p><strong>Duración:</strong> 60 min</p>
            </div>
        </div>
    </section>

    <!-- PROMO -->
    <section>
        <div class="promo">
            <h3>🔥 Promoción del Mes</h3>
            <p>10% de descuento en Corte + Barba</p>
        </div>
    </section>

    <!-- INFO -->
    <section>
        <h2>Información</h2>

        <div class="info">
            <div>
                <h3>Horarios</h3>
                <p>
                    Lunes a Viernes: 9:00 – 19:00 <br>
                    Sábado: 9:00 – 17:00 <br>
                    Domingo: Cerrado
                </p>
            </div>

            <div>
                <h3>Contacto</h3>
                <p>
                    📍 Calle Principal #123 <br>
                    📞 961 000 0000 <br>
                    📧 contacto@barberiaspa.com
                </p>
            </div>
        </div>
    </section>

    <footer>
        © 2026 Barbería & Spa
    </footer>

</body>

</html>
