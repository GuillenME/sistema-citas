<header class="cliente-menu">
    <div class="cliente-menu-top">
        <div class="title">
            @if (optional($homeSetting)->navbar_logo)
                <img class="title-logo-img" src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo Barberia">
            @else
                <img class="title-logo-img" src="{{ asset('imagenes/contacto/logo.jpg') }}" alt="Logo Barberia">
            @endif
            Cliente
        </div>

        <button type="button" class="menu-toggle" data-menu-toggle aria-expanded="false" aria-label="Abrir menu de cliente">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <div class="cliente-menu-main" data-menu-panel>
        <nav>
            <a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">Inicio</a>
            <a href="{{ route('cliente.citas.create') }}" class="{{ request()->routeIs('cliente.citas.create') ? 'active' : '' }}">Agendar cita</a>
            <a href="{{ route('cliente.citas.index') }}" class="{{ request()->routeIs('cliente.citas.index') ? 'active' : '' }}">Mis citas</a>
            <a href="{{ route('cliente.comentarios') }}" class="{{ request()->routeIs('cliente.comentarios') ? 'active' : '' }}">Comentarios</a>
        </nav>

        <div class="cliente-menu-actions">
            <a
                href="{{ route('cliente.perfil') }}"
                class="profile-btn {{ request()->routeIs('cliente.perfil') ? 'active' : '' }}"
                title="Ver perfil"
                aria-label="Ver perfil">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M20 21a8 8 0 1 0-16 0"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </a>

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
        </div>
    </div>
</header>

<script>
    (function () {
        const header = document.querySelector('.cliente-menu');
        const toggle = header?.querySelector('[data-menu-toggle]');
        const mobileMq = window.matchMedia('(max-width: 860px)');

        if (!header || !toggle) {
            return;
        }

        const syncMenu = () => {
            if (!mobileMq.matches) {
                header.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                return;
            }

            toggle.setAttribute('aria-expanded', header.classList.contains('is-open') ? 'true' : 'false');
        };

        toggle.addEventListener('click', () => {
            header.classList.toggle('is-open');
            syncMenu();
        });

        window.addEventListener('resize', syncMenu);
        syncMenu();
    })();

    function ensureLogoutModal() {
        let modal = document.getElementById('modalLogout');

        if (modal) {
            return modal;
        }

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
                <div class="modal-content">
                    <h3>Cerrar sesion</h3>
                    <p>Estas seguro de que deseas cerrar sesion?</p>
                    <div class="modal-buttons">
                        <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Si, cerrar sesion</button>
                        <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(wrapper.firstElementChild);
        return document.getElementById('modalLogout');
    }

    function mostrarModalLogout() {
        ensureLogoutModal().classList.add('active');
    }

    function cerrarModalLogout() {
        const modal = document.getElementById('modalLogout');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    function confirmarLogout() {
        document.getElementById('logoutForm').submit();
    }
</script>
