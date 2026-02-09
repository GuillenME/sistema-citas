<div> <!-- 🔴 ROOT ÚNICO DEL COMPONENTE -->

    <section id="noticias" class="home-section">
        <div class="news-header">
            <h2>Noticias & Novedades</h2>
            <p class="news-subtitle">
                Lo ultimo en tendencias, promociones y novedades.
            </p>
        </div>

        <div class="news-grid">
            @foreach ($noticias as $index => $noticia)
                <article class="news-card {{ $index % 2 === 0 ? 'down' : 'up' }}">
                    <div class="news-thumb">
                        <img
                            src="{{ $noticia->image 
                                ? asset('storage/' . $noticia->image) 
                                : asset('imagenes/servicio_default.png') }}"
                            alt="{{ $noticia->title }}"
                        >
                    </div>

                    <div class="news-body text-center">
                        <span class="news-date">
                            {{ \Carbon\Carbon::parse($noticia->publication_date)->format('d M Y') }}
                        </span>

                        <h3>{{ $noticia->title }}</h3>

                        <p>
                            {{ \Illuminate\Support\Str::limit($noticia->content, 140) }}
                        </p>
                    </div>

                    <button
                        class="news-more"
                        data-title="{{ e($noticia->title) }}"
                        data-date="{{ \Carbon\Carbon::parse($noticia->publication_date)->format('d M Y') }}"
                        data-image="{{ $noticia->image ? asset('storage/' . $noticia->image) : asset('imagenes/servicio_default.png') }}"
                        data-content="{{ e($noticia->content) }}"
                    >
                        Leer más
                    </button>
                </article>
            @endforeach
        </div>

        <div class="news-actions">
            <a href="{{ route('noticias.publicas') }}">
                Ver todas las noticias
            </a>
        </div>
    </section>

    <!-- MODAL -->
    <div id="newsModal" class="news-modal" aria-hidden="true">
        <div class="news-modal-content" role="dialog" aria-modal="true">
            <button class="news-modal-close" type="button">&times;</button>

            <img id="newsModalImage" src="" alt="Noticia">
            <span id="newsModalDate" class="news-modal-date"></span>
            <h3 id="newsModalTitle"></h3>
            <p id="newsModalContent"></p>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('newsModal');
            if (!modal) return;

            const modalImage = document.getElementById('newsModalImage');
            const modalDate = document.getElementById('newsModalDate');
            const modalTitle = document.getElementById('newsModalTitle');
            const modalContent = document.getElementById('newsModalContent');

            document.querySelectorAll('.news-more').forEach(btn => {
                btn.addEventListener('click', () => {
                    modalImage.src = btn.dataset.image;
                    modalDate.textContent = btn.dataset.date;
                    modalTitle.textContent = btn.dataset.title;
                    modalContent.textContent = btn.dataset.content;

                    modal.classList.add('active');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            const closeModal = () => {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            };

            modal.addEventListener('click', e => {
                if (e.target === modal || e.target.classList.contains('news-modal-close')) {
                    closeModal();
                }
            });
        })();
    </script>

</div> <!-- 🔴 FIN ROOT -->
