@extends('layouts.admin')

@section('title', 'Crear promocion')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.promociones.index'))
@section('content')
    <div class="admin-form-shell">
        <livewire:admin.promocion-create />
    </div>
@endsection
