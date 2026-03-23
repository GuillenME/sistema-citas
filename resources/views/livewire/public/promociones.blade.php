<div class="promos-showcase">
    <div class="promos-head">
        <div>
            <h2>Ofertas de Temporada</h2>
            <p>Valido por tiempo limitado en sucursales seleccionadas.</p>
        </div>

        <div class="promos-tabs">
            <input class="promos-tab-control" type="radio" name="promos-tab" id="promos-tab-active" checked>
            <input class="promos-tab-control" type="radio" name="promos-tab" id="promos-tab-upcoming">

            <div class="promos-tab-buttons">
                <label for="promos-tab-active" class="promos-tab-btn promos-tab-btn-active">Activas</label>
                <label for="promos-tab-upcoming" class="promos-tab-btn promos-tab-btn-upcoming">Próximas</label>
            </div>

            <div class="promos-panels">
                <div class="promos-panel promos-panel-active">
                    @forelse($promocionesActivas as $promo)
                        <article class="promo-feature-card {{ $loop->index === 1 ? 'is-featured' : '' }}">
                            <div class="promo-image-wrap">
                                <img
                                    src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                                    alt="{{ $promo->title }}"
                                >
                                <span class="promo-discount">{{ $promo->discount }}% OFF</span>
                                @if ($loop->index === 1)
                                    <span class="promo-badge">Lo mas popular</span>
                                @endif
                            </div>

                            <div class="promo-content">
                                <h3>{{ $promo->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($promo->description, 105) }}</p>

                                <div class="promo-meta">
                                    Vigente del {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                                    al {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                                </div>

                            </div>
                        </article>
                    @empty
                        <p class="promo-empty">
                            No hay promociones activas por el momento.
                        </p>
                    @endforelse
                </div>

                <div class="promos-panel promos-panel-upcoming">
                    @forelse($promocionesProximas as $promo)
                        <article class="promo-feature-card is-upcoming">
                            <div class="promo-image-wrap">
                                <img
                                    src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                                    alt="{{ $promo->title }}"
                                >
                                <span class="promo-discount">{{ $promo->discount }}% OFF</span>
                            </div>

                            <div class="promo-content">
                                <h3>{{ $promo->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($promo->description, 105) }}</p>

                                <div class="promo-meta">
                                    Inicia el {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                                </div>

                            </div>
                        </article>
                    @empty
                        <p class="promo-empty">
                            No hay promociones proximas publicadas.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
