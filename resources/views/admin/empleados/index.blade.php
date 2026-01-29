@extends('layouts.admin')

@section('title', 'Empleados')

@section('header-actions')
    <a href="{{ route('admin.empleados.create') }}" class="btn-create">
        + Nuevo empleado
    </a>
@endsection

@section('content')
    <livewire:admin.empleado-index />
@endsection
