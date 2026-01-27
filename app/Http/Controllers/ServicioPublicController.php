<?php

namespace App\Http\Controllers;

use App\Models\Servicio;

class ServicioPublicController extends Controller
{
   public function index()
{
    $servicios = Servicio::where('active', true)->get();

    return view('servicios.index', compact('servicios'));
}

}
