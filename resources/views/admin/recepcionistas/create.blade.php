@extends('layouts.admin')

@section('title', 'Nuevo recepcionista')

@section('back-url', route('admin.recepcionistas.index'))
@section('content')
    <livewire:admin.recepcionista-create />
@endsection

