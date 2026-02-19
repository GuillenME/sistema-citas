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
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
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
