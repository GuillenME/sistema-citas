@extends('layouts.admin')

@section('title', 'Noticias')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/servicios-index.css') }}">
@endsection
@section('content')
    <livewire:admin.noticia-index />
@endsection
