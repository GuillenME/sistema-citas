<div>

    <h1>Editar empleado</h1>

    <div class="card">

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" wire:model.defer="nombre">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" wire:model.defer="telefono">
            </div>

            <div class="form-group full">
                <label>Especialidad</label>
                <input type="text" wire:model.defer="especialidad">
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
