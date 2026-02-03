<div>
    <section id="noticias">
        <h2>Noticias & Novedades</h2>

        <div class="news-grid">
            @forelse ($noticias as $noticia)
                <div class="card news-card">
                    <img
                        src="{{ $noticia->image ? asset('storage/' . $noticia->image) : asset('imagenes/servicio_default.png') }}"
                        alt="{{ $noticia->title }}"
                    >

                    <div class="news-content">
                        <h3>{{ $noticia->title }}</h3>
                        <p>
                            {{ \Illuminate\Support\Str::limit($noticia->content, 140) }}
                        </p>
                        <span class="news-date">
                            Publicado: {{ \Carbon\Carbon::parse($noticia->publication_date)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="card news-card">
                    <div class="news-content">
                        <h3>Sin noticias</h3>
                        <p>Muy pronto publicaremos novedades.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>
