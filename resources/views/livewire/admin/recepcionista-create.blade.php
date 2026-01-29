<div class="card">

    <div class="form-grid">

        <div class="form-group">
            <label>Nombre</label>
            <input wire:model.defer="nombre">
            @error('nombre') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Apellido</label>
            <input wire:model.defer="apellido">
        </div>

        <div class="form-group full">
            <label>Email</label>
            <input wire:model.defer="email">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input wire:model.defer="telefono">
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" wire:model.defer="password">
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

    </div>

    <div class="actions">
        <a href="{{ route('admin.recepcionistas.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button class="btn btn-save" wire:click="abrirConfirmacion">
            Guardar
        </button>
    </div>

    {{-- MODAL --}}
    @if ($confirmar)
        <div class="modal-overlay">
            <div class="modal-box">
                <h3>¿Crear recepcionista?</h3>
                <p>¿Deseas guardar este recepcionista?</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>
                    <button class="btn btn-save" wire:click="guardar">
                        Sí, guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
