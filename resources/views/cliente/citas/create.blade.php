<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<h2>Agendar cita</h2>

<form method="POST" action="{{ route('cliente.citas.store') }}">
    @csrf

    <select name="servicio_id" id="servicio">
        <option value="">Selecciona un servicio</option>
        @foreach ($servicios as $servicio)
            <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
        @endforeach
    </select>

    <input type="date"
           name="fecha"
           id="fecha"
           min="{{ now()->toDateString() }}">

    <select name="hora_inicio" id="horarios">
        <option value="">Selecciona un horario</option>
    </select>

    <button type="submit">AGENDAR</button>
</form>

<hr>

<h4>Anticipo requerido</h4>
<p>Se requiere un anticipo del <strong>50%</strong></p>
<p>
    Banco: BBVA<br>
    Cuenta: 1234567890<br>
    CLABE: 012345678901234567
</p>

<script>
const servicio = document.getElementById('servicio');
const fecha = document.getElementById('fecha');
const horarios = document.getElementById('horarios');

// 🔒 Bloquear domingos inmediatamente
fecha.addEventListener('input', () => {
    if (!fecha.value) return;

    const d = new Date(fecha.value + 'T00:00:00').getDay();
    if (d === 0) {
        alert('Los domingos no se atiende');
        fecha.value = '';
        horarios.innerHTML = '<option value="">Selecciona un horario</option>';
    }
});

async function cargarBloques() {
    horarios.innerHTML = '<option value="">Selecciona un horario</option>';

    if (!servicio.value || !fecha.value) return;

    const dia = new Date(fecha.value + 'T00:00:00').getDay();
    if (dia === 0) return;

    const res = await fetch(
        `/cliente/citas/bloques?servicio_id=${servicio.value}&fecha=${fecha.value}`
    );

    const bloques = await res.json();

    if (bloques.length === 0) {
        const opt = document.createElement('option');
        opt.textContent = 'No hay horarios disponibles';
        horarios.appendChild(opt);
        return;
    }

    bloques.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.inicio;
        opt.textContent = `${b.inicio} - ${b.fin}`;
        horarios.appendChild(opt);
    });
}

servicio.addEventListener('change', cargarBloques);
fecha.addEventListener('change', cargarBloques);
</script>

</body>
</html>
