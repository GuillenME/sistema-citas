<div class="header">
    <div class="header-left">
        <a href="{{ route('admin.dashboard') }}" class="back-arrow">←</a>
        <h1>{{ $title }}</h1>
    </div>

    <div style="display:flex; gap:15px; align-items:center;">
        {{ $slot }}

        <button class="btn-logout" wire:click="abrirLogout">
            Cerrar sesión
        </button>
    </div>
</div>
