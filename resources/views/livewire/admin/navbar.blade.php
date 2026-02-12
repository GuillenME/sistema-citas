<nav class="navbar">
    <div class="navbar-left">
        <a href="{{ route('admin.dashboard') }}"
           class="logo {{ !empty($homeSetting?->navbar_logo) ? 'has-image' : '' }}">
            @if (!empty($homeSetting?->navbar_logo))
                <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo">
            @else
                Sistema Citas
            @endif
        </a>
        <div class="navbar-links">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Inicio
            </a>
            <a href="{{ route('admin.citas.index') }}"
               class="{{ request()->routeIs('admin.citas.*') ? 'active' : '' }}">
                Citas
            </a>
            <a href="{{ route('admin.servicios.index') }}"
               class="{{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">
                Servicios
            </a>
            <a href="{{ route('admin.promociones.index') }}"
               class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
                Promociones
            </a>
            <a href="{{ route('admin.empleados.index') }}"
               class="{{ request()->routeIs('admin.empleados.*') ? 'active' : '' }}">
                Empleados
            </a>
            <a href="{{ route('admin.clientes.index') }}"
               class="{{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}">
                Clientes
            </a>
            <a href="{{ route('admin.noticias.index') }}"
               class="{{ request()->routeIs('admin.noticias.*') ? 'active' : '' }}">
                Noticias
            </a>
            <a href="{{ route('admin.recepcionistas.index') }}"
               class="{{ request()->routeIs('admin.recepcionistas.*') ? 'active' : '' }}">
                Recepcionistas
            </a>
            <a href="{{ route('admin.home_settings.edit') }}"
               class="{{ request()->routeIs('admin.home_settings.*') ? 'active' : '' }}">
                Editar Home
            </a>
        </div>
    </div>

    <div class="navbar-right">
        <button class="btn-logout" wire:click="abrirLogout">
            Cerrar sesión
        </button>
    </div>
</nav>
