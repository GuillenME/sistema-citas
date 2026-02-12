<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/comentarios.css') }}">
</head>
<body class="cliente-comentarios-page">

@include('cliente.partials.menu')

<div class="comentarios-wrap">
    <h1 class="comentarios-title">Comentarios</h1>
    <p class="comentarios-subtitle">Tu opinion nos ayuda a mejorar.</p>

    <div class="comentarios-grid">
        <div class="comentarios-card">
        <h2>Dejar comentario</h2>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('cliente.comentarios.store') }}">
            @csrf
            <label for="comment">Comentario</label>
            <textarea id="comment" name="comment" required>{{ old('comment') }}</textarea>
            @error('comment') <small class="form-error">{{ $message }}</small> @enderror

            <label>Calificacion (opcional)</label>
            <fieldset class="rating-input" aria-label="Calificacion">
                <input
                    type="radio"
                    id="rating-none"
                    name="rating"
                    value=""
                    @checked(old('rating') === null || old('rating') === '')
                >
                <label for="rating-none" class="rating-none">Sin calificacion</label>

                @for ($i = 1; $i <= 5; $i++)
                    <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" @checked((string) old('rating') === (string) $i)>
                    <label for="rating-{{ $i }}" class="rating-star">{{ $i }}</label>
                @endfor
            </fieldset>
            @error('rating') <small class="form-error">{{ $message }}</small> @enderror

            <button type="submit" class="btn-save">Enviar comentario</button>
        </form>
    </div>

    <div class="comentarios-card">
        <h2>Mis comentarios</h2>
        @forelse ($reviews as $review)
            <div class="comentario-item">
                <div>{{ $review->comment }}</div>
                <div class="comentario-meta">
                    {{ $review->created_at->format('d/m/Y H:i') }}
                    @if ($review->rating)
                        <span class="comentario-rating">
                            Calificacion: {{ $review->rating }}/5
                            <span class="rating-stars" aria-hidden="true">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <span class="filled">★</span>
                                    @else
                                        <span class="empty">☆</span>
                                    @endif
                                @endfor
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <p>Aun no has dejado comentarios.</p>
        @endforelse

        @if ($reviews->hasPages())
            <div class="comentarios-pagination">
                <div class="pagination-info">
                    Pagina {{ $reviews->currentPage() }} de {{ $reviews->lastPage() }} ({{ $reviews->total() }} comentarios)
                </div>
                {{ $reviews->links('pagination::simple-bootstrap-4') }}
            </div>
        @endif
        </div>
    </div>
</div>

<!-- MODAL LOGOUT -->
<div id="modalLogout" class="modal-overlay" onclick="if(event.target === this) cerrarModalLogout()">
    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro de que deseas cerrar sesión?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-confirm" onclick="confirmarLogout()">Sí, cerrar sesión</button>
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalLogout()">Cancelar</button>
        </div>
    </div>
</div>

<script>
    function mostrarModalLogout() {
        document.getElementById('modalLogout').classList.add('active');
    }

    function cerrarModalLogout() {
        document.getElementById('modalLogout').classList.remove('active');
    }

    function confirmarLogout() {
        document.getElementById('logoutForm').submit();
    }
</script>

</body>
</html>
