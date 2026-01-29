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
            <input value="{{ $usuario->email }}" disabled>
        </div>

        <div class="form-group full">
            <label>Teléfono</label>
            <input wire:model.defer="telefono">
        </div>

    </div>

    <div class="actions">
        <a href="{{ route('admin.recepcionistas.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button class="btn btn-save" wire:click="abrirConfirmacion">
            Guardar cambios
        </button>
    </div>

    {{-- MODAL --}}
    @if ($confirmar)
        <div class="modal-overlay">
            <div class="modal-box">
                <h3>¿Actualizar recepcionista?</h3>
                <p>¿Deseas guardar los cambios?</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>
                    <button class="btn btn-save" wire:click="actualizar">
                        Sí, actualizar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
