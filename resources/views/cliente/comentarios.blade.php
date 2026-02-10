<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clientes/dashboard.css') }}">
    <style>
        .comentarios-wrap {
            max-width: 1100px;
            margin: 100px auto 50px;
            padding: 0 20px;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .comentarios-title {
            font-family: "Playfair Display", serif;
            font-size: 28px;
            margin: 0 0 18px;
            text-align: center;
            color: #fff7ef;
        }

        .comentarios-subtitle {
            text-align: center;
            color: #f0e2d2;
            font-size: 13px;
            margin-bottom: 26px;
        }

        .comentarios-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(260px, 1fr));
            gap: 24px;
        }

        .comentarios-card {
            background: #5f4030;
            border: 1px solid rgba(252, 204, 124, 0.25);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
        }

        .comentarios-card h2 {
            margin: 0 0 12px;
            font-size: 18px;
        }

        .comentarios-card label {
            display: block;
            margin: 12px 0 6px;
            color: #f0e2d2;
            font-size: 13px;
        }

        .comentarios-card textarea,
        .comentarios-card select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(252, 204, 124, 0.25);
            background: rgba(0, 0, 0, 0.25);
            color: #fffaf4;
            outline: none;
        }

        .comentarios-card textarea {
            min-height: 120px;
            resize: vertical;
        }

        .comentarios-card textarea:focus,
        .comentarios-card select:focus {
            border-color: rgba(252, 204, 124, 0.55);
            box-shadow: 0 0 0 2px rgba(252, 204, 124, 0.15);
        }

        .comentarios-card .btn-save {
            margin-top: 14px;
            padding: 10px 18px;
            border-radius: 10px;
            border: 1px solid rgba(252, 204, 124, 0.6);
            background: rgba(252, 204, 124, 0.35);
            color: #1f140d;
            cursor: pointer;
            font-weight: 600;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .comentarios-card .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
        }

        .comentario-item {
            border-top: 1px solid rgba(255,255,255,.18);
            padding-top: 14px;
            margin-top: 14px;
        }

        .comentario-item > div:first-child {
            color: #fffaf4;
        }

        .comentario-meta {
            font-size: 12px;
            color: #efe0cf;
            margin-top: 6px;
        }

        .comentario-rating {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(252, 204, 124, 0.15);
            border: 1px solid rgba(252, 204, 124, 0.4);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            color: #ffdca0;
        }

        .comentarios-pagination {
            margin-top: 16px;
        }

        .comentarios-pagination .pagination {
            display: flex;
            gap: 8px;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .comentarios-pagination .page-link {
            background: rgba(252, 204, 124, 0.12);
            border: 1px solid rgba(252, 204, 124, 0.35);
            color: #fffaf4;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .comentarios-pagination .page-item.active .page-link {
            background: rgba(252, 204, 124, 0.35);
            border-color: rgba(252, 204, 124, 0.6);
        }

        .rating-stars {
            display: inline-flex;
            gap: 2px;
            margin-left: 6px;
            font-size: 12px;
        }

        .rating-stars .filled {
            color: #fccc7c;
        }

        .rating-stars .empty {
            color: rgba(255, 255, 255, 0.35);
        }

        .alert-success {
            background: rgba(34,197,94,.2);
            border: 1px solid rgba(34,197,94,.5);
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .comentarios-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body style="--bg-url: url('{{ asset('imagenes/registro_fondo.png') }}');">

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
            @error('comment') <small style="color:#f87171">{{ $message }}</small> @enderror

            <label for="rating">Calificacion (opcional)</label>
            <select id="rating" name="rating">
                <option value="">Sin calificacion</option>
                <option value="5" @selected(old('rating') == 5)>5</option>
                <option value="4" @selected(old('rating') == 4)>4</option>
                <option value="3" @selected(old('rating') == 3)>3</option>
                <option value="2" @selected(old('rating') == 2)>2</option>
                <option value="1" @selected(old('rating') == 1)>1</option>
            </select>
            @error('rating') <small style="color:#f87171">{{ $message }}</small> @enderror

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



