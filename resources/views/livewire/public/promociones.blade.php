<div class="promos-wrapper">

    <style>
        .promos-wrapper{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .promo-card{
            background: #5b4233;
            padding: 14px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,.08);
            transition: .3s;
            text-align: left;
            display: flex;
            flex-direction: column;
            min-height: 300px;
        }

        .promo-card:hover{
            transform: translateY(-6px);
            box-shadow: 0 0 25px rgba(0,0,0,.2);
        }

        .promo-card h3{
            color: #fccc7c;
            margin: 6px 0 8px;
            font-size: 18px;
        }

        .promo-card img{
            width:100%;
            height:140px;
            object-fit:contain;
            object-position:center;
            border-radius:14px;
            margin-bottom:10px;
            border:1px solid rgba(255,255,255,.12);
            background: rgba(0,0,0,.25);
        }

        .promo-card p{
            font-size: 13px;
            margin-bottom: 8px;
            color: #e5e7eb;
            opacity: .9;
        }

        .promo-price{
            font-size: 20px;
            font-weight: bold;
            color: #e48815;
            margin-top: auto;
            display: block;
        }

        .promo-dates{
            font-size: 11px;
            color: #c0a799;
            margin-top: 6px;
        }
    </style>

    @forelse($promociones as $promo)
        <div class="promo-card">
            <img
                src="{{ $promo->image ? asset('storage/' . $promo->image) : asset('imagenes/servicio_default.png') }}"
                alt="{{ $promo->title }}"
            >
            <h3>{{ $promo->title }}</h3>

            <p>{{ $promo->description }}</p>

            <span class="promo-price">
                ${{ number_format($promo->price, 2) }}
            </span>

            <div class="promo-dates">
                Vigente del {{ $promo->start_date }} al {{ $promo->end_date }}
            </div>
        </div>
    @empty
        <p style="grid-column:1/-1; text-align:center;">
            No hay promociones activas por el momento.
        </p>
    @endforelse

</div>
