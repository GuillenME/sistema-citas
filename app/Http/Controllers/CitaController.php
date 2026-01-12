<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        return view('cliente.citas.index');
    }

    public function create()
    {
        return view('cliente.citas.create');
    }

    public function store(Request $request)
    {
        // Aquí luego guardaremos la cita
        return redirect()->route('cliente.citas.index');
    }
}
