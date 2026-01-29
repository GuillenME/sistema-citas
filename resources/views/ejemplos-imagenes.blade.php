<?php
/**
 * Ejemplos de cómo mostrar imágenes en vistas públicas
 * Copia y adapta estos ejemplos a tus vistas
 */
?>

<!-- EJEMPLO 1: Mostrar imagen de un servicio en tarjeta -->
<div class="card">
    @if($servicio->image)
        <img src="{{ asset('storage/' . $servicio->image) }}" 
             class="card-img-top" 
             alt="{{ $servicio->name }}"
             style="object-fit: cover; height: 250px;">
    @else
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
             style="height: 250px;">
            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
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
             class="img-fluid rounded" 
             alt="{{ $promocion->title }}"
             style="width: 100%; height: auto; object-fit: cover;">
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
                         class="img-fluid rounded mb-3"
                         style="width: 100%; height: 200px; object-fit: cover;">
                @else
                    <div class="placeholder-image bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                         style="width: 100%; height: 200px;">
                        <i class="fas fa-spa text-muted" style="font-size: 2rem;"></i>
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
     class="img-fluid rounded"
     style="width: 100%; max-width: 500px;">

<!-- EJEMPLO 5: Imagen responsiva con srcset -->
<picture>
    @if($servicio->image)
        <img src="{{ asset('storage/' . $servicio->image) }}" 
             alt="{{ $servicio->name }}"
             class="img-fluid rounded"
             style="width: 100%;">
    @else
        <img src="{{ asset('images/no-image-placeholder.jpg') }}" 
             alt="Sin imagen"
             class="img-fluid rounded"
             style="width: 100%;">
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

<!-- ESTILOS CSS OPCIONALES -->
<style>
    .service-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .promotion-banner {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .promotion-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: white;
        padding: 20px;
    }

    .placeholder-image {
        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
    }
</style>
