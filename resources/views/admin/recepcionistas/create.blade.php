@extends('layouts.admin')

@section('title', 'Nuevo recepcionista')

@section('back-url', route('admin.recepcionistas.index'))
@section('content')
<h1>Nuevo recepcionista</h1>
    <livewire:admin.recepcionista-create />
@endsection

