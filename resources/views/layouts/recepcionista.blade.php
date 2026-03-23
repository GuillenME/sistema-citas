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
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/recepcionista-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            overflow-x: hidden;
        }

        .recepcionista-main {
            position: relative;
            z-index: 1;
            width: min(1320px, calc(100% - 48px));
            margin: 0 auto;
            padding: 26px 0 40px;
        }

        .recepcionista-page-shell {
            width: 100%;
        }

        .recepcionista-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 28px;
        }

        .recepcionista-page-heading {
            display: grid;
            gap: 10px;
        }

        .recepcionista-back-link {
            width: fit-content;
            color: #f4c16d;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 13px;
            font-weight: 700;
        }

        .recepcionista-back-link:hover {
            color: #ffd691;
        }

        .recepcionista-page-header h1 {
            margin: 0;
            color: #fff7ec;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: .95;
        }

        .recepcionista-page-subtitle {
            margin: 0;
            max-width: 760px;
            color: rgba(255, 241, 220, .82);
            font-size: 1rem;
            line-height: 1.6;
        }

        .recepcionista-page-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .modal-overlay {
            display: none;
        }

        .modal-overlay.active,
        .flash-modal {
            display: flex;
        }

        .flash-modal.is-hidden {
            display: none;
        }

        @media (max-width: 820px) {
            .recepcionista-main {
                width: min(100% - 24px, 1320px);
                padding-top: 18px;
            }

            .recepcionista-page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .recepcionista-page-actions {
                justify-content: flex-start;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @include('recepcionista.partials.menu')

    <main class="recepcionista-main">
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

        <section class="recepcionista-page-shell">
            <header class="recepcionista-page-header">
                @php
                    $backUrl = trim($__env->yieldContent('back-url'));
                @endphp
                <div class="recepcionista-page-heading">
                    <a href="{{ $backUrl !== '' ? $backUrl : route('recepcionista.dashboard') }}" class="recepcionista-back-link" title="Volver al menu principal">
                        &larr; Panel principal
                    </a>
                    <h1>@yield('title')</h1>
                    @hasSection('page-subtitle')
                        <p class="recepcionista-page-subtitle">@yield('page-subtitle')</p>
                    @endif
                </div>

                <div class="recepcionista-page-actions">
                    @yield('header-actions')
                </div>
            </header>

            @yield('content')
        </section>
    </main>

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
    @yield('scripts')
</body>
</html>
