@extends('layouts.admin')

@section('title', 'Editar promocion')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.promociones.index'))
@section('content')
    <div class="admin-form-shell">
        <livewire:admin.promocion-edit :promocion="$promocion" />
    </div>
@endsection
