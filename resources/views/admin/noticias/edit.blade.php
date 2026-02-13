@extends('layouts.admin')

@section('title', 'Editar noticia')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.noticias.index'))
@section('content')
    <div class="admin-form-shell">
        <livewire:admin.noticia-edit :noticia="$noticia" />
    </div>
@endsection
