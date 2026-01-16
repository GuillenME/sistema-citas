@if ($promociones->count())
    <section>
        <h2 style="text-align:center;margin-bottom:40px">
            Promociones
        </h2>

        <div class="services">
            @foreach ($promociones as $promo)
                <div class="card">
                    <h3>🔥 {{ $promo->titulo }}</h3>

                    <p>{{ $promo->descripcion }}</p>

                    <p>
                        <strong>{{ $promo->descuento }}% de descuento</strong>
                    </p>

                    <p style="font-size:14px;color:#cbd5f5">
                        Vigente del {{ $promo->fecha_inicio }}
                        al {{ $promo->fecha_fin }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>
@endif

