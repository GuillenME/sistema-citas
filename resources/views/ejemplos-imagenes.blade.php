<?php
/**
 * Ejemplos de cómo mostrar imágenes en vistas públicas
 * Copia y adapta estos ejemplos a tus vistas
 */
?>
@once
    <link rel="stylesheet" href="{{ asset('css/ejemplos-imagenes.css') }}">
@endonce
<!-- EJEMPLO 1: Mostrar imagen de un servicio en tarjeta -->
<div class="card">
    @if($servicio->image)
        <img src="{{ asset('storage/' . $servicio->image) }}" 
             class="card-img-top ejemplo-img-cover-250" 
             alt="{{ $servicio->name }}"
             >
    @else
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center ejemplo-h-250" 
             >
            <i class="fas fa-image text-muted ejemplo-icon-3"></i>
        </div>
    @endif
    <div class="card-body">
        <h5 class="card-title">{{ $servicio->name }}</h5>
        <p class="card-text">{{ $servicio->description }}</p>
        <p class="card-text"><strong>${{ number_format($servicio->price, 2) }}</strong></p>
    </div>
</div>

<!-- EJEMPLO 2: Mostrar imagen de promoción en sección destacada -->
<div class="promotion-banner">
    @if($promocion->image)
        <img src="{{ asset('storage/' . $promocion->image) }}" 
             class="img-fluid rounded ejemplo-img-fluid-cover" 
             alt="{{ $promocion->title }}"
             >
    @endif
    <div class="promotion-overlay">
        <h3>{{ $promocion->title }}</h3>
        <p>{{ $promocion->description }}</p>
        <span class="badge bg-danger">{{ $promocion->discount }}% Descuento</span>
    </div>
</div>

<!-- EJEMPLO 3: Galería de servicios con imágenes -->
<div class="row g-4">
    @foreach($servicios as $servicio)
        <div class="col-md-4">
            <div class="service-card">
                @if($servicio->image)
                    <img src="{{ asset('storage/' . $servicio->image) }}" 
                         alt="{{ $servicio->name }}"
                         class="img-fluid rounded mb-3 ejemplo-img-cover-200"
                         >
                @else
                    <div class="placeholder-image bg-light rounded mb-3 d-flex align-items-center justify-content-center ejemplo-box-200"
                         >
                        <i class="fas fa-spa text-muted ejemplo-icon-2"></i>
                    </div>
                @endif
                <h5>{{ $servicio->name }}</h5>
                <p class="text-muted small">{{ Str::limit($servicio->description, 100) }}</p>
                <p class="price"><strong>${{ number_format($servicio->price, 2) }}</strong></p>
                <a href="#" class="btn btn-sm btn-primary">Reservar</a>
            </div>
        </div>
    @endforeach
</div>

<!-- EJEMPLO 4: Mostrar imagen con lazy loading (para mejorar rendimiento) -->
<img src="{{ asset('storage/' . $servicio->image) }}" 
     alt="{{ $servicio->name }}"
     loading="lazy"
     class="img-fluid rounded ejemplo-img-max-500">

<!-- EJEMPLO 5: Imagen responsiva con srcset -->
<picture>
    @if($servicio->image)
        <img src="{{ asset('storage/' . $servicio->image) }}" 
             alt="{{ $servicio->name }}"
             class="img-fluid rounded ejemplo-w-100">
    @else
        <img src="{{ asset('images/no-image-placeholder.jpg') }}" 
             alt="Sin imagen"
             class="img-fluid rounded ejemplo-w-100">
    @endif
</picture>

<!-- EJEMPLO 6: Mostrar imagen en modal -->
@if($promocion->image)
    <div class="modal fade" id="promotionModal{{ $promocion->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $promocion->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('storage/' . $promocion->image) }}" 
                         alt="{{ $promocion->title }}"
                         class="img-fluid">
                    <p class="mt-3">{{ $promocion->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endif




