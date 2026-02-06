@extends('layouts.admin')

@section('title', 'Crear promoción')

@section('back-url', route('admin.promociones.index'))
@section('content')
<h1>Crear promoción</h1>
    <livewire:admin.promocion-create />
@endsection

