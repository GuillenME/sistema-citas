@extends('layouts.admin')

@section('title', 'Empleados')

@section('header-actions')
    
@endsection

@section('content')
<a href="{{ route('admin.empleados.create') }}" class="btn-create">
        + Nuevo empleado
    </a>
<h1>Empleados</h1>
    <livewire:admin.empleado-index />
@endsection
