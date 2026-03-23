<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-perfil.css') }}">
</head>

<body class="cliente-perfil-page">

    @include('cliente.partials.menu')

<main class="container">
    <section class="profile-shell">
        <div class="profile-card profile-card-main">
            <span class="profile-kicker">Perfil</span>
            <h1>{{ trim(($usuario->name ?? '') . ' ' . ($usuario->last_name ?? '')) }}</h1>
            <p class="profile-subtitle">Aquí puedes consultar la información principal de tu cuenta.</p>
    <main class="container">
        <section class="profile-shell">
            <div class="profile-card profile-card-main">
                <span class="profile-kicker">Perfil</span>
                <h1>{{ trim(($usuario->name ?? '') . ' ' . ($usuario->last_name ?? '')) }}</h1>
                <p class="profile-subtitle">Aqui puedes consultar la informacion principal de tu cuenta.</p>

            @if (session('success'))
                <div class="profile-alert profile-alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="profile-alert profile-alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('cliente.perfil.update') }}" class="profile-form">
                @csrf

                    <div class="profile-grid">
                        <label class="profile-item">
                            <span>Nombre</span>
                            <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required>
                        </label>
                        <label class="profile-item">
                            <span>Apellidos</span>
                            <input type="text" name="last_name" value="{{ old('last_name', $usuario->last_name) }}">
                        </label>
                        <label class="profile-item">
                            <span>Correo</span>
                            <input type="email" value="{{ $usuario->email }}" readonly disabled>
                            <small class="profile-help">
                                Este campo no es editable.
                            </small>
                        </label>
                        <label class="profile-item">
                            <span>Teléfono</span>
                            <input type="text" name="phone" value="{{ old('phone', $usuario->phone) }}">
                        </label>
                        <label class="profile-item">
                            <span>Fecha de nacimiento</span>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $cliente?->birth_date?->format('Y-m-d')) }}">
                            <small class="profile-help">
                                @if ($cliente?->birth_date)
                                    Puedes cambiarla solo una vez mas.
                                @else
                                    Agregala ahora y despues solo podra modificarse una vez.
                                @endif
                            </small>
                        </label>
                        <div class="profile-item">
                            <span>Estado de cuenta</span>
                            <strong>{{ $usuario->active ?? false ? 'Activa' : 'Inactiva' }}</strong>
                        </div>
                    </div>

                <div class="profile-form-actions">
                    <button type="submit" class="profile-btn profile-btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>

        <div class="profile-card profile-card-side">
            <span class="profile-kicker">Resumen</span>
            <div class="profile-stat">
                <span>Total de citas</span>
                <strong>{{ $stats['citas_total'] ?? 0 }}</strong>
            </div>
            <div class="profile-stat">
                <span>Proxima cita</span>
                @if ($stats['proxima_cita'])
                    <strong>{{ \Carbon\Carbon::parse($stats['proxima_cita']->date)->format('d M, Y') }}</strong>
                    <small>{{ \Carbon\Carbon::parse($stats['proxima_cita']->start_time)->format('h:i A') }}</small>
                @else
                    <strong>Sin citas proximas</strong>
>>>>>>> 188f9a6615538d7332be51bb33a6a6b263b430a1
                @endif

                @if ($errors->any())
                    <div class="profile-alert profile-alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('cliente.perfil.update') }}" class="profile-form">
                    @csrf

                    <div class="profile-grid">
                        <label class="profile-item">
                            <span>Nombre</span>
                            <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required>
                        </label>
                        <label class="profile-item">
                            <span>Apellidos</span>
                            <input type="text" name="last_name" value="{{ old('last_name', $usuario->last_name) }}">
                        </label>
                        <label class="profile-item">
                            <span>Correo</span>
                            <input type="email" value="{{ $usuario->email }}" readonly disabled>
                            <small class="profile-help">
                                Este campo no es editable.
                            </small>
                        </label>
                        <label class="profile-item">
                            <span>Telefono</span>
                            <input type="text" name="phone" value="{{ old('phone', $usuario->phone) }}">
                        </label>
                        <label class="profile-item">
                            <span>Fecha de nacimiento</span>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $cliente?->birth_date?->format('Y-m-d')) }}">
                            <small class="profile-help">
                                @if ($cliente?->birth_date)
                                    Puedes cambiarla solo una vez mas.
                                @else
                                    Agregala ahora y despues solo podra modificarse una vez.
                                @endif
                            </small>
                        </label>
                        <div class="profile-item">
                            <span>Estado de cuenta</span>
                            <strong>{{ $usuario->active ?? false ? 'Activa' : 'Inactiva' }}</strong>
                        </div>
                    </div>

                    <div class="profile-form-actions">
                        <button type="submit" class="profile-btn profile-btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>

            <div class="profile-card profile-card-side">
                <span class="profile-kicker">Resumen</span>
                <div class="profile-stat">
                    <span>Total de citas</span>
                    <strong>{{ $stats['citas_total'] ?? 0 }}</strong>
                </div>
                <div class="profile-stat">
                    <span>Proxima cita</span>
                    @if ($stats['proxima_cita'])
                        <strong>{{ \Carbon\Carbon::parse($stats['proxima_cita']->date)->format('d M, Y') }}</strong>
                        <small>{{ \Carbon\Carbon::parse($stats['proxima_cita']->start_time)->format('h:i A') }}</small>
                    @else
                        <strong>Sin citas proximas</strong>
                    @endif
                </div>

                <div class="profile-actions">
                    <a href="{{ route('cliente.citas.create') }}" class="profile-btn profile-btn-primary">Agendar
                        cita</a>
                    <a href="{{ route('cliente.citas.index') }}" class="profile-btn profile-btn-secondary">Ver mis
                        citas</a>
                </div>
            </div>
        </section>
    </main>

</body>
@include('cliente.partials.footer')

</html>
