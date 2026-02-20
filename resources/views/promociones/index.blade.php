@extends('layouts.public')

@section('title', 'Promociones | Barbería & Spa')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/promociones/index.css') }}">
@endsection

@section('content')
<section class="promotions-page">
    <div class="promotions-hero">
        <h1>Nuestras Promociones</h1>
        <p>Descuentos exclusivos para que luzcas tu mejor version sin comprometer tu bolsillo.</p>
    </div>

    <livewire:public.promociones />
</section>
@endsection
