@extends('layouts.admin')

@section('title', 'Promociones')

@section('header-actions')
    
@endsection

@section('content')
<a href="{{ route('admin.promociones.create') }}" class="btn-create">
        + Nueva promoción
    </a>
<h1>Promociones</h1>
    <livewire:admin.promocion-index />
@endsection
