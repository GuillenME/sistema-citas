<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/clientes/dashboard.css') }}">
    <style>
        .comentarios-wrap {
            max-width: 900px;
            margin: 90px auto 40px;
            padding: 0 20px;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .comentarios-card {
            background: #5f4636;
            border: 1px solid rgba(250, 17, 17, 0.12);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .comentarios-card h2 {
            margin: 0 0 12px;
        }

        .comentarios-card label {
            display: block;
            margin: 12px 0 6px;
        }

        .comentarios-card textarea,
        .comentarios-card select {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(0,0,0,.2);
            color: #000;
        }

        .comentarios-card textarea {
            min-height: 120px;
            resize: vertical;
        }

        .comentarios-card .btn-save {
            margin-top: 12px;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid #e48815;
            background: transparent;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 0 12px #e48815;
        }

        .comentarios-card .btn-save:hover {
            box-shadow: 0 0 20px #e48815;
        }

        .comentario-item {
            border-top: 1px solid rgba(255,255,255,.1);
            padding-top: 14px;
            margin-top: 14px;
        }

        .comentario-meta {
            font-size: 12px;
            opacity: .85;
        }

        .alert-success {
            background: rgba(34,197,94,.2);
            border: 1px solid rgba(34,197,94,.5);
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body style="--bg-url: url('{{ asset('imagenes/registro_fondo.png') }}');">

<header>
    <div class="title">Cliente</div>

    <nav>
        <a href="{{ route('cliente.citas.create') }}">Agendar cita</a>
        <a href="{{ route('cliente.citas.index') }}">Mis citas</a>
        <a href="{{ route('cliente.comentarios') }}">Comentarios</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="logout-btn" onclick="mostrarModalLogout()">
            Cerrar sesión
        </button>
    </form>
</header>

<div class="comentarios-wrap">
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

            <label for="rating">Calificación (opcional)</label>
            <select id="rating" name="rating">
                <option value="">Sin calificación</option>
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
                        · Calificación: {{ $review->rating }}/5
                    @endif
                </div>
            </div>
        @empty
            <p>Aún no has dejado comentarios.</p>
        @endforelse
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
