@extends('layouts.admin')

@section('title', 'Servicios')

@section('header-actions')
    
@endsection

@section('content')
<a href="{{ route('admin.servicios.create') }}" class="btn btn-save">
        + Nuevo servicio
    </a>
    <h1>Servicios</h1>
    <livewire:admin.servicios-index />
@endsection
