@extends('layouts.public')

@section('title', 'Promociones | Barbería & Spa')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/promociones/index.css') }}">
@endsection

@section('content')
<section class="promotions-page">
    <div class="promotions-hero">
        <h1 class="promotions-title">
            <span class="title-solid">NUESTRAS</span>
            <span class="title-outline">PROMOCIONES</span>
        </h1>
        <p>Descuentos exclusivos para que luzcas tu mejor version sin comprometer tu bolsillo.</p>
    </div>

    <livewire:public.promociones />
</section>
@endsection
