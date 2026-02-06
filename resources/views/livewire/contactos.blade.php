<section id="contacto" class="home-section contact-section">

    <h2>CONTACTO</h2>

    <!-- FORMULARIO -->
    <form class="contact-form">

        <div class="contact-grid">
            <div class="field">
    <label>Nombre</label>
    <input type="text" wire:model.defer="nombre">
</div>

<div class="field">
    <label>Apellido</label>
    <input type="text" wire:model.defer="apellido">
</div>

<div class="field">
    <label>Email *</label>
    <input type="email" wire:model.defer="email">
</div>

<div class="field">
    <label>Asunto</label>
    <input type="text" wire:model.defer="asunto">
</div>

<div class="field full">
    <label>Mensaje *</label>
    <textarea rows="4" wire:model.defer="mensaje"></textarea>
</div>
        </div>

        <div class="contact-actions">
            <button type="submit" wire:click.prevent="enviar">
                Enviar
            </button>
        </div>

    </form>

    <!-- MAPA (SE QUEDA COMO LO TENÍAS) -->
    <div class="contact-map">
        <iframe
            src="https://www.google.com/maps?q=Guadalajara%20Centro&output=embed"
            loading="lazy">
        </iframe>
    </div>

</section>