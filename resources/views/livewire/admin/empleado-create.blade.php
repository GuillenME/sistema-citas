<div>

    <div class="card">

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" wire:model.defer="nombre">
                @error('nombre') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Telefono</label>
                <input type="tel" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" wire:model.defer="telefono" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                @error('telefono') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group full">
                @php
                    $selectedIds = collect($serviciosSeleccionados)->map(fn ($id) => (int) $id)->all();
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
                            @foreach ($servicios as $servicio)
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

                <small>Selecciona hasta 5 servicios. Al llegar al limite, los demas se desactivan.</small>
                @error('serviciosSeleccionados') <span class="error">{{ $message }}</span> @enderror
                @error('serviciosSeleccionados.*') <span class="error">{{ $message }}</span> @enderror
            </div>

        </div>

        <div class="actions">
            <a href="{{ route('admin.empleados.index') }}" class="btn btn-cancel">
                Cancelar
            </a>

            <button class="btn btn-save" wire:click="abrirConfirmacion">
                Guardar empleado
            </button>
        </div>
    </div>

    @if($confirmar)
        <div class="modal-overlay">
            <div class="modal-box">
                <h3>Guardar empleado?</h3>
                <p>Confirma que deseas crear este empleado</p>

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
