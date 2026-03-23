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

    <div class="form-grid">
        <div class="form-group">
            <label>Título</label>
            <input type="text" wire:model.defer="titulo">
            @error('titulo')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Descuento (%)</label>
            <input type="number" wire:model.defer="descuento" min="1" max="100">
            @error('descuento')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Fecha inicio</label>
            <input type="date" wire:model.defer="fecha_inicio">
            @error('fecha_inicio')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Fecha fin</label>
            <input type="date" wire:model.defer="fecha_fin">
            @error('fecha_fin')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group full">
            <label>Descripción</label>
            <textarea wire:model.defer="descripcion"></textarea>
            @error('descripcion')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            @php
                $selectedIds = collect($servicios)->map(fn ($id) => (int) $id)->all();
                $selectedCount = count($selectedIds);
                $limitReached = $selectedCount >= 5;
            @endphp

            <div class="services-picker-head">
                <label>Servicios / Especialidad (max. 5)</label>
                <span class="services-picker-counter {{ $limitReached ? 'is-limit' : '' }}">
                    {{ $selectedCount }}/5 seleccionados
                </span>
            </div>

            <div class="services-picker-scroll-card">
                <div class="services-picker-scroll-body employee-services-scroll">
                    <div class="services-picker-grid employee-services-grid">
                        @foreach ($listaServicios as $servicio)
                            @php
                                $isSelected = in_array($servicio->id, $selectedIds, true);
                                $isDisabled = !$isSelected && $limitReached;
                            @endphp
                            <button
                                type="button"
                                class="service-pick-card {{ $isSelected ? 'is-selected' : '' }} {{ $isDisabled ? 'is-disabled' : '' }}"
                                wire:click="toggleServicio({{ $servicio->id }})"
                                @disabled($isDisabled)
                            >
                                <span class="service-pick-check" aria-hidden="true">&#10003;</span>
                                <span class="service-pick-name">{{ $servicio->name }}</span>
                                <span class="service-pick-meta">
                                    {{ $servicio->duration_minutes }} min - ${{ number_format((float) $servicio->price, 2) }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <small>Selecciona hasta 5 servicios. Al llegar al límite, los demás se desactivan.</small>
            @error('servicios')
                <small class="error">{{ $message }}</small>
            @enderror
            @error('servicios.*')
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
            @else
                <div class="preview-empty">Sin imagen</div>
            @endif
        </div>

        <div class="form-group full checkbox">
            <input type="checkbox" wire:model="publicada" id="publicada">
            <label for="publicada">Publicar promoción</label>
        </div>
    </div>

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
