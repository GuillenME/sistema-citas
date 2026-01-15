<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Cita;   
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function create()
    {
        $servicios = Servicio::all();

        $usuarios = Usuario::where('rol_id', 2)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('recepcionista.citas.create', compact('servicios', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required'
        ]);

        Cita::create([
            'usuario_id' => $request->usuario_id,
            'servicio_id' => $request->servicio_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'estado' => 'pendiente',
        ]);
    }
}
