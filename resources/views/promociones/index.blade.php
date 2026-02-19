@extends('layouts.public')

@section('title', 'Promociones | Barbería & Spa')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/promociones/index.css') }}">
@endsection

@section('content')
<section>
    <h1>Promociones</h1>

    {{-- MISMAS PROMOS DEL INDEX --}}
    <livewire:public.promociones />
</section>
@endsection
