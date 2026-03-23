@extends('layouts.admin')

@section('title', 'Configuración de inicio')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/home-edit.css') }}">
@endsection

@section('back-url', route('admin.dashboard'))
@section('content')
    <div class="home-editor-shell">
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.home_settings.update') }}" enctype="multipart/form-data" class="home-editor-form">
            @csrf
            @method('PUT')

            <header class="editor-topbar">
                <div>
                    <p class="editor-kicker">Panel de configuracion</p>
                    <p class="editor-meta"><span class="dot"></span> Ajusta contenido, imagenes y servicios destacados</p>
                </div>
                <div class="editor-actions">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </header>

            <section class="editor-block">
                <h3>Configuracion de inicio</h3>
                <p class="block-lead">Personaliza el titulo principal y el mensaje de bienvenida.</p>
                <div class="form-grid two">
                    <div>
                        <label for="hero_title">Título principal</label>
                        <input type="text" id="hero_title" name="hero_title" value="{{ old('hero_title', $homeSetting->hero_title) }}">
                    </div>
                    <div>
                        <label for="hero_subtitle">Subtitulo / Tagline</label>
                        <textarea id="hero_subtitle" name="hero_subtitle" rows="2" class="auto-grow">{{ old('hero_subtitle', $homeSetting->hero_subtitle) }}</textarea>
                    </div>
                </div>
                <div class="form-grid">
                    <div>
                        <label for="register_subtitle">Texto del registro</label>
                        <textarea id="register_subtitle" name="register_subtitle" rows="2" class="auto-grow">{{ old('register_subtitle', $homeSetting->register_subtitle) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="editor-block">
                <h3>Gestión de multimedia</h3>
                <div class="media-grid">
                    <article class="upload-card">
                        <div class="upload-card-head">
                            <label for="hero_image">Imagen de fondo Hero</label>
                            <span class="badge">JPG/PNG/WEBP - 2MB</span>
                        </div>

                        @if ($homeSetting->hero_image)
                            <img src="{{ asset('storage/' . $homeSetting->hero_image) }}" alt="Imagen actual"
                                class="home-image-preview home-image-preview-hero" id="hero_image_preview">
                        @else
                            <img src="" alt="Vista previa imagen hero"
                                class="home-image-preview home-image-preview-hero hidden" id="hero_image_preview">
                        @endif

                        <input type="file" name="hero_image" id="hero_image" accept="image/*">
                        <small>Cambia la imagen o arrastra un archivo.</small>
                    </article>

                    <article class="upload-card">
                        <div class="upload-card-head">
                            <label for="navbar_logo">Logo de la marca</label>
                            <span class="badge">PNG/SVG/WEBP - 2MB</span>
                        </div>

                        @if ($homeSetting->navbar_logo)
                            <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo actual"
                                class="home-image-preview home-image-preview-logo" id="navbar_logo_preview">
                        @else
                            <img src="" alt="Vista previa logo"
                                class="home-image-preview home-image-preview-logo hidden" id="navbar_logo_preview">
                        @endif

                        <input type="file" name="navbar_logo" id="navbar_logo" accept="image/*">
                        <small>Sube logo con fondo transparente para mejor resultado.</small>
                    </article>
                </div>
            </section>
             <section class="editor-block">
                <h3>Tarjetas destacadas</h3>
                <div class="form-grid three">
                    <div class="mini-card">
                        <h4>Tarjeta 1</h4>
                        <label for="feature_1_title">Título</label>
                        <input type="text" id="feature_1_title" name="feature_1_title" value="{{ old('feature_1_title', $homeSetting->feature_1_title) }}">
                        <label for="feature_1_description">Descripción</label>
                        <textarea id="feature_1_description" name="feature_1_description" rows="2" class="auto-grow">{{ old('feature_1_description', $homeSetting->feature_1_description) }}</textarea>
                    </div>

                    <div class="mini-card">
                        <h4>Tarjeta 2</h4>
                        <label for="feature_2_title">Título</label>
                        <input type="text" id="feature_2_title" name="feature_2_title" value="{{ old('feature_2_title', $homeSetting->feature_2_title) }}">
                        <label for="feature_2_description">Descripción</label>
                        <textarea id="feature_2_description" name="feature_2_description" rows="2" class="auto-grow">{{ old('feature_2_description', $homeSetting->feature_2_description) }}</textarea>
                    </div>

                    <div class="mini-card">
                        <h4>Tarjeta 3</h4>
                        <label for="feature_3_title">Título</label>
                        <input type="text" id="feature_3_title" name="feature_3_title" value="{{ old('feature_3_title', $homeSetting->feature_3_title) }}">
                        <label for="feature_3_description">Descripción</label>
                        <textarea id="feature_3_description" name="feature_3_description" rows="2" class="auto-grow">{{ old('feature_3_description', $homeSetting->feature_3_description) }}</textarea>
                    </div>
                </div>
            </section>
            <section class="editor-block">
                <h3>Listado de Servicios</h3>
                @php
                    $selectedHomeServices = old(
                        'featured_services',
                        $serviciosActivos->where('featured_on_home', true)->pluck('id')->toArray()
                    );
                @endphp

                @error('featured_services')
                    <p class="error">{{ $message }}</p>
                @enderror
                @error('featured_services.*')
                    <p class="error">{{ $message }}</p>
                @enderror

                <div class="services-picker-head">
                    <label>Servicios destacados en inicio</label>
                    <span class="services-picker-counter" id="featured_services_counter">0/10 seleccionados</span>
                </div>

                <div class="services-picker-scroll-card">
                    <div class="services-picker-scroll-body">
                        <div class="services-picker-grid" id="featured_services_grid" data-max="10">
                            @foreach ($serviciosActivos as $servicio)
                                @php
                                    $isSelected = in_array($servicio->id, $selectedHomeServices);
                                @endphp
                                <button
                                    type="button"
                                    class="service-pick-card {{ $isSelected ? 'is-selected' : '' }}"
                                    data-service-id="{{ $servicio->id }}"
                                    aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
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

                <div id="featured_services_inputs">
                    @foreach ($selectedHomeServices as $serviceId)
                        <input type="hidden" name="featured_services[]" value="{{ $serviceId }}">
                    @endforeach
                </div>

                <small>Selecciona hasta 10. Al llegar al límite, los demás servicios se desactivan.</small>
            </section>


            <section class="editor-block">
                <h3>Información de contacto</h3>
                @php
                    $footerAddressValue = old('footer_address', $homeSetting->footer_address);
                    $footerAddressQuery = rawurlencode(trim((string) $footerAddressValue) !== '' ? $footerAddressValue : 'Guadalajara Centro');
                @endphp

                <div class="contact-info-row contact-info-row-top">
                    <div class="contact-info-item">
                        <label for="footer_address">Dirección</label>
                        <textarea id="footer_address" name="footer_address" rows="2" class="auto-grow fixed-height-control">{{ old('footer_address', $homeSetting->footer_address) }}</textarea>
                        <small class="field-note">Usa la direccion exacta de Google Maps (sin referencias) para que el mapa se ubique correctamente.</small>
                    </div>
                    <div class="contact-info-item">
                        <label for="footer_references">Referencias</label>
                        <textarea id="footer_references" name="footer_references" rows="2" class="auto-grow fixed-height-control">{{ old('footer_references', $homeSetting->footer_references) }}</textarea>
                    </div>
                    <div class="contact-info-item">
<<<<<<< HEAD
                        <label for="footer_phone">Teléfono de contacto</label>
                        <textarea id="footer_phone" name="footer_phone" rows="2" class="auto-grow fixed-height-control">{{ old('footer_phone', $homeSetting->footer_phone) }}</textarea>
=======
                        <label for="footer_phone">Telefono de contacto</label>
                        <textarea id="footer_phone" name="footer_phone" rows="2" class="auto-grow fixed-height-control" inputmode="numeric">{{ old('footer_phone', $homeSetting->footer_phone) }}</textarea>
>>>>>>> 07f4d73cb1073a8ec358cef8101849d0370a5ff7
                    </div>
                    <div class="contact-info-item">
                        <label for="footer_whatsapp">WhatsApp</label>
                        <textarea id="footer_whatsapp" name="footer_whatsapp" rows="2" class="auto-grow fixed-height-control" inputmode="numeric">{{ old('footer_whatsapp', $homeSetting->footer_whatsapp) }}</textarea>
                        <small class="field-note">Se guardan solo numeros, sin espacios ni prefijos automaticos.</small>
                    </div>
                </div>

                <div class="contact-info-row contact-info-row-bottom">
                    <div class="contact-info-item map-preview-card">
                        <span>Vista previa del mapa</span>
                        <iframe
                            id="footer_address_map_preview"
                            src="https://www.google.com/maps?q={{ $footerAddressQuery }}&output=embed"
                            loading="lazy">
                        </iframe>
                    </div>
                    <div class="contact-info-item">
                        <label for="footer_hours">Horario de atencion</label>
                        <textarea id="footer_hours" name="footer_hours" rows="2" class="auto-grow">{{ old('footer_hours', $homeSetting->footer_hours) }}</textarea>
                    </div>
                </div>
            </section>


            <div class="actions">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    (function () {
        function initHomeEditor() {
            var fields = document.querySelectorAll('.auto-grow:not(.fixed-height-control)');
            function adjust(el) {
                el.style.height = 'auto';
                el.style.height = el.scrollHeight + 'px';
            }
            fields.forEach(function (el) {
                adjust(el);
                el.addEventListener('input', function () { adjust(el); });
            });

            function bindImagePreview(inputId, previewId) {
                var input = document.getElementById(inputId);
                var preview = document.getElementById(previewId);

                if (!input || !preview) return;
                if (input.dataset.previewBound === '1') return;
                input.dataset.previewBound = '1';

                input.addEventListener('change', function (event) {
                    var file = event.target.files && event.target.files[0];
                    if (!file) return;

                    var objectUrl = URL.createObjectURL(file);
                    preview.src = objectUrl;
                    preview.classList.remove('hidden');
                    preview.onload = function () {
                        URL.revokeObjectURL(objectUrl);
                    };
                });
            }

            bindImagePreview('hero_image', 'hero_image_preview');
            bindImagePreview('navbar_logo', 'navbar_logo_preview');

            var addressInput = document.getElementById('footer_address');
            var addressMapPreview = document.getElementById('footer_address_map_preview');
            var phoneInput = document.getElementById('footer_phone');
            var whatsappInput = document.getElementById('footer_whatsapp');

            function updateAddressMapPreview() {
                if (!addressInput || !addressMapPreview) return;
                var value = (addressInput.value || '').trim();
                var query = encodeURIComponent(value !== '' ? value : 'Guadalajara Centro');
                addressMapPreview.src = 'https://www.google.com/maps?q=' + query + '&output=embed';
            }

            if (addressInput && addressMapPreview && addressInput.dataset.mapPreviewBound !== '1') {
                addressInput.dataset.mapPreviewBound = '1';
                addressInput.addEventListener('input', updateAddressMapPreview);
                addressInput.addEventListener('change', updateAddressMapPreview);
            }

            function normalizePhoneValue(rawValue) {
                return String(rawValue || '').replace(/\D+/g, '');
            }

            function bindPhoneNormalizer(input) {
                if (!input || input.dataset.normalizeBound === '1') return;
                input.dataset.normalizeBound = '1';

                function syncValue() {
                    input.value = normalizePhoneValue(input.value);
                }

                input.addEventListener('input', syncValue);
                input.addEventListener('blur', syncValue);
                input.addEventListener('change', syncValue);
                syncValue();
            }

            bindPhoneNormalizer(phoneInput);
            bindPhoneNormalizer(whatsappInput);

            var grid = document.getElementById('featured_services_grid');
            var counter = document.getElementById('featured_services_counter');
            var hiddenInputs = document.getElementById('featured_services_inputs');

            if (!grid || !counter || !hiddenInputs) return;
            if (grid.dataset.bound === '1') return;
            grid.dataset.bound = '1';

            var max = Number(grid.dataset.max || '10');
            var cards = Array.prototype.slice.call(grid.querySelectorAll('.service-pick-card'));

            function selectedCards() {
                return cards.filter(function (card) {
                    return card.classList.contains('is-selected');
                });
            }

            function syncHiddenInputs() {
                var selected = selectedCards();
                hiddenInputs.innerHTML = '';

                selected.forEach(function (card) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'featured_services[]';
                    input.value = card.dataset.serviceId;
                    hiddenInputs.appendChild(input);
                });
            }

            function updateCounterAndState() {
                var selected = selectedCards();
                var selectedCount = selected.length;
                var reachedLimit = selectedCount >= max;

                counter.textContent = selectedCount + '/' + max + ' seleccionados';
                counter.classList.toggle('is-limit', reachedLimit);

                cards.forEach(function (card) {
                    var isSelected = card.classList.contains('is-selected');
                    card.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
                    card.disabled = !isSelected && reachedLimit;
                    card.classList.toggle('is-disabled', !isSelected && reachedLimit);
                });
            }

            cards.forEach(function (card) {
                card.addEventListener('click', function () {
                    var currentlySelected = card.classList.contains('is-selected');
                    var selectedCount = selectedCards().length;

                    if (!currentlySelected && selectedCount >= max) {
                        return;
                    }

                    card.classList.toggle('is-selected');
                    syncHiddenInputs();
                    updateCounterAndState();
                });
            });

            syncHiddenInputs();
            updateCounterAndState();
        }

        document.addEventListener('DOMContentLoaded', initHomeEditor, { once: true });
        document.addEventListener('livewire:navigated', initHomeEditor);
    })();
</script>
@endsection
