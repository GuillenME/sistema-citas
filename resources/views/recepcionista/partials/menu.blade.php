<header class="cliente-menu">
    <div class="title">Recepcionista</div>

    <nav>
        <a href="{{ route('recepcionista.dashboard') }}" class="{{ request()->routeIs('recepcionista.dashboard') ? 'active' : '' }}">Inicio</a>
        <a href="{{ route('recepcionista.citas.create') }}" class="{{ request()->routeIs('recepcionista.citas.create') ? 'active' : '' }}">Agendar cita</a>
        <a href="{{ route('recepcionista.citas.index') }}" class="{{ request()->routeIs('recepcionista.citas.index') ? 'active' : '' }}">Citas</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">
            Cerrar sesion
        </button>
    </form>
</header>
