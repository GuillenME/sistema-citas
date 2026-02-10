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

        <div class="form-group">
            <label>Email</label>
            <input value="{{ $usuario->email }}" disabled class="input-disabled">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input wire:model.defer="telefono">
        </div>

        <div class="form-group">
            <label>Contraseña nueva</label>
            <div class="input-icon">
                <input type="password" wire:model.defer="password" id="passwordEditInput">
                <button type="button" class="btn-eye" data-toggle="passwordEditInput" aria-label="Mostrar contraseña">
                    <svg class="eye-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 5c5.2 0 9.3 3.3 11 7-1.7 3.7-5.8 7-11 7S2.7 15.7 1 12c1.7-3.7 5.8-7 11-7zm0 2.2c-3.9 0-7.2 2.4-8.7 4.8 1.5 2.4 4.8 4.8 8.7 4.8s7.2-2.4 8.7-4.8c-1.5-2.4-4.8-4.8-8.7-4.8zm0 1.8a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Confirmar contraseña</label>
            <div class="input-icon">
                <input type="password" wire:model.defer="password_confirmation" id="passwordEditConfirmInput">
                <button type="button" class="btn-eye" data-toggle="passwordEditConfirmInput" aria-label="Mostrar confirmación de contraseña">
                    <svg class="eye-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 5c5.2 0 9.3 3.3 11 7-1.7 3.7-5.8 7-11 7S2.7 15.7 1 12c1.7-3.7 5.8-7 11-7zm0 2.2c-3.9 0-7.2 2.4-8.7 4.8 1.5 2.4 4.8 4.8 8.7 4.8s7.2-2.4 8.7-4.8c-1.5-2.4-4.8-4.8-8.7-4.8zm0 1.8a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
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
                <h3>Actualizar recepcionista</h3>
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

<script>
    document.querySelectorAll('.btn-eye').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-toggle');
            const input = document.getElementById(id);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
</script>
