@extends('layouts.admin')

@section('title', 'Clientes')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/servicios-index.css') }}">
@endsection

@section('content')
    <livewire:admin.clientes-index />
@endsection
