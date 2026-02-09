<div class="card">
    @if ($errors->has('servicios'))
        <div id="promoErrorModalEdit" class="modal-overlay" onclick="document.getElementById('promoErrorModalEdit').style.display='none'">
            <div class="modal-box" onclick="event.stopPropagation()">
                <h3>No se pudo guardar</h3>
                <p>{{ $errors->first('servicios') }}</p>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="document.getElementById('promoErrorModalEdit').style.display='none'">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

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
            {{-- 🔥 SIN defer para evitar bugs --}}
            <select multiple wire:model="servicios">
                @foreach($listaServicios as $servicio)
                    <option value="{{ $servicio->id }}">
                        {{ $servicio->name }}
                    </option>
                @endforeach
            </select>
            @error('servicios') <span class="error">{{ $message }}</span> @enderror
        </div>

        {{-- ✅ CHECKBOX ARREGLADO --}}
        <div class="form-group full checkbox">
            <input type="checkbox" id="publicada" wire:model="publicada">
            <label for="publicada">Publicar promoción</label>
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

    {{-- MODAL CONFIRMACIÓN --}}
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
                            wire:click="actualizar"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Sí, guardar</span>
                        <span wire:loading>Guardando…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
