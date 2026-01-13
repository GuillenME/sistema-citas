<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CitaEstado;
use Illuminate\Http\Request;

class AdminCitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['cliente', 'servicio'])->get();

        return view('admin.citas.index', compact('citas'));
    }

    public function confirmar(Cita $cita)
    {
        $cita->update([
            'estado' => 'confirmada'
        ]);

        CitaEstado::create([
            'cita_id' => $cita->id,
            'estado' => 'confirmada',
            'usuairio_id' => auth()->user()->id,
            'fecha_cambio' => now()
        ]);
        
        return back()->with('success', 'Cita confirmada correctamente');
    }

    public function cancelar(Cita $cita)
    {
        $cita->update([
            'estado' => 'cancelada'
        ]);

        return back()->with('success', 'Cita cancelada correctamente');
    }
}
