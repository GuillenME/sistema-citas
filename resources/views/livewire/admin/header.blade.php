<div class="header">
    <div class="header-left">
        <a href="{{ route('admin.dashboard') }}" class="back-arrow">←</a>
        <h1>{{ $title }}</h1>
    </div>

    <div class="header-actions-inline">
        {{ $slot }}

        <button class="btn-logout" wire:click="abrirLogout">
            Cerrar sesión
        </button>
    </div>
</div>
