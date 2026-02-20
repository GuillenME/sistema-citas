<div class="form-container">

    <div class="form-grid">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.defer="nombre">
            @error('nombre') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Duración (min)</label>
            <input type="number" wire:model.defer="duracion_minutos">
            @error('duracion_minutos') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group full">
            <label>Descripción</label>
            <textarea wire:model.defer="descripcion"></textarea>
        </div>

        <div class="form-group">
            <label>Imagen del servicio</label>
            <input type="file" wire:model="image" accept="image/*">
        </div>



        <div class="form-group">
            <label>Precio</label>
            <input type="number" step="0.01" wire:model.defer="precio">
            @error('precio') <small class="error">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>Vista previa</label>
            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="preview preview-wide">
            @elseif ($servicio->image)
                <img src="{{ asset('storage/'.$servicio->image) }}" class="preview preview-wide">
            @else
                <div class="preview-empty">Sin imagen</div>
            @endif
        </div>

        <div class="form-group">
            <label>Activo</label>
            <input type="checkbox" wire:model.defer="activo">
            @error('activo') <small class="error">{{ $message }}</small> @enderror
        </div>

    </div>

    <div class="actions">
        <a href="{{ route('admin.servicios.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button type="button"
                class="btn btn-save"
                wire:click="abrirConfirmacion">
            Guardar cambios
        </button>
    </div>

    {{-- MODAL CONFIRMACIÓN --}}
    @if ($confirmar)
        <div class="modal-overlay" wire:click.self="$set('confirmar', false)">
            <div class="modal-box">
                <h3>¿Actualizar servicio?</h3>
                <p>Los cambios se guardarán permanentemente.</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel"
                            wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>

                    <button class="btn btn-save"
                            wire:click="actualizar"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Actualizar</span>
                        <span wire:loading>Actualizando…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
