@extends('layouts.admin')

@section('title', 'Promociones')

@section('header-actions')
    <a href="{{ route('admin.promociones.create') }}" class="btn-create">
        + Nueva promoción
    </a>
@endsection

@section('content')
    <livewire:admin.promocion-index />
@endsection
