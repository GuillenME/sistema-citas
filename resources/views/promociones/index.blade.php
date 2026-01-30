<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Promociones | Barbería & Spa</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
       * { box-sizing: border-box; }

html{
    scroll-behavior: smooth;
}

body{
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0f172a;
    color: #e5e7eb;
}

  /* ================= HEADER ================= */
header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    background: rgba(2, 6, 23, 0.95);
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
    color: #93c5fd;
}

nav a{
    margin: 0 14px;
    color: #e5e7eb;
    text-decoration: none;
    font-weight: bold;
    transition: .3s;
}

nav a:hover{
    color: #93c5fd;
}

.login-icon {
    display: inline-flex;
    align-items: center;
}

.icon-img {
    width: 55px;
    height: 55px;
    object-fit: contain;
    @livewireStyles
</head>
<body>

<header>
    <div class="logo">Barbería & Spa</div>

    <nav>
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('servicios') }}">Servicios</a>
        <a href="{{ route('promociones') }}">Promociones</a>
        <a href="{{ url('/') }}#contacto">Contacto</a>
        <a href="{{ url('/') }}#noticias">Noticias & Novedades</a>
    </nav>

    <a href="{{ route('login') }}" class="login-icon">
        <img src="{{ asset('imagenes/usuario.png') }}" alt="Iniciar sesión" class="icon-img">
    </a>
</header>

<section>
    <h1>Promociones</h1>

    {{-- MISMAS PROMOS DEL INDEX --}}
    <livewire:public.promociones />
</section>

@livewireScripts
</body>
</html>