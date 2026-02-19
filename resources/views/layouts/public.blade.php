<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barber?a & Spa')</title>

    <link rel="stylesheet" href="{{ asset('css/public/layout.css') }}">
    @yield('styles')
    @livewireStyles
</head>
<body>
    @include('partials.navbar')

    @yield('content')

    <footer>
        <div class="footer-wrap">
            <div class="footer-brand">
                @if (optional($homeSetting)->navbar_logo)
                    <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}"
                        alt="Logo"
                        class="footer-logo-img">
                @else
                    Barberia & Spa
                @endif
            </div>
            <div class="footer-item">
                <span>📍Ubicacion</span>
                {{ $homeSetting->footer_address ?? 'Calle Principal #123 - Guadalajara' }}
            </div>
            <div class="footer-item">
                <span>📱 Teléfono</span>
                {{ $homeSetting->footer_phone ?? '33 1234 5678' }}
            </div>
            <div class="footer-item">
                <span>🕜 Horarios</span>
                {{ $homeSetting->footer_hours ?? 'Lun-Sab 9:00-20:00' }}
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 Barberia & Spa · <span>Cybac</span>
        </div>
    </footer>

    @yield('scripts')
    @livewireScripts
</body>
</html>
