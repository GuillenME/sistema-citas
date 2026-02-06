@extends('layouts.admin')

@section('title', 'Editar recepcionista')

@section('back-url', route('admin.recepcionistas.index'))
@section('content')
<h1>Editar recepcionista</h1>
    <livewire:admin.recepcionista-edit :usuario="$usuario" />
@endsection

