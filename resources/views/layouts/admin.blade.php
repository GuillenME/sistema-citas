<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel Admin')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @livewireStyles
</head>

<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="navbar-left">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            Sistema Citas
        </a>

        <a href="{{ route('admin.promociones.index') }}">Promociones</a>
        <a href="{{ route('admin.servicios.index') }}">Servicios</a>
        <a href="{{ route('admin.clientes.index') }}">Clientes</a>
        <a href="{{ route('admin.empleados.index') }}">Empleados</a>
        <a href="{{ route('admin.recepcionistas.index') }}">Recepcionistas</a>
    </div>

    <div class="navbar-right">
        <button class="btn-logout" wire:click="$emit('abrirLogout')">
            Cerrar sesión
        </button>
    </div>
</nav>

{{-- CONTENIDO --}}
<main class="dashboard">
    <header class="page-header">
        <h1>@yield('title')</h1>

        <div class="page-actions">
            @yield('header-actions')
        </div>
    </header>

    @yield('content')
</main>

{{-- MODAL GLOBAL --}}
<livewire:admin.logout-modal />

@livewireScripts
</body>
</html>

