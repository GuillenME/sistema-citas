@extends('layouts.admin')

@section('title', 'Editar noticia')

@section('content')
    <div class="card">
        <h1>Editar noticia</h1>
        <livewire:admin.noticia-edit :noticia="$noticia" />
    </div>
@endsection
