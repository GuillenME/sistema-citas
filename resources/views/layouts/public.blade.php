<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barber?a & Spa')</title>

    <link rel="stylesheet" href="{{ asset('css/public/layout.css') }}">
    @yield('styles')
    @livewireStyles
</head>

<body>
    @if (!trim($__env->yieldContent('hide_navbar')))
        @include('partials.navbar')
    @endif

    @yield('content')

    <footer class="footer">
        <div class="footer-wrap">

            <div class="footer-brand">
                @if (optional($homeSetting)->navbar_logo)
                    <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo"
                        class="footer-logo-img">
                @else
                    Barbería & Spa
                @endif

                <p class="footer-tag">
                    Excelencia en el cuidado personal.
                </p>
            </div>

            <div class="footer-item">
                <span>📍 UBICACIÓN</span>
                <p>{{ $homeSetting->footer_address ?? 'Calle Principal #123 - Guadalajara' }}</p>

                @if (!empty($homeSetting->footer_references))
                    <p>{{ $homeSetting->footer_references }}</p>
                @endif
            </div>

            <div class="footer-item">
                <span>📞 CONTACTO</span>
                @php
                    $footerPhone = $homeSetting->footer_phone ?? '33 1234 5678';
                    $footerPhoneHref = preg_replace('/[^\d+]+/', '', (string) $footerPhone);
                @endphp

                <p>
                    TELÉFONO:
                    @if (!empty($footerPhoneHref))
                        <a href="tel:{{ $footerPhoneHref }}">{{ $footerPhone }}</a>
                    @else
                        {{ $footerPhone }}
                    @endif
                </p>

                @if (!empty($homeSetting->footer_whatsapp))
                    @php
                        $footerWhatsappDigits = preg_replace('/\D+/', '', (string) $homeSetting->footer_whatsapp);
                        if ($footerWhatsappDigits !== '' && !str_starts_with($footerWhatsappDigits, '52')) {
                            $footerWhatsappDigits = '52' . $footerWhatsappDigits;
                        }
                    @endphp

                    <p>
                        WHATSAPP:
                        @if (!empty($footerWhatsappDigits))
                            <a href="https://wa.me/{{ $footerWhatsappDigits }}" target="_blank">
                                {{ $homeSetting->footer_whatsapp }}
                            </a>
                        @else
                            {{ $homeSetting->footer_whatsapp }}
                        @endif
                    </p>
                @endif
            </div>

            <div class="footer-item">
                <span>🕜 HORARIOS</span>

                <p>
                    {!! nl2br(e($homeSetting->footer_hours ?? 'Lun-Sab 9:00-20:00')) !!}
                </p>
            </div>

        </div>

        <div class="footer-bottom">
            © 2026 Barbería & Spa · <span>Cybac</span>
        </div>
    </footer>

    @yield('scripts')
    @livewireScripts
</body>

</html>
