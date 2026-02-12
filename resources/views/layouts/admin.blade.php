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

    <main class="dashboard">

        <header class="page-header">
            @if (!request()->routeIs('admin.dashboard'))
                @php
                    $backUrl = trim($__env->yieldContent('back-url'));
                @endphp
                <a href="{{ $backUrl !== '' ? $backUrl : route('admin.dashboard') }}" class="back-arrow" title="Volver al menu principal">&larr;</a>
            @endif
            <h1>@yield('title')</h1>

            <div class="page-actions">
                @yield('header-actions')
            </div>
        </header>

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

        @yield('content')

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
    @yield('scripts')
</body>
</html>
