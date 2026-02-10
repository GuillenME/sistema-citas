@extends('layouts.admin')

@section('title', 'Nueva noticia')

@section('back-url', route('admin.noticias.index'))
@section('content')
    <div class="card">
        <livewire:admin.noticia-create />
    </div>
@endsection

