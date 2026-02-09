<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barber?a & Spa')</title>

    <style>
header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    background: #8c4030;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 40px;
    z-index: 1000;
    backdrop-filter: blur(6px);
}

.logo{
    font-weight: bold;
    font-size: 25px;
    letter-spacing: 2px;
    color: #e48815;
}

nav a{
    margin: 0 14px;
    color: #e5e7eb;
    text-decoration: none;
    font-weight: bold;
    transition: .3s;
}

nav a:hover{
    color: #e48815;
}

.login-icon {
    display: inline-flex;
    align-items: center;
}

.icon-img {
    width: 55px;
    height: 55px;
    object-fit: contain;
}

footer{
    margin-top: 60px;
    background: #8c4030;
    border-top: 4px solid #e48815;
    color: #ffffff;
}

.footer-wrap{
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    align-items: center;
}

.footer-brand{
    font-weight: bold;
    letter-spacing: 2px;
    color: #fccc7c;
    font-size: 18px;
}

.footer-item{
    font-size: 14px;
    opacity: .95;
}

.footer-item span{
    display: block;
    color: #fccc7c;
    font-size: 12px;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.footer-bottom{
    text-align: center;
    padding: 12px 16px 20px;
    font-size: 12px;
    opacity: .85;
}
</style>
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
                        style="height: 130px; object-fit: contain;">
                @else
                    Barberia & Spa
                @endif
            </div>
            <div class="footer-item">
                <span>Ubicacion</span>
                {{ $homeSetting->footer_address ?? 'Calle Principal #123 - Guadalajara' }}
            </div>
            <div class="footer-item">
                <span>Telefono</span>
                {{ $homeSetting->footer_phone ?? '33 1234 5678' }}
            </div>
            <div class="footer-item">
                <span>Horarios</span>
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
