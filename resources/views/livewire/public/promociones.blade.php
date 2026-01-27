<section>
    <h2>Promociones</h2>

    @if ($promociones->count())

        <div class="services-wrapper">
            <button class="nav-btn left" onclick="scrollPromos(-1)">‹</button>

            <div class="services-slider promo-slider-centered" id="promoSlider">
                @foreach ($promociones as $promo)
                    <div class="card service-card" onclick="openPromoModal({{ $promo->id }})" style="cursor: pointer;">
                        <h3>{{ $promo->title }}</h3>

                        <p>{{ $promo->description }}</p>

                        <p style="margin-top:10px;color:#fde68a;">
                            <strong>Descuento:</strong> {{ $promo->discount }}%
                        </p>

                        <small style="opacity:.8;">
                            Válido del
                            {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                            al
                            {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                        </small>
                    </div>
                @endforeach
            </div>

            <button class="nav-btn right" onclick="scrollPromos(1)">›</button>
        </div>

    @else
        <p style="text-align:center;opacity:.7;">
            No hay promociones activas por el momento.
        </p>
    @endif
</section>

<!-- Modal de Promoción -->
<div id="promoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closePromoModal()">&times;</span>
        <div id="modalContent"></div>
    </div>
</div>

<script>
function openPromoModal(promoId) {
    // Aquí puedes hacer una petición AJAX para obtener los detalles completos
    fetch(`/api/promocion/${promoId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalContent').innerHTML = `
                <h2 style="color: #d4af37;">${data.title}</h2>
                <p>${data.description}</p>
                <p style="color: #22c55e; font-size: 24px; font-weight: bold;">${data.discount}% DE DESCUENTO</p>
                <p><strong>Válido desde:</strong> ${new Date(data.start_date).toLocaleDateString()}</p>
                <p><strong>Hasta:</strong> ${new Date(data.end_date).toLocaleDateString()}</p>
                ${data.image ? `<img src="${data.image}" alt="${data.title}" style="max-width: 100%; margin-top: 20px;">` : ''}
            `;
            document.getElementById('promoModal').style.display = 'block';
        });
}

function closePromoModal() {
    document.getElementById('promoModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('promoModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
}

.modal-content {
    background-color: #2a2a2a;
    margin: 10% auto;
    padding: 30px;
    border: 1px solid #d4af37;
    border-radius: 16px;
    width: 80%;
    max-width: 600px;
    color: #e5e7eb;
}

.close {
    color: #d4af37;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #fff;
}
</style>
