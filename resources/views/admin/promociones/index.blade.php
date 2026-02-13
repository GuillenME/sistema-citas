@extends('layouts.admin')

@section('title', 'Promociones')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/servicios-index.css') }}">
@endsection

@section('content')
    <livewire:admin.promocion-index />
@endsection
