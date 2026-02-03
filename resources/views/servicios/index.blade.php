<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Barbería & Spa</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #e5e7eb;

            background-image:
                linear-gradient(rgba(2, 6, 23, 0.15), rgba(2, 6, 23, 0.45)),
                url("{{ asset('imagenes/serviciosFon2.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }


        /* ================= HEADER ================= */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: #8c4030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1000;
            backdrop-filter: blur(6px);
        }

        .logo {
            font-weight: bold;
            font-size: 25px;
            letter-spacing: 2px;
            color: #e48815;
        }

        nav a {
            margin: 0 14px;
            color: #e5e7eb;
            text-decoration: none;
            font-weight: bold;
            transition: .3s;
        }

        nav a:hover {
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




        section {
            max-width: 1200px;
            margin: auto;
            padding: 80px 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 50px;
            text-shadow: 0 0 15px #fccc7c;
        }

        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
        }

        .card {
            background: #5f4636;
            border-radius: 16px;
            padding: 15px;
            border: 1px solid #c0a799;
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 0 25px #c0a799;
        }

        .card h3 {
            color: #fff;
            margin-bottom: 10px;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            object-position: center;
            border-radius: 12px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(0, 0, 0, .25);
        }

        .price {
            color: #e48815;
            font-weight: bold;
            margin-top: 10px;
        }

        .duration {
            font-size: 13px;
            opacity: .85;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid #e48815;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            box-shadow: 0 0 12px #e48815
        }

        .btn:hover {
            box-shadow: 0 0 20px #e48815
        }

        /* ================= MODAL ================= */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(43, 24, 5, 0.85);
            backdrop-filter: blur(6px);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            position: relative;
            background: #5f4636;
            padding: 30px;
            border-radius: 18px;
            width: 90%;
            max-width: 420px;
            border: 1px solid #fccc7c;
            box-shadow: 0 0 30px #c0a799;
            animation: zoom .3s ease;
        }

        @keyframes zoom {
            from {
                transform: scale(.8);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        .modal-content h2 {
            color: #fff;
            margin-bottom: 10px;
        }

        .modal-content img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            object-position: center;
            border-radius: 12px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(0, 0, 0, .25);
        }

        .modal-content .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            cursor: pointer;
            color: #e5e7eb;
        }

        .modal-content .close:hover {
            color: #e48815;
        }

        .close {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(0, 0, 0, .3);
        }

        .close:hover {
            background: rgba(0, 0, 0, .5);
        }
    </style>
    @livewireStyles
</head>

<body>

    <header>
        <div class="logo">Barbería & Spa</div>

        <nav>
            <a href="{{ url('/') }}#inicio">Inicio</a>
            <a href="{{ url('/') }}#servicios">Servicios</a>
            <a href="{{ url('/') }}#promociones">Promociones</a>
            <a href="{{ url('/') }}#contacto">Contacto</a>
            <a href="{{ url('/') }}#noticias">Noticias & Novedades</a>
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
                    <img src="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                        alt="{{ $servicio->name }}">
                    <h3>{{ $servicio->name }}</h3>


                    <a href="#" class="btn abrir-modal" data-nombre="{{ $servicio->name }}"
                        data-descripcion="{{ $servicio->description }}"
                        data-duracion="{{ $servicio->duration_minutes }}"
                        data-precio="{{ number_format($servicio->price, 2) }}"
                        data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}">
                        ver mas...
                    </a>
                </div>
            @empty
                <p>No hay servicios disponibles.</p>
            @endforelse
        </div>

    </section>
    <!-- ================= MODAL ================= -->
    <div id="modalServicio" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>

            <img id="modalImagen" src="" alt="Servicio">
            <h2 id="modalTitulo"></h2>
            <p id="modalDescripcion"></p>

            <p class="duration" id="modalDuracion"></p>
            <p class="price" id="modalPrecio"></p>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalServicio');
        const cerrar = document.querySelector('.close');

        document.querySelectorAll('.abrir-modal').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();

                document.getElementById('modalTitulo').innerText = btn.dataset.nombre;
                document.getElementById('modalDescripcion').innerText = btn.dataset.descripcion;
                document.getElementById('modalImagen').src = btn.dataset.imagen;
                document.getElementById('modalDuracion').innerText =
                    'Duración: ' + btn.dataset.duracion + ' min';
                document.getElementById('modalPrecio').innerText =
                    '$' + btn.dataset.precio;

                modal.style.display = 'flex';
            });
        });

        cerrar.onclick = () => modal.style.display = 'none';

        window.onclick = e => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</body>

</html>
