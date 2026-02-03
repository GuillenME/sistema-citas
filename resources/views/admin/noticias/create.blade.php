@extends('layouts.admin')

@section('title', 'Nueva noticia')

@section('content')
    <div class="card">
        <h1>Nueva noticia</h1>
        <livewire:admin.noticia-create />
    </div>
@endsection
