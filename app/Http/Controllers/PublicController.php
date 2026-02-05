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
            ->orderBy('id', 'desc')
            ->take(4)
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
            ->take(4)
            ->get();

        $noticias = Noticia::where('published', 1)
            ->orderBy('publication_date', 'desc')
            ->take(3)
            ->get();

        $reviews = Review::orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $homeSetting = HomeSetting::first();

        return view('public.index', compact(
            'servicios',
            'promociones',
            'homeServicios',
            'homePromociones',
            'noticias',
            'reviews',
            'homeSetting'
        ));
    }
}
