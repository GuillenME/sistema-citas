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
                <div class="profile-hero">
                    <div>
                        <span class="profile-kicker">Perfil</span>
                        <h1>{{ trim(($usuario->name ?? '') . ' ' . ($usuario->last_name ?? '')) }}</h1>
                        <p class="profile-subtitle">Aqui puedes consultar la informacion principal de tu cuenta.</p>
                    </div>

                    <div class="profile-hero-badge">
                        <span>Estado</span>
                        <strong>{{ $usuario->active ?? false ? 'Activa' : 'Inactiva' }}</strong>
                    </div>
                </div>

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
                            <small class="profile-help">Este campo no es editable.</small>
                        </label>

                        <label class="profile-item">
                            <span>Telefono</span>
                            <input type="text" name="phone" value="{{ old('phone', $usuario->phone) }}">
                        </label>

                        <label class="profile-item profile-item-wide">
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
                    </div>

                    <div class="profile-form-actions">
                        <button type="submit" class="profile-btn profile-btn-primary">Guardar cambios</button>
                    </div>
                </form>

                <div class="profile-danger-inline">
                    <div>
                        <span class="profile-kicker">Eliminar cuenta</span>
                        <p class="profile-help profile-danger-text">
                            Si ya no deseas usar tu cuenta, puedes solicitar su eliminacion desde un modal de confirmacion.
                        </p>
                    </div>

                    <button type="button" class="profile-btn profile-btn-danger" id="openDeleteAccountModal">
                        Eliminar mi cuenta
                    </button>
                </div>
            </div>

            <aside class="profile-card profile-card-side">
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

                <div class="profile-stat">
                    <span>Correo registrado</span>
                    <strong>{{ $usuario->email }}</strong>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('cliente.citas.create') }}" class="profile-btn profile-btn-primary">Agendar cita</a>
                    <a href="{{ route('cliente.citas.index') }}" class="profile-btn profile-btn-secondary">Ver mis citas</a>
                </div>
            </aside>
        </section>
    </main>

    <div class="modal-overlay" id="deleteAccountModal" aria-hidden="true">
        <div class="modal-content profile-delete-modal" role="dialog" aria-modal="true" aria-labelledby="deleteAccountTitle">
            <h3 id="deleteAccountTitle">Eliminar cuenta</h3>
            <p>
                Tu acceso sera desactivado y tus datos personales se anonimizaran. El historial de citas se conservara sin informacion identificable.
            </p>

            <form method="POST" action="{{ route('cliente.perfil.delete') }}" class="profile-form profile-delete-form">
                @csrf

                <label class="profile-item profile-delete-field">
                    <span>Confirmar contrasena</span>
                    <input type="password" name="password" required autocomplete="current-password">
                    <small class="profile-help">Ingresa tu contrasena actual para confirmar la eliminacion de tu cuenta.</small>
                </label>

                <div class="modal-buttons">
                    <button type="button" class="modal-btn modal-btn-cancel" id="closeDeleteAccountModal">Cancelar</button>
                    <button type="submit" class="modal-btn modal-btn-danger">Confirmar eliminacion</button>
                </div>
            </form>
        </div>
    </div>

    @include('cliente.partials.footer')

    <script>
        (function () {
            var openBtn = document.getElementById('openDeleteAccountModal');
            var closeBtn = document.getElementById('closeDeleteAccountModal');
            var modal = document.getElementById('deleteAccountModal');

            if (!openBtn || !closeBtn || !modal) return;

            function openModal() {
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal.classList.contains('active')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>

</html>
