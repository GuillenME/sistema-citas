@extends('layouts.admin')

@section('title', 'Editar empleado')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.empleados.index'))
@section('content')
    <div class="admin-form-shell">
        <livewire:admin.empleado-edit :empleado="$empleado" />
    </div>
@endsection

