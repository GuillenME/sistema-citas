<div class="card">
    <div class="card-header">
        <h5>{{ $promocion_id ? 'Editar Promoción' : 'Nueva Promoción' }}</h5>
    </div>
    <div class="card-body">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form wire:submit.prevent="guardar">
            <div class="mb-3">
                <label class="form-label">Título <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.blur="title" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" wire:model.blur="description" rows="4" required></textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="mb-3">
                        <label class="form-label">Descuento (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('discount') is-invalid @enderror" wire:model.blur="discount" step="0.01" min="0" max="100" required>
                        @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-col">
                    <div class="mb-3">
                        <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" wire:model.blur="start_date" required>
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-col">
                    <div class="mb-3">
                        <label class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" wire:model.blur="end_date" required>
                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" wire:model.live="image" accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                
                <small class="text-muted d-block mt-2">Formatos permitidos: JPEG, PNG, JPG, GIF, WebP. Tamaño máximo: 2MB</small>

                <div class="mt-3">
                    <p class="mb-2"><strong>Vista previa:</strong></p>
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="preview preview-wide">
                    @elseif ($imagen_actual)
                        <img src="{{ asset('storage/' . $imagen_actual) }}" alt="Imagen actual" class="preview preview-wide">
                    @else
                        <div class="preview-empty">Sin imagen</div>
                    @endif
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" wire:model="published" id="published">
                <label class="form-check-label" for="published">Publicada</label>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                <a href="{{ route('promociones.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Guardando...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
