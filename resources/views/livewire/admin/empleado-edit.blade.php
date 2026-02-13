<div>

    <div class="card">

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" wire:model.defer="nombre">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" wire:model.defer="telefono" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
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

            <div class="form-group full">
                <label>Estado</label>
                <select wire:model="activo">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

        </div>

        <div class="actions">
            <a href="{{ route('admin.empleados.index') }}" class="btn btn-cancel">
                Cancelar
            </a>

            <button class="btn btn-save" wire:click="actualizar">
                Guardar cambios
            </button>
        </div>

    </div>

</div>

