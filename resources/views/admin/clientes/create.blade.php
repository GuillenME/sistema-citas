@extends('layouts.admin')

@section('title', 'Nuevo cliente')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form-theme.css') }}">
@endsection

@section('back-url', route('admin.clientes.index'))
@section('content')
    <div class="admin-form-shell">
        <div class="form-container">
            <form method="POST" action="{{ route('admin.clientes.store') }}" class="form-container">
                @csrf

                <div class="form-group full">
                    <label>Acceso del cliente</label>
                    <input type="text" value="Se enviara un correo de bienvenida para que el cliente defina su contraseña." readonly>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" autocomplete="given-name"
                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\\s]/g,'')">
                        @error('nombre') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" autocomplete="family-name"
                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\\s]/g,'')">
                        @error('apellido') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input id="telefono" type="tel" name="telefono" value="{{ old('telefono') }}" inputmode="numeric"
                            maxlength="10" autocomplete="tel" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                        @error('telefono') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Correo electronico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email">
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('admin.clientes.index') }}" class="btn btn-cancel">Cancelar</a>
                    <button type="submit" class="btn btn-save">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
