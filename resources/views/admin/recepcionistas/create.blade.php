<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo recepcionista</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @livewireStyles
</head>

<body>

    <div class="dashboard">

        <div class="header">
            <div class="header-left">
                <a href="{{ route('admin.recepcionistas.index') }}" class="back-arrow">←</a>
                <h1>Nuevo recepcionista</h1>
            </div>
        </div>

        <livewire:admin.recepcionista-create />

    </div>

    @livewireScripts
</body>

</html>
