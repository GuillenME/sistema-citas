@extends('layouts.public')

@section('title', 'Barbería & Spa')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/public/index.css') }}">
@endsection

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>


</body>


@section('content')
<div class="hero" id="inicio" style="--hero-bg-image: url('{{ $homeSetting && $homeSetting->hero_image ? asset('storage/' . $homeSetting->hero_image) : asset('imagenes/registro_fondo3.png') }}');">
    <h1>{{ optional($homeSetting)->hero_title ?? 'BARBERÍA & SPA' }}</h1>
    <p>{{ optional($homeSetting)->hero_subtitle ?? 'Estilo, cuidado y bienestar en un solo lugar' }}</p>

    <div class="features">
        <div class="feature">
            <h3>{{ optional($homeSetting)->feature_1_title ?? 'Cortes Modernos' }}</h3>
            <p>{{ optional($homeSetting)->feature_1_description ?? 'Técnicas actuales y tendencias' }}</p>
        </div>
        <div class="feature">
            <h3>{{ optional($homeSetting)->feature_2_title ?? 'Tratamientos Spa' }}</h3>
            <p>{{ optional($homeSetting)->feature_2_description ?? 'Relajación y cuidado personal' }}</p>
        </div>
        <div class="feature">
            <h3>{{ optional($homeSetting)->feature_3_title ?? 'Calidad Premium' }}</h3>
            <p>{{ optional($homeSetting)->feature_3_description ?? 'Productos de primera línea' }}</p>
        </div>
    </div>
</div>

<section id="servicios" class="home-section">
    <h2>Servicios</h2>

    <div class="services-wrapper">
        <button class="nav-btn left" onclick="scrollServices(-1)">‹</button>

        <div class="services-slider" id="servicesSlider">
            @forelse($homeServicios as $servicio)
                <div
                    class="home-card service-card service-click"
                    data-nombre="{{ $servicio->name }}"
                    data-descripcion="{{ $servicio->description }}"
                    data-precio="${{ number_format($servicio->price, 2) }}"
                    data-imagen="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                >
                    <img
                        src="{{ $servicio->image ? asset('storage/' . $servicio->image) : asset('imagenes/servicio_default.png') }}"
                        alt="{{ $servicio->name }}"
                    >
                    <div class="service-name">{{ $servicio->name }}</div>
                </div>
            @empty
                <p>No hay servicios disponibles.</p>
            @endforelse
        </div>

        <button class="nav-btn right" onclick="scrollServices(1)">›</button>
    </div>
    <div class="home-actions">
        <a class="btn" href="{{ route('servicios') }}">Ver todos los servicios</a>
    </div>
</section>

<section id="promociones" class="promo-section">

    {{-- PROMOCIÓN (por ahora usa la primera, luego será la más reciente) --}}
    @if($homePromociones->count())
        @php $promo = $homePromociones->first(); @endphp

        <div class="promo-box">

            <!-- IZQUIERDA: INFO -->
            <div class="promo-info">
                <span class="promo-badge">¡DESCUENTO EN PROMOCIÓN!</span>

                <h2>
                    DEL {{ $promo->discount }}%
                </h2>

                <p class="promo-text">
                    {{ $promo->description }}
                </p>
                @if($promo->start_date && $promo->end_date)
                    <div class="promo-dates">
                        Vigente del {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                        al {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                    </div>
                @endif
            </div>

            <!-- DERECHA: IMAGEN -->
            <div class="promo-image">
                <img
                    src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                    alt="{{ $promo->title }}"
                >
            </div>

        </div>
    @endif

</section>

<section id="reviews" class="home-section">
    <h2>Comentarios</h2>

    <div class="reviews-grid google-style">
        @forelse($reviews as $review)

            @php
            $email = $review->user_email ?? '';
            $parts = explode('@', $email, 2);
            $user = $parts[0] ?? '';
            $domain = $parts[1] ?? '';

            $userMasked = $user === ''
                ? 'Usuario'
                : substr($user, 0, 2) . str_repeat('*', max(strlen($user) - 2, 0));

            $domainParts = explode('.', $domain, 2);
            $domainName = $domainParts[0] ?? '';
            $domainTld = $domainParts[1] ?? '';

            $domainMasked = $domainName === ''
                ? ''
                : substr($domainName, 0, 1) . str_repeat('*', max(strlen($domainName) - 1, 0));

            $maskedEmail = ($domainMasked && $domainTld)
                ? $userMasked . '@' . $domainMasked . '.' . $domainTld
                : $userMasked;

            $initial = strtoupper(substr($maskedEmail, 0, 1));
        @endphp
            <div class="review-card google-card">

                <!-- ESTRELLAS -->
                <div class="google-stars">
                    @php
                $rating = max(1, min($review->rating ?? 5, 5));
                @endphp
                @for ($i = 0; $i < $rating; $i++)
                ★
                @endfor

                </div>

                <!-- TEXTO -->
                <p class="google-comment">
                    {{ $review->comment }}
                </p>

                <!-- FOOTER -->
                <div class="google-user">
                    <div class="google-avatar">
                        {{ $initial }}
                    </div>

                    <div class="google-meta">
                    <strong>{{ $maskedEmail }}</strong>
                    <span>
                    {{ $review->created_at->format('d/m/Y') }}
                    </span>
                </div>

                </div>

            </div>

        @empty
            <p>No hay comentarios aún.</p>
        @endforelse
    </div>
    @if (!empty($reviewsHasMore) && $reviewsHasMore)
        <div class="reviews-actions">
            <a href="{{ route('comentarios.publicos') }}">Ver todos los comentarios</a>
        </div>
    @endif
</section>

{{-- NOTICIAS --}}
<livewire:noticias />
{{-- CONTACTOS --}}
<livewire:contactos />
<div id="modalServicio" class="modal">
    <div class="modal-box">

        <button class="modal-close">&times;</button>

        <div class="modal-left">
            <img id="modalImagen">
        </div>

        <div class="modal-right">
            <h2 id="modalTitulo"></h2>
            <p id="modalDescripcion"></p>
            <div class="price" id="modalPrecio"></div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // ===== CARRUSEL =====
    function scrollServices(direction) {
        const slider = document.getElementById('servicesSlider');
        if (!slider) return;

        const cardWidth =
            slider.querySelector('.service-card').offsetWidth + 16;

        slider.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });
    }

    // ===== MODAL SERVICIO =====
    const modal = document.getElementById('modalServicio');
    const cerrar = document.querySelector('.modal-close');

    document.querySelectorAll('.service-click').forEach(card => {
        card.addEventListener('click', () => abrirModal(card));
    });

    function abrirModal(card){
        document.getElementById('modalTitulo').innerText = card.dataset.nombre;
        document.getElementById('modalDescripcion').innerText = card.dataset.descripcion;
        document.getElementById('modalPrecio').innerText = card.dataset.precio;
        document.getElementById('modalImagen').src = card.dataset.imagen;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
    }

    function cerrarModal(){
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    cerrar.addEventListener('click', cerrarModal);

    window.addEventListener('click', e => {
        if (e.target === modal) {
            cerrarModal();
        }
    });
</script>
@endsection
