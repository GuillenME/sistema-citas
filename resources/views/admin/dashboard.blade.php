@extends('layouts.admin')

@section('title', 'Administrador')

@section('content')
<div class="admin-home-wrapper">
<div class="admin-home">
   

    <div class="admin-cards">

        <a href="{{ route('admin.citas.index') }}" class="admin-card">
            <span>📅</span>
            <strong>Gestionar citas</strong>
        </a>

        <a href="{{ route('admin.servicios.index') }}" class="admin-card">
            <span>✂️</span>
            <strong>Gestionar servicios</strong>
        </a>

        <a href="{{ route('admin.promociones.index') }}" class="admin-card">
            <span>🎉</span>
            <strong>Gestionar promociones</strong>
        </a>

        <a href="{{ route('admin.empleados.index') }}" class="admin-card">
            <span>👨‍💼</span>
            <strong>Gestionar empleados</strong>
        </a>

        <a href="{{ route('admin.clientes.index') }}" class="admin-card">
            <span>👥</span>
            <strong>Gestionar clientes</strong>
        </a>

        <a href="{{ route('admin.noticias.index') }}" class="admin-card">
            <span>📰</span>
            <strong>Gestionar noticias</strong>
        </a>

        <a href="{{ route('admin.recepcionistas.index') }}" class="admin-card">
            <span>👩🏽‍💻👨🏽‍💻</span>
            <strong>Gestionar recepcionistas</strong>
        </a>
        <a href="{{ route('admin.home_settings.edit') }}" class="admin-card">
            <span>🏠</span>
            <strong>Gestionar configuración de home</strong>
        </a>

    </div>
</div>
</div>
@endsection
