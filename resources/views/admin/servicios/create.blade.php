@extends('layouts.admin')

@section('title', 'Nuevo servicio')

@section('back-url', route('admin.servicios.index'))
@section('content')
    <div class="card">
        <livewire:admin.servicio-create />
    </div>
@endsection

