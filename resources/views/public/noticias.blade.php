@extends('layouts.public')

@section('title', 'Noticias')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        color: #e5e7eb;
        background-image:
            linear-gradient(rgba(2, 6, 23, 0.15), rgba(2, 6, 23, 0.45)),
            url("{{ asset('imagenes/serviciosFon2.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    section {
        max-width: 1200px;
        margin: auto;
        padding: 80px 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 10px;
        text-shadow: 0 0 15px #fccc7c;
    }

    .subtitle {
        text-align: center;
        margin-bottom: 40px;
        color: #f3e7d7;
    }

    .news-grid{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 28px;
    }

    .news-card{
        background: #5f4636;
        border-radius: 16px;
        border: 1px solid #c0a799;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: .3s;
    }

    .news-card:hover{
        transform: translateY(-6px);
        box-shadow: 0 0 25px #c0a799;
    }

    .news-thumb img{
        width: 100%;
        height: 200px;
        object-fit: contain;
        background: rgba(0,0,0,.35);
        padding: 8px;
        display: block;
    }

    .news-body{
        padding: 16px;
    }

    .news-date{
        font-size: 12px;
        display: inline-block;
        margin-bottom: 8px;
        color: #fccc7c;
    }

    .news-body h3{
        font-size: 18px;
        margin: 0 0 8px;
        color: #fff;
    }

    .news-body p{
        font-size: 14px;
        opacity: .9;
    }

    .news-more{
        border: 1px solid rgba(255,255,255,.35);
        background: rgba(0,0,0,.2);
        color: #fccc7c;
        font-weight: bold;
        padding: 8px 12px;
        text-align: center;
        border-radius: 10px;
        margin: 0 16px 16px;
        cursor: pointer;
        align-self: flex-start;
    }

    .news-pagination{
        margin-top: 26px;
        display: flex;
        justify-content: center;
    }

    .news-pagination .pagination{
        display: flex;
        gap: 8px;
        padding-left: 0;
        list-style: none;
    }

    .news-pagination .page-link{
        display: inline-block;
        padding: 8px 12px;
        border-radius: 10px;
        background: rgba(0,0,0,0.2);
        color: #ffffff;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .news-pagination .page-item.active .page-link{
        background: #8c4030;
        border-color: #8c4030;
        color: #fccc7c;
    }

    .news-modal{
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.65);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        padding: 20px;
        opacity: 0;
        transition: opacity .2s ease;
    }

    .news-modal.active{
        display: flex;
        opacity: 1;
    }

    .news-modal-content{
        background: #5f4636;
        border: 1px solid #c0a799;
        border-radius: 16px;
        max-width: 560px;
        width: 100%;
        padding: 20px;
        box-shadow: 0 0 30px rgba(0,0,0,.45);
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(10px) scale(.98);
        opacity: 0;
        transition: transform .2s ease, opacity .2s ease;
    }

    .news-modal.active .news-modal-content{
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .news-modal-content img{
        width: 100%;
        height: 320px;
        object-fit: contain;
        background: rgba(0,0,0,.35);
        padding: 8px;
        border-radius: 12px;
        margin-bottom: 14px;
    }

    .news-modal-date{
        color: #fccc7c;
        font-size: 12px;
    }

    .news-modal-content h3{
        margin: 8px 0 10px;
    }

    .news-modal-close{
        position: absolute;
        top: 10px;
        right: 14px;
        background: transparent;
        border: 0;
        color: #fff;
        font-size: 28px;
        cursor: pointer;
    }
</style>
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
        <h3 id="newsModalTitle"></h3>
        <p id="newsModalContent"></p>
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
