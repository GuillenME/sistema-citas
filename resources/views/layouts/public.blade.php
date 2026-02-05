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
</style>
    @yield('styles')
    @livewireStyles
</head>
<body>
    @include('partials.navbar')

    @yield('content')

    @yield('scripts')
    @livewireScripts
</body>
</html>
