@extends('layouts.public')

@section('title', 'Servicios | Barbería & Spa')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/servicios/index.css') }}">
@endsection

@section('content')
<section>
    <h1>Nuestros Servicios</h1>

    <div class="services">
        @forelse($servicios as $servicio)
            <div class="card">
                <img src="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                    alt="{{ $servicio->name }}">
                <h3>{{ $servicio->name }}</h3>

                <a href="#" class="btn abrir-modal" data-nombre="{{ $servicio->name }}"
                    data-descripcion="{{ $servicio->description }}"
                    data-duracion="{{ $servicio->duration_minutes }}"
                    data-precio="{{ number_format($servicio->price, 2) }}"
                    data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}">
                    ver más...
                </a>
            </div>
        @empty
            <p>No hay servicios disponibles.</p>
        @endforelse
    </div>

</section>
<!-- ================= MODAL ================= -->
<div id="modalServicio" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <img id="modalImagen" src="" alt="Servicio">
        <h2 id="modalTitulo"></h2>
        <p id="modalDescripcion"></p>

        <p class="duration" id="modalDuracion"></p>
        <p class="price" id="modalPrecio"></p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('modalServicio');
    const cerrar = document.querySelector('.close');

    document.querySelectorAll('.abrir-modal').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();

            document.getElementById('modalTitulo').innerText = btn.dataset.nombre;
            document.getElementById('modalDescripcion').innerText = btn.dataset.descripcion;
            document.getElementById('modalImagen').src = btn.dataset.imagen;
            document.getElementById('modalDuracion').innerText =
                'Duración: ' + btn.dataset.duracion + ' min';
            document.getElementById('modalPrecio').innerText =
                '$' + btn.dataset.precio;

            modal.style.display = 'flex';
        });
    });

    cerrar.onclick = () => modal.style.display = 'none';

    window.onclick = e => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
