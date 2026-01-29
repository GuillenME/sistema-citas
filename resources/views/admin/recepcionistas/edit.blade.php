@extends('layouts.admin')

@section('title', 'Editar recepcionista')

@section('content')
    <livewire:admin.recepcionista-edit :usuario="$usuario" />
@endsection
