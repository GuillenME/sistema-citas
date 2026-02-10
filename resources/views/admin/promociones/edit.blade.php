@extends('layouts.admin')

@section('title', 'Editar promoción')

@section('back-url', route('admin.promociones.index'))
@section('content')
    <div class="card">
        <livewire:admin.promocion-edit :promocion="$promocion" />
    </div>
@endsection


