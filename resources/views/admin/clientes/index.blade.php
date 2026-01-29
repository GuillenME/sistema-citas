<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @livewireStyles
</head>

<body>

    <div class="dashboard">

        <div class="header">
            <div class="header-left">
                <a href="{{ route('admin.dashboard') }}" class="back-arrow">←</a>
                <h1>Clientes</h1>
            </div>
        </div>

        <livewire:admin.clientes-index />

    </div>

    @livewireScripts
</body>

</html>
