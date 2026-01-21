<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CitaEstado;
use App\Models\Empleado;
use Illuminate\Http\Request;

class AdminCitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['cliente', 'servicio', 'empleado'])->get();
        $empleados = Empleado::where('activo', 1)->get();

        return view('admin.citas.index', compact('citas', 'empleados'));
    }

    public function confirmar(Cita $cita)
    {
        $cita->update([
            'estado' => 'confirmada'
        ]);

        CitaEstado::create([
            'cita_id' => $cita->id,
            'estado' => 'confirmada',
            'usuario_id' => auth()->id(),
            'fecha_cambio' => now()
        ]);

        return back()->with('success', 'Cita confirmada correctamente');
    }

    public function cancelar(Cita $cita)
    {
        $cita->update([
            'estado' => 'cancelada'
        ]);

        CitaEstado::create([
            'cita_id' => $cita->id,
            'estado' => 'cancelada',
            'usuario_id' => auth()->id(),
            'fecha_cambio' => now()
        ]);

        return back()->with('success', 'Cita cancelada correctamente');
    }

    public function asignarEmpleado(Request $request, Cita $cita)
    {
        if ($cita->estado !== 'confirmada') {
            return back()->with('error', 'Solo se puede asignar empleado a citas confirmadas');
        }

        if ($cita->empleado_id) {
            return back()->with('error', 'Esta cita ya tiene un empleado asignado');
        }

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
        ]);

        $cita->update([
            'empleado_id' => $request->empleado_id,
        ]);

        return back()->with('success', 'Empleado asignado correctamente');
    }
}

