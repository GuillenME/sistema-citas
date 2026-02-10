@extends('layouts.admin')

@section('title', 'Importar servicios')

@section('back-url', route('admin.servicios.index'))
@section('content')
    <div class="card">
        <p>Descarga la plantilla, completa los datos y sube tu archivo CSV.</p>

        <div class="actions" style="margin-bottom:20px;">
            <a href="{{ route('admin.servicios.template') }}" class="btn btn-cancel">
                Descargar plantilla CSV
            </a>
        </div>

        <form method="POST" action="{{ route('admin.servicios.import.store') }}" enctype="multipart/form-data">
            @csrf

            <label>Archivo CSV</label>
            <input type="file" name="file" accept=".csv,.txt" required>
            @error('file')
                <small class="error">{{ $message }}</small>
            @enderror

            <div class="actions" style="margin-top:20px;">
                <a href="{{ route('admin.servicios.index') }}" class="btn btn-cancel">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    Importar
                </button>
            </div>
        </form>
    </div>
@endsection

