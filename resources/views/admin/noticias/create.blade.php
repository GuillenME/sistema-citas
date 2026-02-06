@extends('layouts.admin')

@section('title', 'Nueva noticia')

@section('back-url', route('admin.noticias.index'))
@section('content')
    <div class="card">
        <h1>Nueva noticia</h1>
        <livewire:admin.noticia-create />
    </div>
@endsection

