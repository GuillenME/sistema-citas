@extends('layouts.admin')

@section('title', 'Editar servicio')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.servicios.index'))
@section('content')
    <div class="admin-form-shell">
        <livewire:admin.servicio-edit :servicio="$servicio" />
    </div>
@endsection
