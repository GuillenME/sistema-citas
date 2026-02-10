@extends('layouts.admin')

@section('title', 'Servicios')

@section('page-title', 'Servicios')

@section('header-actions')
    <div class="actions-split">
        <div class="actions-left">
            <a href="{{ route('admin.servicios.create') }}" class="btn btn-save">
                + Nuevo servicio
            </a>
        </div>
        <div class="actions-right">
            <a href="{{ route('admin.servicios.template') }}" class="btn btn-cancel csv-btn">
                Descargar plantilla CSV
            </a>
            <a href="{{ route('admin.servicios.import') }}" class="btn btn-edit csv-btn">
                Importar CSV
            </a>
        </div>
    </div>
@endsection

@section('content')
    <livewire:admin.servicios-index />
@endsection
