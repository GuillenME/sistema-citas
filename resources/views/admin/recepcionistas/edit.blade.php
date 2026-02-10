@extends('layouts.admin')

@section('title', 'Editar recepcionista')

@section('back-url', route('admin.recepcionistas.index'))
@section('content')
    <livewire:admin.recepcionista-edit :usuario="$usuario" />
@endsection

