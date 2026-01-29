<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empleados</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @livewireStyles
</head>
<body>
<div class="dashboard">
    <livewire:admin.empleado-index />
</div>
@livewireScripts
</body>
</html>
