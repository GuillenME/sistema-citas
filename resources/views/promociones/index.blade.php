<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Promociones | Barbería & Spa</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#0f172a;
            color:#e5e7eb;
        }

        header{
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:70px;
            background:rgba(6,13,46,.95);
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 40px;
            z-index:1000;
        }

        .logo{
            font-weight:bold;
            font-size:22px;
            color:#93c5fd;
        }

        a{
            color:#93c5fd;
            text-decoration:none;
            font-weight:bold;
        }

        section{
            max-width:1200px;
            margin:auto;
            padding:120px 20px;
        }

        h1{
            text-align:center;
            margin-bottom:50px;
        }
    </style>

    @livewireStyles
</head>
<body>

<header>
    <div class="logo">Barbería & Spa</div>
    <a href="{{ route('home') }}">← Volver al inicio</a>
</header>

<section>
    <h1>Promociones</h1>

    {{-- MISMAS PROMOS DEL INDEX --}}
    <livewire:public.promociones />
</section>

@livewireScripts
</body>
</html>
