@extends('layouts.admin')

@section('title', 'Terminos y privacidad')
@section('page-subtitle', 'Edita el contenido legal que veran los clientes en las paginas publicas.')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/legal-edit.css') }}">
@endsection

@section('back-url', route('admin.dashboard'))
@section('content')
    <div class="legal-editor-shell">
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.legal.update') }}" class="legal-editor-form">
            @csrf
            @method('PUT')

            <header class="legal-editor-topbar">
                <div>
                    <p class="legal-editor-kicker">Contenido legal</p>
                    <p class="legal-editor-meta">Solo el administrador puede actualizar estos textos.</p>
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </header>

            <section class="legal-editor-grid">
                <article class="legal-editor-card">
                    <div class="legal-editor-card-head">
                        <div>
                            <h3>Terminos y condiciones</h3>
                            <p>Este texto se muestra en la ruta publica `/terminos`.</p>
                        </div>
                        <span class="legal-editor-badge">
                            Actualizado:
                            {{ optional($homeSetting->terms_updated_at)->format('d/m/Y H:i') ?? 'Sin fecha' }}
                        </span>
                    </div>

                    <label for="terms_content">Contenido</label>
                    <textarea id="terms_content" name="terms_content" rows="18">{{ old('terms_content', $homeSetting->terms_content) }}</textarea>
                </article>

                <article class="legal-editor-card">
                    <div class="legal-editor-card-head">
                        <div>
                            <h3>Politica de privacidad</h3>
                            <p>Este texto se muestra en la ruta publica `/privacidad`.</p>
                        </div>
                        <span class="legal-editor-badge">
                            Actualizado:
                            {{ optional($homeSetting->privacy_policy_updated_at)->format('d/m/Y H:i') ?? 'Sin fecha' }}
                        </span>
                    </div>

                    <label for="privacy_policy_content">Contenido</label>
                    <textarea id="privacy_policy_content" name="privacy_policy_content" rows="18">{{ old('privacy_policy_content', $homeSetting->privacy_policy_content) }}</textarea>
                </article>
            </section>

            <div class="legal-editor-actions">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection
