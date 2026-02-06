@extends('layouts.admin')

@section('title', 'Editar noticia')

@section('back-url', route('admin.noticias.index'))
@section('content')
    <div class="card">
        <h1>Editar noticia</h1>
        <livewire:admin.noticia-edit :noticia="$noticia" />
    </div>
@endsection

