<nav class="navbar">
    <div class="navbar-left">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            Sistema Citas
        </a>

        <a href="{{ route('admin.citas.index') }}">Citas</a>
        <a href="{{ route('admin.promociones.index') }}">Promociones</a>
        <a href="{{ route('admin.servicios.index') }}">Servicios</a>
        <a href="{{ route('admin.noticias.index') }}">Noticias</a>
        <a href="{{ route('admin.clientes.index') }}">Clientes</a>
        <a href="{{ route('admin.empleados.index') }}">Empleados</a>
        <a href="{{ route('admin.recepcionistas.index') }}">Recepcionistas</a>
    </div>

    <div class="navbar-right">
        <button class="btn-logout" wire:click="abrirLogout">
            Cerrar sesión
        </button>
    </div>
</nav>
