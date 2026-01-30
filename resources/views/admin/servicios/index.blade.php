@extends('layouts.admin')

@section('title', 'Servicios')

@section('page-title', 'Servicios')

@section('header-actions')
    <a href="{{ route('admin.servicios.create') }}" class="btn btn-save">
        + Nuevo servicio
    </a>
@endsection

@section('content')
    <livewire:admin.servicios-index />
@endsection
