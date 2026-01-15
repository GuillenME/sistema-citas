<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <h2>Recuperar contraseña</h2>

    @if(session('success'))
        <p style="color:#22c55e">{{ session('success') }}</p>
    @endif

    <input type="email" name="email" placeholder="Correo" required>

    @error('email')
        <p style="color:#f87171">{{ $message }}</p>
    @enderror

    <button type="submit">Enviar enlace</button>
</form>
