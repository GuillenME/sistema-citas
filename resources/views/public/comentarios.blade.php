@extends('layouts.public')

@section('title', 'Comentarios')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/public/comentarios.css') }}">
@endsection

@section('content')
<section class="reviews-page">
    <div class="reviews-head">
        <span class="reviews-eyebrow">EXPERIENCIAS REALES</span>
        <h1 class="reviews-title">Comentarios</h1>
        <p class="subtitle">Gracias por compartir tu opinion con nosotros.</p>
    </div>

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
            <div class="reviews-grid reviews-premium-grid">
        @endif

        <article class="review-card review-premium-card">
            <div class="premium-review-top">
                <div class="premium-review-stars" aria-label="Calificacion del cliente">
                    @for ($i = 0; $i < $rating; $i++)
                        &#9733;
                    @endfor
                </div>

                @if($review->service)
                    <span class="premium-review-tag">{{ $review->service->name }}</span>
                @endif
            </div>

            <p class="premium-review-quote">"{{ $review->comment }}"</p>

            <div class="premium-review-meta">
                <div class="premium-review-avatar">{{ $initial }}</div>
                <div class="premium-review-author">
                    @php
                        $nombre = $review->user->name ?? 'Cliente';
                        $apellidoInicial = $review->user && $review->user->last_name
                            ? strtoupper(substr($review->user->last_name, 0, 1)) . '.'
                            : '';
                    @endphp
                    <strong>{{ $nombre }} {{ $apellidoInicial }}</strong>
                    <span>{{ $review->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </article>

        @if ($loop->last)
            </div>
        @endif
    @empty
        <div class="reviews-empty">No hay comentarios aun.</div>
    @endforelse

</section>
@endsection
