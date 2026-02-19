<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>
        @hasSection('title')
            @yield('title') | Sistema Citas
        @else
            Sistema Citas
        @endif
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
    @livewireStyles
</head>
<body>

    <livewire:admin.navbar />
    <button type="button" class="mobile-sidebar-toggle" data-sidebar-toggle aria-label="Abrir o cerrar menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <main class="dashboard {{ request()->routeIs('admin.dashboard') ? 'dashboard-home' : 'dashboard-inner' }}">

        @if (session('success'))
            <div class="modal-overlay flash-modal" id="flashModal">
                <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="flashTitle">
                    <h3 id="flashTitle">Listo</h3>
                    <p>{{ session('success') }}</p>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-save" id="closeFlashModal">Cerrar</button>
                    </div>
                </div>
            </div>
        @endif

        @if (!request()->routeIs('admin.dashboard'))
            <section class="admin-page-shell">
                <header class="page-header">
                    @php
                        $backUrl = trim($__env->yieldContent('back-url'));
                    @endphp
                    <div class="page-heading">
                        <a href="{{ $backUrl !== '' ? $backUrl : route('admin.dashboard') }}" class="back-link" title="Volver al menu principal">
                            &larr; Panel principal
                        </a>
                        <h1>@yield('title')</h1>
                        @hasSection('page-subtitle')
                            <p class="page-subtitle">@yield('page-subtitle')</p>
                        @endif
                    </div>

                    <div class="page-actions">
                        @yield('header-actions')
                    </div>
                </header>

                @yield('content')
            </section>
        @else
            @yield('content')
        @endif

    </main>

    <livewire:admin.logout-modal />

    @livewireScripts
    <script>
        (function () {
            const modal = document.getElementById('flashModal');
            if (!modal) return;

            const closeBtn = document.getElementById('closeFlashModal');
            const closeModal = () => modal.classList.add('is-hidden');

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeModal();
            });
        })();
    </script>
    <script>
        (function () {
            const mobileMq = window.matchMedia('(max-width: 980px)');
            const body = document.body;
            body.classList.remove('sidebar-collapsed');

            function closeMobileSidebar() {
                body.classList.remove('sidebar-open');
            }

            document.addEventListener('click', function (event) {
                const toggle = event.target.closest('[data-sidebar-toggle]');
                const overlay = event.target.closest('[data-sidebar-overlay]');
                const menuLink = event.target.closest('.admin-sidebar .navbar-links a');

                if (toggle) {
                    body.classList.toggle('sidebar-open');
                    return;
                }

                if (overlay || (menuLink && mobileMq.matches)) {
                    closeMobileSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (!mobileMq.matches) {
                    closeMobileSidebar();
                    return;
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeMobileSidebar();
                }
            });

            document.addEventListener('livewire:navigated', function () {
                closeMobileSidebar();
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>
