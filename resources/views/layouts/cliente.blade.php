<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Cliente')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    @livewireStyles
</head>

<body class="@yield('body-class')">

<header class="header">
    <a href="{{ route('cliente.dashboard') }}" class="back-btn">←</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn">Cerrar sesión</button>
    </form>
</header>

<main class="container">
    @yield('content')
</main>

@livewireScripts
</body>
</html>
