@extends('layouts.public')

@section('title', 'Comentarios')

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

    .services {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
    }

    .card {
        background: #5f4636;
        border-radius: 16px;
        padding: 15px;
        border: 1px solid #c0a799;
        transition: .3s;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0 25px #c0a799;
    }

    .card h3 {
        color: #fff;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .stars {
        color: #fbbc04;
        letter-spacing: 3px;
        margin-bottom: 10px;
        text-align: center;
    }

    .comment {
        font-size: 14px;
        line-height: 1.6;
        opacity: .95;
        margin-bottom: 16px;
    }

    .meta {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        opacity: .9;
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(0, 0, 0, .35);
        display: grid;
        place-items: center;
        color: #fccc7c;
        font-weight: bold;
        text-transform: uppercase;
        border: 1px solid rgba(255,255,255,.12);
    }

    .reviews-pagination{
        margin-top: 26px;
        display: flex;
        justify-content: center;
    }

    .reviews-pagination .pagination{
        display: flex;
        gap: 8px;
        padding-left: 0;
        list-style: none;
    }

    .reviews-pagination .page-link{
        display: inline-block;
        padding: 8px 12px;
        border-radius: 10px;
        background: rgba(0,0,0,0.2);
        color: #ffffff;
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .reviews-pagination .page-item.active .page-link{
        background: #8c4030;
        border-color: #8c4030;
        color: #fccc7c;
    }

    .empty-state{
        background: #5f4636;
        border: 1px dashed #c0a799;
        border-radius: 12px;
        padding: 22px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<section>
    <h1>Comentarios</h1>
    <p class="subtitle">Gracias por compartir tu opinion con nosotros.</p>

    @forelse ($reviews as $review)
        @php
            $email = $review->user_email ?? '';
            $user = strstr($email, '@', true);
            $domain = strstr($email, '@');

            $userMasked = $user ? substr($user, 0, 2) . '***' : 'u***';
            $domainClean = $domain ? ltrim($domain, '@') : '';
            $domainParts = $domainClean ? explode('.', $domainClean) : [];
            $domainMasked = $domainParts ? substr($domainParts[0], 0, 2) . '***' : '';
            $domainTld = count($domainParts) > 1 ? end($domainParts) : '';

            $maskedEmail = ($domainMasked && $domainTld)
                ? $userMasked . '@' . $domainMasked . '.' . $domainTld
                : $userMasked;

            $initial = strtoupper(substr($maskedEmail, 0, 1));
            $rating = max(1, min($review->rating ?? 5, 5));
        @endphp

        @if ($loop->first)
            <div class="services">
        @endif

        <div class="card">
            <div class="stars">
                @for ($i = 0; $i < $rating; $i++)
                    &#9733;
                @endfor
            </div>
            <p class="comment">{{ $review->comment }}</p>
            <div class="meta">
                <div class="avatar">{{ $initial }}</div>
                <div>
                    <div>{{ $maskedEmail }}</div>
                    <div>{{ $review->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        @if ($loop->last)
            </div>
        @endif
    @empty
        <div class="empty-state">No hay comentarios aun.</div>
    @endforelse

    @if ($reviews->hasPages())
        <div class="reviews-pagination">
            {{ $reviews->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</section>
@endsection
