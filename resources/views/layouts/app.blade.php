<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Barbería & Spa</title>

    @livewireStyles

    <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
</head>
<body>

    {{-- HEADER --}}
    <livewire:navbar />

    {{-- CONTENIDO --}}
    <main>
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <footer>
        © 2026 Barbería & Spa <br>
        Desarrollado por <strong>Tu Empresa</strong>
    </footer>

@livewireScripts
</body>
</html>
