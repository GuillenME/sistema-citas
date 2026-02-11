@extends('layouts.public')

@section('title', 'Comentarios')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/public/comentarios.css') }}">
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
