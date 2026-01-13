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
        $request->validate(
            [
                'servicio_id' => 'required',
                'fecha' => 'required|date',
                'hora' => 'required',
            ],
            [
                'servicio_id.required' => 'Debes seleccionar un servicio.',
                'fecha.required'       => 'La fecha es obligatoria.',
                'fecha.date'           => 'La fecha no es válida.',
                'hora.required'        => 'La hora es obligatoria.',
            ]
        );

        return redirect()->route('cliente.citas.index')
            ->with('success', 'Cita registrada correctamente');
    }
}
