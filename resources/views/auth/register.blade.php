<h2>Registro de cliente</h2>

{{-- ERRORES GENERALES --}}
@if ($errors->any())
    <div class="error-box">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" novalidate>
    @csrf

    <input
        type="text"
        name="nombre"
        placeholder="Nombre"
        value="{{ old('nombre') }}"
        class="@error('nombre') input-error @enderror"
    >
    @error('nombre')
        <span class="field-error">{{ $message }}</span>
    @enderror

    <input
        type="text"
        name="email"
        placeholder="Correo"
        value="{{ old('email') }}"
        class="@error('email') input-error @enderror"
    >
    @error('email')
        <span class="field-error">{{ $message }}</span>
    @enderror

    <input
        type="password"
        name="password"
        placeholder="Contraseña"
        class="@error('password') input-error @enderror"
    >
    @error('password')
        <span class="field-error">{{ $message }}</span>
    @enderror

    <input
        type="password"
        name="password_confirmation"
        placeholder="Confirmar contraseña"
        class="@error('password_confirmation') input-error @enderror"
    >

    <!-- Rol fijo -->
    <input type="hidden" name="rol_id" value="3">

    <button type="submit">Registrarse</button>
</form>
