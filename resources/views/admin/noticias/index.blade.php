@extends('layouts.admin')

@section('title', 'Noticias')

@section('header-actions')
    <a href="{{ route('admin.noticias.create') }}" class="btn btn-save">
        + Nueva noticia
    </a>
@endsection
@section('content')
    <livewire:admin.noticia-index />
@endsection
