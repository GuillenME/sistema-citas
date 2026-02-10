@extends('layouts.admin')

@section('title', 'Nuevo empleado')

@section('back-url', route('admin.empleados.index'))
@section('content')

    <livewire:admin.empleado-create />
@endsection

