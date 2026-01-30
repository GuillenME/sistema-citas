@extends('layouts.admin')

@section('title', 'Recepcionistas')

@section('header-actions')
    
@endsection

@section('content')
<a href="{{ route('admin.recepcionistas.create') }}" class="btn-create">
        + Nuevo recepcionista
    </a>
    <h1>Recepcionistas</h1>
    <livewire:admin.recepcionista-index />
@endsection
