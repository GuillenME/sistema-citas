@extends('layouts.admin')

@section('title', 'Editar promoción')

@section('back-url', route('admin.promociones.index'))
@section('content')
<h1>Editar promoción</h1>
    <livewire:admin.promocion-edit :promocion="$promocion" />
@endsection

