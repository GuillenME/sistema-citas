<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar empleado</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @livewireStyles
</head>
<body>
<div class="dashboard">
    <livewire:admin.empleado-edit :empleado="$empleado" />
</div>
@livewireScripts
</body>
</html>
