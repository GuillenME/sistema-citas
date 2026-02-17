@extends('layouts.admin')

@section('title', 'Editar recepcionista')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.recepcionistas.index'))
@section('content')
    <div class="admin-form-shell">
        <livewire:admin.recepcionista-edit :usuario="$usuario" />
    </div>
@endsection
