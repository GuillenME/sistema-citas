@extends('layouts.admin')

@section('title', 'Administrador')

@section('content')
    <div class="dashboard-home-shell">
        <header class="dh-topbar">
            <div class="dh-topbar-left">
                <h1>Control Central</h1>
                <span class="dh-badge">Modo Administrador</span>
            </div>
            <div class="dh-topbar-actions">
                <a href="{{ route('admin.citas.reporte-diario.pdf') }}" class="dh-btn dh-btn-ghost">Reporte diario</a>
                <a href="{{ route('admin.citas.reporte-mensual') }}" class="dh-btn dh-btn-ghost">Reporte mensual</a>
                {{-- <a href="{{ route('admin.citas.create') }}" class="dh-btn dh-btn-primary">Nueva cita</a> --}}
            </div>
        </header>

        <section class="dh-hero">
            <h2>Hola de nuevo.</h2>
            <p>Aqui tienes un resumen rapido para operar agenda, servicios, equipo y contenido.</p>
        </section>

        <section class="dh-main-grid">
            <a href="{{ route('admin.citas.index') }}" class="dh-primary-card">
                <div class="dh-card-head">
                    <span class="dh-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 14v2.2l1.6 1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v.832" />
                            <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2" />
                            <circle cx="16" cy="16" r="6" />
                            <rect x="8" y="2" width="8" height="4" rx="1" />
                        </svg>
                    </span>
                    <span class="dh-chip">{{ $citasHoy ?? 0 }} citas hoy</span>
                </div>
                <h3>Gestionar Citas</h3>
                <p>Administra la agenda del dia, confirma solicitudes y reasigna turnos a tu staff.</p>
                <div class="dh-card-foot">
                    <span class="dh-btn dh-btn-primary">Ver calendario</span>
                </div>
            </a>

            <a href="{{ route('admin.home_settings.edit') }}" class="dh-side-card">
                <div class="dh-card-head">
                    <span class="dh-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 17H5" />
                            <path d="M19 7h-9" />
                            <circle cx="17" cy="17" r="3" />
                            <circle cx="7" cy="7" r="3" />
                        </svg>
                    </span>
                </div>
                <h3>Configurar Vista Principal</h3>
                <p>Personaliza la experiencia de clientes en la app movil y el sitio publico.</p>
                <span class="dh-btn dh-btn-ghost">Personalizar diseño</span>
            </a>
        </section>

        <section class="dh-modules-grid">
            <a href="{{ route('admin.servicios.index') }}" class="dh-module">
                <span class="dh-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3" />
                        <path d="M8.12 8.12 12 12" />
                        <path d="M20 4 8.12 15.88" />
                        <circle cx="6" cy="18" r="3" />
                        <path d="M14.8 14.8 20 20" />
                    </svg>
                </span>
                <h4>Servicios</h4>
                <p>Catalogo y precios</p>
            </a>
            <a href="{{ route('admin.promociones.index') }}" class="dh-module">
                <span class="dh-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z" />
                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor" />
                    </svg>
                </span>
                <h4>Promociones</h4>
                <p>Cupones y ofertas</p>
            </a>
            <a href="{{ route('admin.empleados.index') }}" class="dh-module">
                <span class="dh-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 10h2" />
                        <path d="M16 14h2" />
                        <path d="M6.17 15a3 3 0 0 1 5.66 0" />
                        <circle cx="9" cy="11" r="2" />
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                    </svg>
                </span>
                <h4>Empleados</h4>
                <p>Staff y horarios</p>
            </a>
            <a href="{{ route('admin.clientes.index') }}" class="dh-module">
                <span class="dh-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                </span>
                <h4>Clientes</h4>
                <p>Base de datos</p>
            </a>
            <a href="{{ route('admin.noticias.index') }}" class="dh-module">
                <span class="dh-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18h-5" />
                        <path d="M18 14h-8" />
                        <path
                            d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2" />
                        <rect width="8" height="4" x="10" y="6" rx="1" />
                    </svg>
                </span>
                <h4>Noticias</h4>
                <p>Blog y avisos</p>
            </a>
            <a href="{{ route('admin.recepcionistas.index') }}" class="dh-module">
                <span class="dh-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z" />
                        <path d="M20 16a8 8 0 1 0-16 0" />
                        <path d="M12 4v4" />
                        <path d="M10 4h4" />
                    </svg>
                </span>
                <h4>Recepcionistas</h4>
                <p>Perfiles y turnos</p>
            </a>
        </section>
    </div>
@endsection
