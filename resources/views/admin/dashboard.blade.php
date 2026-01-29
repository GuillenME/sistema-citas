@extends('layouts.admin')

@section('title', 'Panel del Administrador')

@section('content')
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

        <a href="{{ route('admin.recepcionistas.index') }}" class="admin-card">
            <span>👩🏽‍💻👨🏽‍💻</span>
            <strong>Gestionar recepcionistas</strong>
        </a>

    </div>

</div>
@endsection
