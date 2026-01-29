<div>
    @if($mostrar)
        <div class="modal-overlay" wire:click.self="cerrar">
            <div class="modal-box">
                <h3>¿Cerrar sesión?</h3>
                <p>¿Estás seguro de que deseas cerrar sesión?</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cerrar">
                        Cancelar
                    </button>

                    <button class="btn btn-save" wire:click="logout">
                        Sí, cerrar sesión
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
