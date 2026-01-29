@extends('layouts.admin')

@section('title', 'Editar servicio')

@section('content')
    <div class="card">
        <livewire:admin.servicio-edit :servicio="$servicio" />
    </div>
@endsection
