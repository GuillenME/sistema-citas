<?php

namespace App\Http\Controllers;

use App\Models\Servicio;

class ServicioPublicController extends Controller
{
    public function index()
    {
        // SOLO servicios activos
        $servicios = Servicio::where('activo', true)->get();

        return view('servicios.index', compact('servicios'));
    }
}
