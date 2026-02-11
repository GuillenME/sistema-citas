<div class="promos-wrapper">

    

    @forelse($promociones as $promo)
        <div class="promo-card">
            <img
                src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                alt="{{ $promo->title }}"
            >
            <h3>{{ $promo->title }}</h3>

            <p>{{ $promo->description }}</p>

            <span class="promo-price">
            {{ $promo->discount }}% OFF
            </span>

            <div class="promo-dates">
            Vigente del
            {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
            al
            {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
            </div>

        </div>
    @empty
        <p class="promo-empty">
            No hay promociones activas por el momento.
        </p>
    @endforelse

</div>
