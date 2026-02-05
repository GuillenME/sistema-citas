<div class="news-editorial">
    @foreach ($noticias as $index => $noticia)
        <article class="news-item {{ $index % 2 === 0 ? 'down' : 'up' }}">
            
            <img
                src="{{ $noticia->image ? asset('storage/' . $noticia->image) : asset('imagenes/servicio_default.png') }}"
                alt="{{ $noticia->title }}"
            >

            <div class="news-text">
                <span class="news-date">
                    {{ \Carbon\Carbon::parse($noticia->publication_date)->format('d M Y') }}
                </span>

                <h3>{{ $noticia->title }}</h3>

                <p>
                    {{ \Illuminate\Support\Str::limit($noticia->content, 120) }}
                </p>
            </div>

        </article>
    @endforeach
</div>