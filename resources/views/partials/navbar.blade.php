<header>
    <div class="logo">
        @if (optional($homeSetting)->navbar_logo)
            <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo" style="height: 73px; object-fit: contain;">
        @else
            Barber?a & Spa
        @endif
    </div>

    <nav>
        <a href="{{ url('/') }}#inicio">Inicio</a>
        <a href="{{ url('/') }}#servicios">Servicios</a>
        <a href="{{ url('/') }}#promociones">Promociones</a>
        <a href="{{ url('/') }}#noticias">Noticias & Novedades</a>
        <a href="{{ url('/') }}#contacto">Contacto</a>
    </nav>

    <a href="{{ route('login') }}" class="login-icon">
        <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesi?n" class="icon-img">
    </a>
</header>
