<section id="contacto" class="home-section contact-section contact-lux">
    @php
        $footerAddress = $homeSetting->footer_address ?? 'Calle Principal #123 - Guadalajara';
        $footerPhone = $homeSetting->footer_phone ?? '33 1234 5678';
        $footerHours = $homeSetting->footer_hours ?? 'Lun-Sab 9:00-20:00';
    @endphp

    <div class="contact-lux-head">
        <h2>CONTACTANOS</h2>
        <p>Vive la experiencia del estandar de oro en belleza. Nuestro equipo esta listo para disenar tu proximo look iconico.</p>
    </div>

    <div class="contact-lux-grid">
        <form class="contact-lux-form" wire:submit.prevent="enviar">
            <h3>Envianos un mensaje</h3>

            <div class="contact-lux-fields">
                <div class="contact-lux-field">
                    <label>NOMBRE</label>
                    <input type="text" wire:model.defer="nombre" placeholder="Tu nombre">
                </div>

                <div class="contact-lux-field">
                    <label>APELLIDOS</label>
                    <input type="text" wire:model.defer="apellido" placeholder="Tus apellidos">
                </div>

                <div class="contact-lux-field full">
                    <label>CORREO ELECTRONICO</label>
                    <input type="email" wire:model.defer="email" placeholder="ejemplo@correo.com">
                </div>

                <div class="contact-lux-field full">
                    <label>ASUNTO</label>
                    <input type="text" wire:model.defer="asunto" placeholder="Reserva de cita">
                </div>

                <div class="contact-lux-field full">
                    <label>MENSAJE</label>
                    <textarea rows="5" wire:model.defer="mensaje" placeholder="Como podemos ayudarte?"></textarea>
                </div>
            </div>

            <button type="submit" class="contact-lux-submit">ENVIAR MENSAJE</button>
        </form>

        <div class="contact-lux-side">
            <div class="contact-lux-cards">
                <article class="contact-info-card">
                    <h4>UBICACIÓN</h4>
                    <p>{!! nl2br(e($footerAddress)) !!}</p>
                </article>

                <article class="contact-info-card">
                    <h4>TELÉFONO</h4>
                    <p>{!! nl2br(e($footerPhone)) !!}</p>
                </article>

                <article class="contact-info-card contact-hours-card">
                    <h4>HORARIOS</h4>
                    <p>{!! nl2br(e($footerHours)) !!}</p>
                </article>


            </div>

            <div class="contact-lux-map">
                <iframe
                    src="https://www.google.com/maps?q=Guadalajara%20Centro&output=embed"
                    loading="lazy">
                </iframe>
            </div>

            <div class="contact-lux-social">
                <span>SIGUENOS EN REDES</span>
                <div class="contact-social-links">
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="Facebook">FB</a>
                    <a href="#" aria-label="Twitter">X</a>
                </div>
            </div>
        </div>
    </div>
</section>
