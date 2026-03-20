<div class="form-container">

    <div class="form-grid">
        <div class="form-group">
            <label>Título</label>
            <input type="text" wire:model.defer="titulo">
            @error('titulo')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Fecha publicacion</label>
            <input type="date" wire:model.defer="fecha_publicacion">
            @error('fecha_publicacion')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group full">
            <label>Contenido</label>
            <textarea wire:model.defer="contenido"></textarea>
            @error('contenido')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Imagen</label>
            <input type="file" wire:model="image" accept="image/*">
            @error('image')
                <small class="error">{{ $message }}</small>
            @enderror
            @if ($image)
                <div class="image-preview-wrapper inline">
                    <img src="{{ $image->temporaryUrl() }}" class="preview preview-wide">
                </div>
            @elseif ($noticia->image)
                <div class="image-preview-wrapper inline">
                    <img src="{{ asset('storage/' . $noticia->image) }}" class="preview preview-wide">
                </div>
            @else
                <div class="preview-empty">Sin imagen</div>
            @endif
        </div>

        <div class="form-group checkbox">
            <input type="checkbox" wire:model="publicada" id="publicada">
            <label for="publicada">Publicar noticia</label>
        </div>
    </div>

    <div class="actions">
        <a href="{{ route('admin.noticias.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button type="button" class="btn btn-save" wire:click="abrirConfirmacion">
            Guardar cambios
        </button>
    </div>

    @if ($confirmar)
        <div class="modal-overlay" wire:click.self="$set('confirmar', false)">
            <div class="modal-box">
                <h3>Actualizar noticia</h3>

                <p>
                    <strong>{{ $titulo }}</strong><br>
                    Fecha: {{ $fecha_publicacion }}
                </p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>

                    <button class="btn btn-save" wire:click="actualizar" wire:loading.attr="disabled">
                        <span wire:loading.remove>Confirmar</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
