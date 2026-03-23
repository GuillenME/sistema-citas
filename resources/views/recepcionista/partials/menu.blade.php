<header class="cliente-menu">
    <div class="cliente-menu-top">
        <div class="title">
            @if (optional($homeSetting)->navbar_logo)
                <img class="title-logo-img" src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo Barberia">
            @else
                <img class="title-logo-img" src="{{ asset('imagenes/contacto/logo.jpg') }}" alt="Logo Barberia">
            @endif
            Recepcionista
        </div>

        <button type="button" class="menu-toggle" data-menu-toggle aria-expanded="false" aria-label="Abrir menu de recepcionista">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <div class="cliente-menu-main" data-menu-panel>
        <nav>
            <a href="{{ route('recepcionista.dashboard') }}" class="{{ request()->routeIs('recepcionista.dashboard') ? 'active' : '' }}">Inicio</a>
            <a href="{{ route('recepcionista.citas.create') }}" class="{{ request()->routeIs('recepcionista.citas.create') ? 'active' : '' }}">Agendar cita</a>
            <a href="{{ route('recepcionista.citas.agenda') }}" class="{{ request()->routeIs('recepcionista.citas.agenda', 'recepcionista.citas.index', 'recepcionista.citas.reporte-diario', 'recepcionista.citas.reporte-diario.pdf', 'recepcionista.citas.reporte-mensual', 'recepcionista.citas.reporte-mensual.pdf', 'recepcionista.citas.ticket') ? 'active' : '' }}">Citas</a>
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
</script>
