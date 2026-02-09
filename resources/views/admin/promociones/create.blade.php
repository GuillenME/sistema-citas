@extends('layouts.admin')

@section('title', 'Crear promoción')

@section('back-url', route('admin.promociones.index'))
@section('content')
    <div class="card">
        <livewire:admin.promocion-create />
    </div>
@endsection


