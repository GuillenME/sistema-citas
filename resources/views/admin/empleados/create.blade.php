<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo empleado</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @livewireStyles
</head>
<body>
<div class="dashboard">
    <livewire:admin.empleado-create />
</div>
@livewireScripts
</body>
</html>
