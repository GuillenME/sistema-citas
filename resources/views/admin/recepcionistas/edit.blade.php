@extends('layouts.admin')

@section('title', 'Editar recepcionista')

@section('content')
<h1>Editar recepcionista</h1>
    <livewire:admin.recepcionista-edit :usuario="$usuario" />
@endsection
