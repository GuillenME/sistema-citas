<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;


class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::where('cliente_id', auth()->id())
            ->with('servicio')
            ->get();

        return view('cliente.citas.index', compact('citas'));
    }


    public function create()
    {
        $servicios = Servicio::all();
        return view('cliente.citas.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required'
        ]);

        Cita::create([
            'cliente_id'   => auth()->id(),
            'servicio_id'  => $request->servicio_id,
            'fecha'        => $request->fecha,
            'hora_inicio'  => $request->hora,
            'estado'       => 'pendiente_de_anticipo'
        ]);


        return redirect()->route('cliente.citas.index')
            ->with('success', 'Cita registrada correctamente');
    }
}
