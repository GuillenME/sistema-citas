<div class="promos-wrapper">

    <style>
        .promos-wrapper{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .promo-card{
            background: rgba(17, 24, 39, .9);
            padding: 25px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,.1);
            transition: .3s;
            text-align: center;
        }

        .promo-card:hover{
            transform: translateY(-6px);
        }

        .promo-card h3{
            color: #93c5fd;
            margin-bottom: 12px;
            font-size: 20px;
        }

        .promo-card p{
            font-size: 14px;
            margin-bottom: 10px;
            color: #e5e7eb;
        }

        .promo-price{
            font-size: 22px;
            font-weight: bold;
            color: #22c55e;
            margin-top: 15px;
            display: block;
        }

        .promo-dates{
            font-size: 12px;
            color: #94a3b8;
            margin-top: 8px;
        }
    </style>

    @forelse($promociones as $promo)
        <div class="promo-card">
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
