@if ($promociones->count())
<section>
    <h2>Promociones</h2>

    <div class="services-slider promo-slider-centered">
        @foreach ($promociones as $promo)
            <div class="card service-card">
                <h3>🔥 {{ $promo->titulo }}</h3>

                <p>{{ $promo->descripcion }}</p>

                <p>
                    <strong>{{ $promo->descuento }}% de descuento</strong>
                </p>

                <small>
                    Vigente del {{ $promo->fecha_inicio }}
                    al {{ $promo->fecha_fin }}
                </small>
            </div>
        @endforeach
    </div>
</section>
@endif
