<header class="cliente-menu">
    <div class="title">
        @if (optional($homeSetting)->navbar_logo)
            <img class="title-logo-img" src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo Barberia">
        @else
            <img class="title-logo-img" src="{{ asset('imagenes/contacto/logo.jpg') }}" alt="Logo Barberia">
        @endif
        Cliente
    </div>

    <nav>
        <a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">Inicio</a>
        <a href="{{ route('cliente.citas.create') }}" class="{{ request()->routeIs('cliente.citas.create') ? 'active' : '' }}">Agendar cita</a>
        <a href="{{ route('cliente.citas.index') }}" class="{{ request()->routeIs('cliente.citas.index') ? 'active' : '' }}">Mis citas</a>
        <a href="{{ route('cliente.comentarios') }}" class="{{ request()->routeIs('cliente.comentarios') ? 'active' : '' }}">Comentarios</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout(event)">
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

<div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro de que deseas cerrar sesión?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Sí, cerrar sesión</button>
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
        </div>
    </div>
</div>

<script>
    function mostrarModalLogout() {
        document.getElementById('modalLogout').classList.add('active');
    }

    function cerrarModalLogout() {
        document.getElementById('modalLogout').classList.remove('active');
    }

    function confirmarLogout() {
        document.getElementById('logoutForm').submit();
    }
</script>
