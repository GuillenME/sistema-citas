@extends('layouts.admin')

@section('title', 'Editar servicio')

@section('back-url', route('admin.servicios.index'))
@section('content')
    <div class="card">
        <h1>Editar servicio</h1>
        <livewire:admin.servicio-edit :servicio="$servicio" />
    </div>
@endsection

