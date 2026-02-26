<header class="public-navbar">
    <div class="logo">
        @if (optional($homeSetting)->navbar_logo)
            <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}"
                 alt="Logo"
                 class="navbar-logo-img">
        @else
            Barbería & Spa
        @endif
    </div>

    @php($homeUrl = route('home'))
    <nav>
        <a href="{{ $homeUrl }}#inicio"
           data-section="inicio"
           class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>

        <a href="{{ route('servicios') }}"
           class="{{ request()->routeIs('servicios') ? 'active' : '' }}">Servicios</a>

        <a href="{{ route('promociones') }}"
           class="{{ request()->routeIs('promociones') ? 'active' : '' }}">Promociones</a>

        <a href="{{ route('comentarios.publicos') }}"
           class="{{ request()->routeIs('comentarios.publicos') ? 'active' : '' }}">Comentarios</a>

        <a href="{{ $homeUrl }}#noticias"
           data-section="noticias">Noticias & Novedades</a>

        <a href="{{ $homeUrl }}#contacto"
           data-section="contacto">Contacto</a>
    </nav>

    <a href="{{ route('login') }}" class="login-icon">
        <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesión" class="icon-img">
    </a>
</header>
