@extends('layouts.admin')

@section('title', 'Editar Home público')

@section('back-url', route('admin.dashboard'))
@section('content')
    <div class="card">
        <h1>Editar Home público</h1>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.home_settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="form-section">
                <h2>Hero</h2>

                <label for="hero_title">Título</label>
                <input type="text" id="hero_title" name="hero_title"
                    value="{{ old('hero_title', $homeSetting->hero_title) }}">

                <label for="hero_subtitle">Subtítulo</label>
                <textarea id="hero_subtitle" name="hero_subtitle" rows="2">{{ old('hero_subtitle', $homeSetting->hero_subtitle) }}</textarea>
            </section>

            <section class="form-section">
                <h2>Imagen de fondo</h2>

                @if ($homeSetting->hero_image)
                    <img src="{{ asset('storage/' . $homeSetting->hero_image) }}" alt="Imagen actual"
                        style="max-width: 260px; display:block; margin-bottom:10px;">
                @endif

                <input type="file" name="hero_image" id="hero_image">
                <small>Formatos: JPG, PNG o WEBP. Máximo 2MB.</small>
            </section>
            <section class="form-section">
                <h2>Logo del navbar</h2>

                @if ($homeSetting->navbar_logo)
                    <img src="{{ asset('storage/' . $homeSetting->navbar_logo) }}" alt="Logo actual"
                        style="max-width: 200px; display:block; margin-bottom:10px;">
                @endif

                <input type="file" name="navbar_logo" id="navbar_logo">
                <small>Formatos: JPG, PNG, SVG o WEBP. Máximo 2MB.</small>
            </section>


            <section class="form-section">
                <h2>Tarjeta 1</h2>
                <label for="feature_1_title">Título</label>
                <input type="text" id="feature_1_title" name="feature_1_title"
                    value="{{ old('feature_1_title', $homeSetting->feature_1_title) }}">
                <label for="feature_1_description">Descripción</label>
                <textarea id="feature_1_description" name="feature_1_description" rows="2">{{ old('feature_1_description', $homeSetting->feature_1_description) }}</textarea>
            </section>

            <section class="form-section">
                <h2>Tarjeta 2</h2>
                <label for="feature_2_title">Título</label>
                <input type="text" id="feature_2_title" name="feature_2_title"
                    value="{{ old('feature_2_title', $homeSetting->feature_2_title) }}">
                <label for="feature_2_description">Descripción</label>
                <textarea id="feature_2_description" name="feature_2_description" rows="2">{{ old('feature_2_description', $homeSetting->feature_2_description) }}</textarea>
            </section>

            <section class="form-section">
                <h2>Tarjeta 3</h2>
                <label for="feature_3_title">Título</label>
                <input type="text" id="feature_3_title" name="feature_3_title"
                    value="{{ old('feature_3_title', $homeSetting->feature_3_title) }}">
                <label for="feature_3_description">Descripción</label>
                <textarea id="feature_3_description" name="feature_3_description" rows="2">{{ old('feature_3_description', $homeSetting->feature_3_description) }}</textarea>
            </section>

            <button type="submit" class="btn">
                Guardar cambios
            </button>
        </form>
    </div>
@endsection

