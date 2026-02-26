@extends('layouts.public')

@section('title', 'Servicios | Barberia & Spa')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/servicios/index.css') }}?v={{ filemtime(public_path('css/servicios/index.css')) }}">
@endsection

@section('content')
<section class="services-page">
    <header class="services-hero">
        <div class="services-hero-overlay">
            <h1 class="services-title">
                <span class="title-solid">NUESTROS</span>
                <span class="title-outline">SERVICIOS</span>
            </h1>
            <p class="services-lead">
                Donde la tradicion se encuentra con la innovacion. Ofrecemos rituales de cuidado personal
                disenados exclusivamente para una imagen contemporanea.
            </p>
        </div>
    </header>

    <div class="services-grid-wrap">
        <div class="services-grid">
            @forelse($servicios as $servicio)
                <article class="service-card" data-reveal>
                    <img src="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                        alt="{{ $servicio->name }}">

                    <div class="service-card-overlay">
                        <h3>{{ $servicio->name }}</h3>
                        <a href="#" class="service-card-btn abrir-modal" data-nombre="{{ $servicio->name }}"
                            data-descripcion="{{ $servicio->description }}"
                            data-duracion="{{ $servicio->duration_minutes }}"
                            data-precio="{{ number_format($servicio->price, 2) }}"
                            data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}">
                            EXPLORAR
                        </a>
                    </div>
                </article>
            @empty
                <p class="services-empty">No hay servicios disponibles.</p>
            @endforelse
        </div>
    </div>

    <div class="services-cta" data-reveal>
        <h2>LISTO PARA ELEVAR TU IMAGEN?</h2>
        <div class="services-cta-actions">
            @auth
                <a href="{{ route('cliente.citas.create') }}" class="cta-btn cta-primary">RESERVA TU CITA</a>
            @else
                <a href="{{ route('login') }}" class="cta-btn cta-primary">RESERVA TU CITA</a>
            @endauth
            <a href="{{ route('home') }}#contacto" class="cta-btn cta-ghost">VER UBICACION</a>
        </div>
    </div>
</section>

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
    const revealItems = document.querySelectorAll('[data-reveal]');

    if ('IntersectionObserver' in window && revealItems.length) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.16
        });

        revealItems.forEach((item, index) => {
            item.style.transitionDelay = `${index * 55}ms`;
            revealObserver.observe(item);
        });
    } else {
        revealItems.forEach(item => item.classList.add('is-visible'));
    }

    document.querySelectorAll('.abrir-modal').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();

            document.getElementById('modalTitulo').innerText = btn.dataset.nombre;
            document.getElementById('modalDescripcion').innerText = btn.dataset.descripcion;
            document.getElementById('modalImagen').src = btn.dataset.imagen;
            document.getElementById('modalDuracion').innerText =
                'Duracion: ' + btn.dataset.duracion + ' min';
            document.getElementById('modalPrecio').innerText =
                '$' + btn.dataset.precio;

            modal.style.display = 'flex';
            document.body.classList.add('modal-open');
        });
    });

    cerrar.onclick = () => {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    };

    window.onclick = e => {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    }
</script>
@endsection
