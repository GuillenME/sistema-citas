<div>

    <nav class="navbar admin-sidebar" id="adminSidebar" aria-label="Menu de administracion">

        <div class="admin-sidebar-top">
            <div class="notif-dropdown admin-sidebar-notifications">
                <button class="notif-btn admin-sidebar-notif-btn" id="notifBtn" type="button" aria-expanded="false"
                    aria-controls="notif-menu">
                    <span class="admin-sidebar-notif-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                            <path d="M9 17a3 3 0 0 0 6 0" />
                        </svg>
                    </span>
                    <span class="admin-sidebar-notif-copy">
                        <strong>Notificaciones</strong>
                        <small id="notif-summary">
                            @php($pendingNotifications = auth()->user()->unreadNotifications->count())
                            {{ $pendingNotifications > 0 ? $pendingNotifications . ' avisos pendientes hoy' : 'Sin avisos pendientes hoy' }}
                        </small>
                    </span>
                    <span class="admin-sidebar-notif-dot" id="notif-dot"
                        style="{{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'display:none;' }}"></span>
                </button>

                <div class="notif-menu admin-sidebar-notif-menu" id="notif-menu">
                    @forelse(auth()->user()->unreadNotifications->take(5) as $noti)
                        <a href="{{ route('admin.notificacion.leer', $noti->id) }}" class="notif-item">
                            {{ $noti->data['mensaje'] }}
                            <small>{{ $noti->created_at->diffForHumans() }}</small>
                        </a>
                    @empty
                        <div class="notif-empty">
                            No hay notificaciones
                        </div>
                    @endforelse

                    <a href="{{ route('admin.notificaciones.index') }}" class="notif-more">
                        Ver mas
                    </a>
                </div>
            </div>
        </div>


        <div class="navbar-links">

            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10.5 12 3l9 7.5" />
                        <path d="M5 9.5V21h14V9.5" />
                    </svg>
                </span>
                <span>Inicio</span>
            </a>

            <a href="{{ route('admin.citas.index') }}"
                class="{{ request()->routeIs('admin.citas.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 14v2.2l1.6 1" />
                        <path d="M16 4h2a2 2 0 0 1 2 2v.832" />
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2" />
                        <circle cx="16" cy="16" r="6" />
                        <rect x="8" y="2" width="8" height="4" rx="1" />
                    </svg>
                </span>
                <span>Citas</span>
            </a>

            <a href="{{ route('admin.servicios.index') }}"
                class="{{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3" />
                        <path d="M8.12 8.12 12 12" />
                        <path d="M20 4 8.12 15.88" />
                        <circle cx="6" cy="18" r="3" />
                        <path d="M14.8 14.8 20 20" />
                    </svg>
                </span>
                <span>Servicios</span>
            </a>

            <a href="{{ route('admin.clientes.index') }}"
                class="{{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                </span>
                <span>Clientes</span>
            </a>

            <a href="{{ route('admin.promociones.index') }}"
                class="{{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z" />
                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor" />
                    </svg>
                </span>
                <span>Promociones</span>
            </a>

            <a href="{{ route('admin.empleados.index') }}"
                class="{{ request()->routeIs('admin.empleados.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 10h2" />
                        <path d="M16 14h2" />
                        <path d="M6.17 15a3 3 0 0 1 5.66 0" />
                        <circle cx="9" cy="11" r="2" />
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                    </svg>
                </span>
                <span>Empleados</span>
            </a>

            <a href="{{ route('admin.noticias.index') }}"
                class="{{ request()->routeIs('admin.noticias.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18h-5" />
                        <path d="M18 14h-8" />
                        <path
                            d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2" />
                        <rect width="8" height="4" x="10" y="6" rx="1" />
                    </svg>
                </span>
                <span>Noticias</span>
            </a>

            <a href="{{ route('admin.recepcionistas.index') }}"
                class="{{ request()->routeIs('admin.recepcionistas.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z" />
                        <path d="M20 16a8 8 0 1 0-16 0" />
                        <path d="M12 4v4" />
                        <path d="M10 4h4" />
                    </svg>
                </span>
                <span>Recepcionistas</span>
            </a>

            <a href="{{ route('admin.home_settings.edit') }}"
                class="{{ request()->routeIs('admin.home_settings.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 17H5" />
                        <path d="M19 7h-9" />
                        <circle cx="17" cy="17" r="3" />
                        <circle cx="7" cy="7" r="3" />
                    </svg>
                </span>
                <span>Config. inicio</span>
            </a>

        </div>

        <div class="navbar-right">
            <button class="btn-logout"
                    wire:click="abrirLogout"
                    aria-label="Cerrar sesión"
                    title="Cerrar sesión">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <path d="m16 17 5-5-5-5"/>
                    <path d="M21 12H9"/>
                </svg>
            </button>
        </div>

    </nav>

    <div class="admin-sidebar-overlay" data-sidebar-overlay></div>

</div>
