<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<body>

    <livewire:admin.navbar />

    <main class="dashboard">
        <header class="page-header">
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
