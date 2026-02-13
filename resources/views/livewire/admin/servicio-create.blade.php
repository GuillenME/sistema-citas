<div>

    <div class="form-grid">

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.defer="nombre">
            @error('nombre') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Duracion (min)</label>
            <input type="number" wire:model.defer="duracion_minutos">
            @error('duracion_minutos') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group full">
            <label>Descripcion</label>
            <textarea wire:model.defer="descripcion"></textarea>
            @error('descripcion') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Imagen del servicio</label>
            <input type="file" wire:model="image">
            @error('image') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Precio</label>
            <input type="number" step="0.01" wire:model.defer="precio">
            @error('precio') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Vista previa</label>
            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="preview preview-wide">
            @else
                <div class="preview-empty">Sin imagen</div>
            @endif
        </div>

    </div>

    <div class="actions">
        <a href="{{ route('admin.servicios.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button type="button" class="btn btn-save" wire:click="abrirConfirmacion">
            Guardar servicio
        </button>
    </div>

    {{-- MODAL --}}
    @if ($confirmar)
        <div class="modal-overlay">
            <div class="modal-box">
                <h3>Guardar servicio?</h3>
                <p>Deseas guardar este nuevo servicio?</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>
                    <button class="btn btn-save" wire:click="guardar">
                        Si, guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
