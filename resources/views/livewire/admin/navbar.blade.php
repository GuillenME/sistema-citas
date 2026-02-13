<div> {{-- 🔴 ROOT ÚNICO OBLIGATORIO EN LIVEWIRE --}}

    <nav class="navbar admin-sidebar" id="adminSidebar" aria-label="Menu de administracion">

        {{-- LOGO --}}
        <div class="admin-sidebar-top">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                @if (!empty($homeSetting?->navbar_logo))
                    <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}"
                         alt="Barber & Spa"
                         class="admin-brand-logo">
                @else
                    <span class="admin-brand-fallback">Barber & Spa</span>
                @endif
            </a>
        </div>

        {{-- LINKS --}}
        <div class="navbar-links">

            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-home"></i>
                </span>
                <span>Inicio</span>
            </a>

            <a href="{{ route('admin.citas.index') }}"
               class="{{ request()->routeIs('admin.citas.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                <span>Citas</span>
            </a>

            <a href="{{ route('admin.servicios.index') }}"
               class="{{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-cut"></i>
                </span>
                <span>Servicios</span>
            </a>

            <a href="{{ route('admin.clientes.index') }}"
               class="{{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-users"></i>
                </span>
                <span>Clientes</span>
            </a>

            <a href="{{ route('admin.promociones.index') }}"
               class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-tags"></i>
                </span>
                <span>Promociones</span>
            </a>

            <a href="{{ route('admin.empleados.index') }}"
               class="{{ request()->routeIs('admin.empleados.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-id-badge"></i>
                </span>
                <span>Empleados</span>
            </a>

            <a href="{{ route('admin.noticias.index') }}"
               class="{{ request()->routeIs('admin.noticias.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-newspaper"></i>
                </span>
                <span>Noticias</span>
            </a>

            <a href="{{ route('admin.recepcionistas.index') }}"
               class="{{ request()->routeIs('admin.recepcionistas.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-user-tie"></i>
                </span>
                <span>Recepcionistas</span>
            </a>

            <a href="{{ route('admin.home_settings.edit') }}"
               class="{{ request()->routeIs('admin.home_settings.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <i class="fas fa-sliders-h"></i>
                </span>
                <span>Home</span>
            </a>

        </div>

        {{-- LOGOUT --}}
        <div class="navbar-right">
            <button class="btn-logout"
                    wire:click="abrirLogout"
                    aria-label="Cerrar sesión"
                    title="Cerrar sesión">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>

    </nav>

    {{-- OVERLAY --}}
    <div class="admin-sidebar-overlay" data-sidebar-overlay></div>

</div>
