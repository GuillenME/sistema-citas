<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Promocion;
use App\Models\Noticia;

class PublicController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('activo', 1)->get();

        $promociones = Promocion::where('publicada', 1)
            ->whereDate('fecha_inicio', '<=', now())
            ->whereDate('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $noticias = Noticia::where('publicada', 1)
            ->orderBy('fecha_publicacion', 'desc')
            ->take(3)
            ->get();

        return view('public.index', compact(
            'servicios',
            'promociones',
            'noticias'
        ));
    }
}
