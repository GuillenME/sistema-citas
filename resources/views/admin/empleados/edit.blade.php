@extends('layouts.admin')

@section('title', 'Editar empleado')

@section('content')
    <livewire:admin.empleado-edit :empleado="$empleado" />
@endsection
