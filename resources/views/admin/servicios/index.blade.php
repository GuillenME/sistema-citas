@extends('layouts.admin')

@section('title', 'Servicios')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/servicios-index.css') }}">
@endsection

@section('content')
    <livewire:admin.servicios-index />
@endsection
