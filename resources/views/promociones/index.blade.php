@extends('layouts.public')

@section('title', 'Promociones | Barbería & Spa')

@section('styles')
<style>
* { box-sizing: border-box; }

html{
    scroll-behavior: smooth;
}

body{
    margin: 0;
    font-family: Arial, sans-serif;
    background: #b28562;
    color: #e5e7eb;
}

section{
    max-width: 1200px;
    margin: auto;
    padding: 90px 20px 60px;
}

h1{
    text-align: center;
    margin-bottom: 40px;
    text-shadow: 0 0 15px #fccc7c;
}
</style>
@endsection

@section('content')
<section>
    <h1>Promociones</h1>

    {{-- MISMAS PROMOS DEL INDEX --}}
    <livewire:public.promociones />
</section>
@endsection
