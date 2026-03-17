<header class="cliente-menu">
    <div class="title">
        @if (optional($homeSetting)->navbar_logo)
            <img class="title-logo-img" src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo Barberia">
        @else
            <img class="title-logo-img" src="{{ asset('imagenes/contacto/logo.jpg') }}" alt="Logo Barberia">
        @endif
        Recepcionista
    </div>

    <nav>
        <a href="{{ route('recepcionista.dashboard') }}" class="{{ request()->routeIs('recepcionista.dashboard') ? 'active' : '' }}">Inicio</a>
        <a href="{{ route('recepcionista.citas.create') }}" class="{{ request()->routeIs('recepcionista.citas.create') ? 'active' : '' }}">Agendar cita</a>
        <a href="{{ route('recepcionista.citas.agenda') }}" class="{{ request()->routeIs('recepcionista.citas.*') ? 'active' : '' }}">Citas</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 viewBox="0 0 24 24" 
                 fill="none" 
                 stroke="currentColor" 
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="m16 17 5-5-5-5"/>
                <path d="M21 12H9"/>
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            </svg>
        </button>
    </form>
</header>
