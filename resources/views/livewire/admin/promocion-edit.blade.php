<div class="card">

    <div class="form-grid">

        <div class="form-group">
            <label>Título</label>
            <input type="text" wire:model.defer="titulo">
            @error('titulo') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Descuento (%)</label>
            <input type="number" wire:model.defer="descuento">
            @error('descuento') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Fecha inicio</label>
            <input type="date" wire:model.defer="fecha_inicio">
            @error('fecha_inicio') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Fecha fin</label>
            <input type="date" wire:model.defer="fecha_fin">
            @error('fecha_fin') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group full">
            <label>Descripción</label>
            <textarea wire:model.defer="descripcion"></textarea>
            @error('descripcion') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group full">
            <label>Servicios aplicables</label>
            <select multiple wire:model.defer="servicios">
                @foreach($listaServicios as $servicio)
                    <option value="{{ $servicio->id }}">
                        {{ $servicio->name }}
                    </option>
                @endforeach
            </select>
            @error('servicios') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Imagen</label>
            <input type="file" wire:model="image">
            @error('image') <span class="error">{{ $message }}</span> @enderror
        </div>

        @if ($image)
            <div class="form-group full">
                <img src="{{ $image->temporaryUrl() }}" class="preview">
            </div>
        @elseif ($promocion->image)
            <div class="form-group full">
                <img src="{{ asset('storage/'.$promocion->image) }}" class="preview">
            </div>
        @endif

        <div class="form-group full checkbox">
            <input type="checkbox" wire:model="publicada">
            <label>Publicar promoción</label>
        </div>

    </div>

    <div class="actions">
        <a href="{{ route('admin.promociones.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button type="button"
                class="btn btn-save"
                wire:click="abrirConfirmacion">
            Guardar cambios
        </button>
    </div>

    {{-- MODAL --}}
    @if ($confirmar)
        <div class="modal-overlay" wire:click.self="$set('confirmar', false)">
            <div class="modal-box">
                <h3>¿Guardar promoción?</h3>
                <p>¿Deseas guardar los cambios de esta promoción?</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel"
                            wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>

                    <button class="btn btn-save"
                            wire:click="actualizar">
                        Sí, guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
