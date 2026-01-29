@extends('layouts.admin')

@section('title', 'Editar promoción')

@section('content')
    <livewire:admin.promocion-edit :promocion="$promocion" />
@endsection
