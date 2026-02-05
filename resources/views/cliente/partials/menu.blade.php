<header class="cliente-menu">
    <div class="title">Cliente</div>

    <nav>
        <a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">Inicio</a>
        <a href="{{ route('cliente.citas.create') }}" class="{{ request()->routeIs('cliente.citas.create') ? 'active' : '' }}">Agendar cita</a>
        <a href="{{ route('cliente.citas.index') }}" class="{{ request()->routeIs('cliente.citas.index') ? 'active' : '' }}">Mis citas</a>
        <a href="{{ route('cliente.comentarios') }}" class="{{ request()->routeIs('cliente.comentarios') ? 'active' : '' }}">Comentarios</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">
            Cerrar sesion
        </button>
    </form>
</header>
