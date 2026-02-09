<div class="form-container">
    @if ($errors->has('servicios'))
        <div id="promoErrorModalCreate" class="modal-overlay" onclick="document.getElementById('promoErrorModalCreate').style.display='none'">
            <div class="modal-box" onclick="event.stopPropagation()">
                <h3>No se pudo guardar</h3>
                <p>{{ $errors->first('servicios') }}</p>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="document.getElementById('promoErrorModalCreate').style.display='none'">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <label>Título</label>
    <input type="text" wire:model.defer="titulo">
    @error('titulo')
        <small class="error">{{ $message }}</small>
    @enderror

    <label>Descripción</label>
    <textarea wire:model.defer="descripcion"></textarea>
    @error('descripcion')
        <small class="error">{{ $message }}</small>
    @enderror

    <label>Descuento (%)</label>
    <input type="number" wire:model.defer="descuento" min="1" max="100">
    @error('descuento')
        <small class="error">{{ $message }}</small>
    @enderror

    <label>Fecha inicio</label>
    <input type="date" wire:model.defer="fecha_inicio">
    @error('fecha_inicio')
        <small class="error">{{ $message }}</small>
    @enderror

    <label>Fecha fin</label>
    <input type="date" wire:model.defer="fecha_fin">
    @error('fecha_fin')
        <small class="error">{{ $message }}</small>
    @enderror

    <label>Servicios</label>
    <select wire:model="servicios" multiple>
        @foreach ($listaServicios as $servicio)
            <option value="{{ $servicio->id }}">
                {{ $servicio->name }}
            </option>
        @endforeach
    </select>
    @error('servicios')
        <small class="error">{{ $message }}</small>
    @enderror

    <div class="form-group checkbox">
        <input type="checkbox" wire:model="publicada" id="publicada">
        <label for="publicada">Publicar promoción</label>
    </div>


    <label>Imagen</label>
    <input type="file" wire:model="image">
    @error('image')
        <small class="error">{{ $message }}</small>
    @enderror

    @if ($image)
        <div class="image-preview-wrapper">
            <img src="{{ $image->temporaryUrl() }}" class="preview">
        </div>
    @endif


    <div class="actions">
        <a href="{{ route('admin.promociones.index') }}" class="btn btn-cancel">
            Cancelar
        </a>

        <button type="button" class="btn btn-save" wire:click="abrirConfirmacion">
            Guardar promoción
        </button>
    </div>

    {{-- MODAL --}}
    @if ($confirmar)
        <div class="modal-overlay" wire:click.self="$set('confirmar', false)">
            <div class="modal-box">
                <h3>¿Crear promoción?</h3>

                <p>
                    <strong>{{ $titulo }}</strong><br>
                    Descuento: {{ $descuento }}%<br>
                    Servicios: {{ count($servicios) }}
                </p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>

                    <button class="btn btn-save" wire:click="guardar" wire:loading.attr="disabled">
                        <span wire:loading.remove>Confirmar</span>
                        <span wire:loading>Guardando…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
