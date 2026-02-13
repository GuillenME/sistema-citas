<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Promocion;
use App\Models\Noticia;
use App\Models\Review;
use App\Models\HomeSetting;

class PublicController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('active', 1)->get();
        $homeServicios = Servicio::where('active', 1)
            ->where('featured_on_home', 1)
            ->orderBy('home_position')
            ->take(10)
            ->get();

        $promociones = Promocion::where('published', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();

        $homePromociones = Promocion::where('published', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->take(1)
            ->get();

        $noticias = Noticia::where('published', 1)
            ->orderBy('publication_date', 'desc')
            ->take(4)
            ->get();

        $reviews = Review::orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $reviewsHasMore = Review::count() > 4;

        $homeSetting = HomeSetting::first();

        return view('public.index', compact(
            'servicios',
            'promociones',
            'homeServicios',
            'homePromociones',
            'noticias',
            'reviews',
            'reviewsHasMore',
            'homeSetting'
        ));
    }

    public function comentarios()
    {
        $reviews = Review::orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.comentarios', compact('reviews'));
    }

    public function noticias()
    {
        $noticias = Noticia::where('published', 1)
            ->whereDate('publication_date', '<=', today())
            ->orderBy('publication_date', 'desc')
            ->paginate(9);

        return view('public.noticias', compact('noticias'));
    }
}
