<header class="public-navbar">
    <div class="public-navbar-top">
        <div class="logo">
            @if (optional($homeSetting)->navbar_logo)
                <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}"
                    alt="Logo"
                    class="navbar-logo-img">
            @else
                Barberia & Spa
            @endif
        </div>

        <button type="button" class="public-menu-toggle" data-public-menu-toggle aria-expanded="false" aria-label="Abrir menu principal">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    @php($homeUrl = route('home'))
    <div class="public-navbar-main" data-public-menu-panel>
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
                data-section="noticias"
                class="{{ request()->routeIs('noticias.publicas') ? 'active' : '' }}">Noticias y novedades</a>

            <a href="{{ $homeUrl }}#contacto"
                data-section="contacto">Contacto</a>
        </nav>

        <a href="{{ route('login') }}" class="login-icon">
            <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesion" class="icon-img">
        </a>
    </div>
</header>
