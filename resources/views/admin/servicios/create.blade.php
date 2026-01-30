@extends('layouts.admin')

@section('title', 'Nuevo servicio')

@section('content')
    <div class="card">
        <h1>Nuevo servicio</h1>
        <livewire:admin.servicio-create />
    </div>
@endsection
