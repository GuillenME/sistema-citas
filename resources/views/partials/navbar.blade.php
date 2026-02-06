<header>
    <div class="logo">
        @if (optional($homeSetting)->navbar_logo)
            <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}"
                 alt="Logo"
                 style="height: 73px; object-fit: contain;">
        @else
            Barbería & Spa
        @endif
    </div>

    <nav>
    <a href="{{ route('home') }}">Inicio</a>

    {{-- 👉 REDIRECCIONA A LA VISTA DE SERVICIOS --}}
    <a href="{{ route('servicios') }}">Servicios</a>

    <a href="{{ route('home') }}#promociones">Promociones</a>
    <a href="{{ route('home') }}#noticias">Noticias & Novedades</a>
    <a href="{{ route('home') }}#contacto">Contacto</a>
</nav>

    <a href="{{ route('login') }}" class="login-icon">
        <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesión" class="icon-img">
    </a>
</header>
