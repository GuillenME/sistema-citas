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
    @livewireStyles
</head>
<body>

    <livewire:admin.navbar />

    <main class="dashboard">

        <header class="page-header">
            @if (!request()->routeIs('admin.dashboard'))
                <a href="{{ route('admin.dashboard') }}" class="back-arrow" title="Volver al menú principal">←</a>
            @endif
            <h1>@yield('title')</h1>

            <div class="page-actions">
                @yield('header-actions')
            </div>
        </header>

        @yield('content')

    </main>

    <livewire:admin.logout-modal />

    @livewireScripts
</body>
</html>
