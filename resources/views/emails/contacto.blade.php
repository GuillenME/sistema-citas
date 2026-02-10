@if (session()->has('success'))
    <p style="color:#fff; text-align:center; margin-bottom:20px;">
        ✔ {{ session('success') }}
    </p>
@endif
<h2>Nuevo mensaje de contacto</h2>

<p><strong>Nombre:</strong> {{ $data['nombre'] }} {{ $data['apellido'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Asunto:</strong> {{ $data['asunto'] }}</p>

<hr>

<p>{{ $data['mensaje'] }}</p>