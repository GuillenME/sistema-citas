@extends('layouts.admin')

@section('title', 'Empleados')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/servicios-index.css') }}">
@endsection

@section('content')
    <livewire:admin.empleado-index />
@endsection
