@php
    $lastUpdated = $updatedAt ? $updatedAt->format('d/m/Y') : date('d/m/Y');
@endphp

<style>
:root {
    --primary: #e48815;
    --gold: #fccc7c;
    --brown: #8c4030;
    --soft-brown: #b28562;
    --light: #c0a799;
    --dark: #5f4636;
}

.legal-wrapper {
    display: flex;
    min-height: 100vh;
    background: #dfb26f;
    font-family: "Manrope", sans-serif;
}

.legal-left {
    flex: 1;
    padding: 60px 80px;
}

.legal-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    margin-bottom: 26px;
    border-radius: 999px;
    background: linear-gradient(145deg, var(--brown), var(--soft-brown));
    color: #fff7ec;
    text-decoration: none;
    font-size: 28px;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
}

.legal-back:hover {
    transform: translateY(-2px);
    filter: brightness(1.05);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.24);
}

.legal-header h1 {
    font-family: "Cinzel", serif;
    font-size: 34px;
    color: var(--brown);
    margin-bottom: 8px;
}

.legal-header p {
    color: var(--dark);
    margin-bottom: 32px;
}

.legal-content {
    border-radius: 24px;
    background: rgba(255, 247, 236, 0.62);
    box-shadow: 0 18px 34px rgba(92, 58, 21, 0.14);
    padding: 28px;
    color: #3c3028;
    line-height: 1.8;
    white-space: pre-line;
}

.legal-right {
    width: 35%;
    background: linear-gradient(135deg, var(--soft-brown), var(--brown));
    position: relative;
    overflow: hidden;
}

.shape {
    position: absolute;
    transform: rotate(45deg);
    opacity: 0.85;
}

.shape-1 {
    width: 200px;
    height: 200px;
    background: var(--gold);
    top: 20%;
    right: -80px;
}

.shape-2 {
    width: 150px;
    height: 150px;
    background: var(--primary);
    bottom: 20%;
    right: -60px;
}

.shape-3 {
    width: 100px;
    height: 100px;
    background: var(--light);
    top: 60%;
    right: 40px;
}

@media (max-width: 900px) {
    .legal-wrapper {
        flex-direction: column;
    }

    .legal-left {
        padding: 26px 18px 34px;
    }

    .legal-content {
        padding: 22px 18px;
    }

    .legal-right {
        width: 100%;
        height: 150px;
    }
}
</style>

<div class="legal-wrapper">
    <div class="legal-left">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
           class="legal-back"
           onclick="if (window.history.length > 1) { event.preventDefault(); window.history.back(); }"
           aria-label="Regresar">
            &larr;
        </a>

        <div class="legal-header">
            <h1>{{ $title }}</h1>
            <p>Ultima actualizacion: {{ $lastUpdated }}</p>
        </div>

        <div class="legal-content">{{ $content }}</div>
    </div>

    <div class="legal-right">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
</div>
