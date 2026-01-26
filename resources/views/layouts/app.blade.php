<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Barbería & Spa</title>

    @livewireStyles

    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#0f172a;
            color:#e5e7eb;
        }
        main{
            padding-top:70px;
        }
        footer{
            background:#020617;
            padding:25px;
            text-align:center;
            font-size:14px;
        }
    </style>
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
