<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* ! tailwindcss v3.2.4 | MIT License | https://tailwindcss.com */
        *,::before,::after{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}
        html{line-height:1.5;-webkit-text-size-adjust:100%;font-family:Figtree,sans-serif}
        body{margin:0}
        a{text-decoration:inherit;color:inherit}
        button,input,select,textarea{font:inherit}
        button{cursor:pointer}

        [type=button],[type=reset],[type=submit],button{
            -webkit-appearance:button;
            appearance:button; /* FIX warning */
            background-color:transparent;
        }

        img,video{
            max-width:100%;
            height:auto;
            display:block; /* FIX warning */
        }

        .min-h-screen{min-height:100vh}
        .flex{display:flex}
        .grid{display:grid}
        .justify-center{justify-content:center}
        .items-center{align-items:center}
        .text-center{text-align:center}
        .bg-gray-100{background:#f3f4f6}
        .bg-white{background:#fff}
        .rounded-lg{border-radius:.5rem}
        .shadow-2xl{box-shadow:0 25px 50px -12px rgba(0,0,0,.25)}
        .transition-all{transition:.2s ease}
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex justify-center items-center bg-gray-100">

        <div class="bg-white shadow-2xl rounded-lg p-10 text-center max-w-lg">
            <h1 class="text-2xl font-semibold mb-4">
                Bienvenido a Laravel
            </h1>

            <p class="text-gray-600 mb-6">
                Tu aplicación está lista. Puedes iniciar sesión o registrarte.
            </p>

            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-6 py-2 bg-gray-800 text-white rounded-lg transition-all hover:bg-gray-700">
                    Iniciar sesión
                </a>

                <a href="{{ route('register') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg transition-all hover:bg-gray-400">
                    Registrarse
                </a>
            </div>

            <p class="mt-6 text-sm text-gray-400">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </p>
        </div>

    </div>
</body>
</html>
