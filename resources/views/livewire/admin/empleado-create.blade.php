<div>

    <div class="card">

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" wire:model.defer="nombre">
                @error('nombre') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" wire:model.defer="telefono">
                @error('telefono') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group full">
                <label>Servicios / Especialidad (máx. 5)</label>
                <select wire:model.defer="serviciosSeleccionados" multiple>
                    @foreach ($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->name }}</option>
                    @endforeach
                </select>
                <small>Selecciona hasta 5 servicios.</small>
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

    {{-- MODAL --}}
    @if($confirmar)
        <div class="modal-overlay">
            <div class="modal-box">
                <h3>¿Guardar empleado?</h3>
                <p>Confirma que deseas crear este empleado</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="$set('confirmar', false)">
                        Cancelar
                    </button>
                    <button class="btn btn-save" wire:click="guardar">
                        Sí, guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

