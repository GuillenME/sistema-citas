@extends('layouts.public')

@section('title', 'Noticias')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/public/noticias.css') }}">
@endsection

@section('content')
<section>
    <h1>Noticias</h1>
    <p class="subtitle">Lo ultimo en tendencias, promociones y novedades.</p>

    <div class="news-grid">
        @forelse ($noticias as $noticia)
            <article class="news-card">
                <div class="news-thumb">
                    <img
                        src="{{ $noticia->image ? asset('storage/' . $noticia->image) : asset('imagenes/servicio_default.png') }}"
                        alt="{{ $noticia->title }}"
                    >
                </div>
                <div class="news-body">
                    <span class="news-date">
                        {{ \Carbon\Carbon::parse($noticia->publication_date)->format('d M Y') }}
                    </span>
                    <h3>{{ $noticia->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($noticia->content, 160) }}</p>
                </div>
                <button
                    class="news-more"
                    data-title="{{ e($noticia->title) }}"
                    data-date="{{ \Carbon\Carbon::parse($noticia->publication_date)->format('d M Y') }}"
                    data-image="{{ $noticia->image ? asset('storage/' . $noticia->image) : asset('imagenes/servicio_default.png') }}"
                    data-content="{{ e($noticia->content) }}"
                >
                    Leer mas
                </button>
            </article>
        @empty
            <p>No hay noticias disponibles.</p>
        @endforelse
    </div>

    @if ($noticias->hasPages())
        <div class="news-pagination">
            {{ $noticias->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</section>

<div id="newsModal" class="news-modal" aria-hidden="true">
    <div class="news-modal-content" role="dialog" aria-modal="true">
        <button class="news-modal-close" type="button">&times;</button>
        <img id="newsModalImage" src="" alt="Noticia">
        <span id="newsModalDate" class="news-modal-date"></span>
        <h3 id="newsModalTitle" class="news-modal-title"></h3>
        <p id="newsModalContent" class="news-modal-text"></p>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
