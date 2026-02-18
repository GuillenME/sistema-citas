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
        <div class="feature feature-1">
            <span class="feature-icon" aria-hidden="true">✂</span>
            <h3>{{ optional($homeSetting)->feature_1_title ?? 'Cortes Modernos' }}</h3>
            <p>{{ optional($homeSetting)->feature_1_description ?? 'Técnicas actuales y tendencias' }}</p>
        </div>
        <div class="feature feature-2">
            <span class="feature-icon" aria-hidden="true">✦</span>
            <h3>{{ optional($homeSetting)->feature_2_title ?? 'Tratamientos Spa' }}</h3>
            <p>{{ optional($homeSetting)->feature_2_description ?? 'Relajación y cuidado personal' }}</p>
        </div>
        <div class="feature feature-3">
            <span class="feature-icon" aria-hidden="true">🛡</span>
            <h3>{{ optional($homeSetting)->feature_3_title ?? 'Calidad Premium' }}</h3>
            <p>{{ optional($homeSetting)->feature_3_description ?? 'Productos de primera línea' }}</p>
        </div>
    </div>
</div>

<section id="servicios" class="home-section">
    <div class="services-shell">
        <div class="services-head">
            <h2>Nuestros Servicios</h2>
        </div>

        <div class="services-wrapper">
            <button class="service-nav-btn left" onclick="scrollServices(-1)" aria-label="Anterior">&#8249;</button>

            <div class="services-slider" id="servicesSlider">
                @forelse($homeServicios as $servicio)
                    <article
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
                        <div class="service-overlay">
                            <h3 class="service-name">{{ $servicio->name }}</h3>
                        </div>
                    </article>
                @empty
                    <p>No hay servicios disponibles.</p>
                @endforelse
            </div>

            <button class="service-nav-btn right" onclick="scrollServices(1)" aria-label="Siguiente">&#8250;</button>
        </div>

        @if($homeServicios->count() > 1)
            <div class="services-dots" id="servicesDots">
                @foreach($homeServicios as $servicio)
                    <span class="services-dot {{ $loop->first ? 'is-active' : '' }}" data-index="{{ $loop->index }}"></span>
                @endforeach
            </div>
        @endif

        <div class="home-actions services-actions">
            <a class="btn" href="{{ route('servicios') }}">Ver todos los servicios</a>
        </div>
    </div>
</section>

{{-- PROMOCIONES --}}
@if($homePromociones->count())
<section id="promociones" class="promo-section">

    @php $promo = $homePromociones->first(); @endphp

    <div class="promo-box">
        <div class="promo-image">
            <img
                src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                alt="{{ $promo->title }}"
            >
        </div>

        <div class="promo-info">
            <span class="promo-badge">DESCUENTO EN PROMOCION</span>

            <h2 class="promo-title">
                <span class="promo-title-top">DEL</span>
                <span class="promo-title-discount">{{ $promo->discount }}%</span>
                <span class="promo-title-bottom">OFF</span>
            </h2>

            <p class="promo-text">
                {{ $promo->description }}
            </p>

            @if($promo->start_date && $promo->end_date)
                <div class="promo-meta">
                    Vigente del {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                    al {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                </div>
            @endif

            <div class="promo-actions">
                <a class="btn" href="{{ route('promociones') }}">
                    Ver todas las promociones
                </a>
            </div>
        </div>
    </div>

</section>
@endif


<section id="reviews" class="home-section reviews-showcase">
    <div class="reviews-head">
        <span class="reviews-eyebrow">EXPERIENCIAS REALES</span>
        <h2 class="reviews-title">Comentarios</h2>
    </div>

    <div class="reviews-grid reviews-premium-grid">
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
                $rating = max(1, min($review->rating ?? 5, 5));
            @endphp

            <article class="review-card review-premium-card">
                <div class="premium-review-top">
                    <div class="premium-review-stars" aria-label="Calificacion del cliente">
                        @for ($i = 0; $i < $rating; $i++)
                            &#9733;
                        @endfor
                    </div>

                    @if($review->service)
                        <span class="premium-review-tag">{{ $review->service->name }}</span>
                    @endif
                </div>

                <p class="premium-review-quote">"{{ $review->comment }}"</p>

                <div class="premium-review-meta">
                    <div class="premium-review-avatar">{{ $initial }}</div>

                    <div class="premium-review-author">
                        @php
                            $apellidoInicial = $review->user && $review->user->last_name
                                ? strtoupper(substr($review->user->last_name, 0, 1)) . '.'
                                : '';
                        @endphp
                        <strong>{{ $review->user->name ?? 'Cliente' }} {{ $apellidoInicial }}</strong>
                        <span>{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </article>
        @empty
            <p class="reviews-empty">No hay comentarios aun.</p>
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

        const cards = slider.querySelectorAll('.service-card');
        if (!cards.length) return;

        const step = cards.length > 1
            ? cards[1].offsetLeft - cards[0].offsetLeft
            : cards[0].offsetWidth;

        slider.scrollBy({
            left: direction * step,
            behavior: 'smooth'
        });
    }

    function updateServiceDots() {
        const slider = document.getElementById('servicesSlider');
        const dots = document.querySelectorAll('#servicesDots .services-dot');
        if (!slider || !dots.length) return;
        const maxScroll = Math.max(slider.scrollWidth - slider.clientWidth, 0);
        const progress = maxScroll > 0 ? (slider.scrollLeft / maxScroll) : 0;
        const index = Math.round(progress * (dots.length - 1));

        dots.forEach((dot, i) => {
            dot.classList.toggle('is-active', i === index);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('servicesSlider');
        if (!slider) return;

        const cards = slider.querySelectorAll('.service-card');
        const dots = document.querySelectorAll('#servicesDots .services-dot');
        if (dots.length && cards.length) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    const maxScroll = Math.max(slider.scrollWidth - slider.clientWidth, 0);
                    const targetLeft = (dots.length > 1)
                        ? (maxScroll * (index / (dots.length - 1)))
                        : 0;

                    slider.scrollTo({
                        left: targetLeft,
                        behavior: 'smooth'
                    });
                });
            });
        }

        slider.addEventListener('scroll', updateServiceDots, { passive: true });
        updateServiceDots();
    });

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


