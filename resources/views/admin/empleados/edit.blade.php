@extends('layouts.admin')

@section('title', 'Editar empleado')

@section('back-url', route('admin.empleados.index'))
@section('content')

    <livewire:admin.empleado-edit :empleado="$empleado" />
@endsection

