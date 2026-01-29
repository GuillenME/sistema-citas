@extends('layouts.admin')

@section('title', 'Recepcionistas')

@section('header-actions')
    <a href="{{ route('admin.recepcionistas.create') }}" class="btn-create">
        + Nuevo recepcionista
    </a>
@endsection

@section('content')
    <livewire:admin.recepcionista-index />
@endsection
