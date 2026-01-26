<section>
    <h2>Promociones</h2>

    @if ($promociones->count())

        <div class="services-wrapper">
            <button class="nav-btn left" onclick="scrollPromos(-1)">‹</button>

            <div class="services-slider promo-slider-centered" id="promoSlider">
                @foreach ($promociones as $promo)
                    <div class="card service-card">
                        <h3>{{ $promo->titulo }}</h3>

                        <p>{{ $promo->descripcion }}</p>

                        <p style="margin-top:10px;color:#fde68a;">
                            <strong>Descuento:</strong> {{ $promo->descuento }}%
                        </p>

                        <small style="opacity:.8;">
                            Válido del
                            {{ \Carbon\Carbon::parse($promo->fecha_inicio)->format('d/m/Y') }}
                            al
                            {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}
                        </small>
                    </div>
                @endforeach
            </div>

            <button class="nav-btn right" onclick="scrollPromos(1)">›</button>
        </div>

    @else
        <p style="text-align:center;opacity:.7;">
            No hay promociones activas por el momento.
        </p>
    @endif
</section>
