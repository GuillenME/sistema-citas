@extends('layouts.admin')

@section('title', 'Administrador')

@section('content')
<div class="admin-home-wrapper">
    <div class="admin-home">
        <section class="admin-home-intro">
            <p class="admin-eyebrow">Panel de administracion</p>
            <h2 class="admin-home-title">Control central del sistema de citas</h2>
            <p class="admin-home-lead">
                Administra operaciones clave desde un solo lugar: agenda, servicios, equipo, clientes y contenido.
            </p>
        </section>

        <div class="admin-cards">
            <a href="{{ route('admin.citas.index') }}" class="admin-card">
                <span class="admin-card-icon">&#128197;</span>
                <strong>Gestionar citas</strong>
                <small>Revisa, confirma o cancela citas del sistema.</small>
            </a>

            <a href="{{ route('admin.servicios.index') }}" class="admin-card">
                <span class="admin-card-icon">&#9986;</span>
                <strong>Gestionar servicios</strong>
                <small>Actualiza catalogo, precios y disponibilidad.</small>
            </a>

            <a href="{{ route('admin.promociones.index') }}" class="admin-card">
                <span class="admin-card-icon">&#127881;</span>
                <strong>Gestionar promociones</strong>
                <small>Crea campanas y controla periodos de vigencia.</small>
            </a>

            <a href="{{ route('admin.empleados.index') }}" class="admin-card">
                <span class="admin-card-icon">&#128188;</span>
                <strong>Gestionar empleados</strong>
                <small>Administra datos y estado del personal.</small>
            </a>

            <a href="{{ route('admin.clientes.index') }}" class="admin-card">
                <span class="admin-card-icon">&#128101;</span>
                <strong>Gestionar clientes</strong>
                <small>Consulta perfiles e historial de cada cliente.</small>
            </a>

            <a href="{{ route('admin.noticias.index') }}" class="admin-card">
                <span class="admin-card-icon">&#128240;</span>
                <strong>Gestionar noticias</strong>
                <small>Publica anuncios para mantener informados.</small>
            </a>

            <a href="{{ route('admin.recepcionistas.index') }}" class="admin-card">
                <span class="admin-card-icon">&#128187;</span>
                <strong>Gestionar recepcionistas</strong>
                <small>Configura accesos y control operativo diario.</small>
            </a>

            <a href="{{ route('admin.home_settings.edit') }}" class="admin-card">
                <span class="admin-card-icon">&#127968;</span>
                <strong>Configurar home</strong>
                <small>Edita contenido visual y secciones del inicio.</small>
            </a>
        </div>
    </div>
</div>
@endsection
