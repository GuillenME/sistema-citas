<div class="card">
    <div class="card-header">
        <h5>{{ $servicio_id ? 'Editar Servicio' : 'Nuevo Servicio' }}</h5>
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
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.blur="name" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" wire:model.blur="description" rows="4" required></textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Duración (minutos) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" wire:model.blur="duration_minutes" required>
                        @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Precio ($) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" wire:model.blur="price" step="0.01" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <input type="checkbox" class="form-check-input" wire:model="active" id="active">
                <label class="form-check-label" for="active">Servicio activo</label>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
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
