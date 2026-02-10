@extends('layouts.admin')

@section('title', 'Editar servicio')

@section('back-url', route('admin.servicios.index'))
@section('content')
    <div class="card">
        <livewire:admin.servicio-edit :servicio="$servicio" />
    </div>
@endsection

